<?php
/**
 * Plugin Name: Protect WordPress Core & Fix 404 (Final)
 * Description: Bảo vệ core và khôi phục file .htaccess chuẩn để sửa lỗi 404.
 * Version: 1.3
 * Author: Evisa Security
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. Các Rule BẢO MẬT (Chặn truy cập/thực thi)
 */
function evisa_get_security_rules( $context = 'root' ) {
    $rules = array();

    // --- A. Root: Chỉ chặn file nhạy cảm, tắt listing ---
    if ( 'root' === $context ) {
        $rules[] = 'Options -Indexes';
        $rules[] = '<Files wp-config.php>';
        $rules[] = 'order allow,deny';
        $rules[] = 'deny from all';
        $rules[] = '</Files>';
        $rules[] = '<FilesMatch "^.*(error_log|wp-config\.php|php.ini|\.[hH][tT][aApP].*)$">';
        $rules[] = 'Order deny,allow';
        $rules[] = 'Deny from all';
        $rules[] = '</FilesMatch>';
    }

    // --- B. Wp-includes & Uploads: Chặn chạy PHP hoàn toàn ---
    if ( 'wp-includes' === $context || 'uploads' === $context ) {
        $rules[] = '<FilesMatch "\.(?i:php)$">';
        $rules[] = '  <IfModule !mod_authz_core.c>';
        $rules[] = '    Order allow,deny';
        $rules[] = '    Deny from all';
        $rules[] = '  </IfModule>';
        $rules[] = '  <IfModule mod_authz_core.c>';
        $rules[] = '    Require all denied';
        $rules[] = '  </IfModule>';
        $rules[] = '</FilesMatch>';
    }

    return $rules;
}

/**
 * 2. Các Rule CHUẨN CỦA WORDPRESS (Sửa lỗi 404)
 * Đây chính là đoạn code bạn yêu cầu thêm vào.
 */
function evisa_get_wp_standard_rules() {
    return array(
        '<IfModule mod_rewrite.c>',
        'RewriteEngine On',
        'RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]',
        'RewriteBase /',
        'RewriteRule ^index\.php$ - [L]',
        'RewriteCond %{REQUEST_FILENAME} !-f',
        'RewriteCond %{REQUEST_FILENAME} !-d',
        'RewriteRule . /index.php [L]',
        '</IfModule>',
    );
}

/**
 * 3. Hàm thực thi ghi file .htaccess
 */
function evisa_apply_htaccess_rules() {
    require_once( ABSPATH . 'wp-admin/includes/misc.php' );
    require_once( ABSPATH . 'wp-admin/includes/file.php' );

    $root_htaccess = ABSPATH . '.htaccess';

    // BƯỚC 1: Ghi Rule Bảo Mật (vào block # BEGIN Protect WP Core)
    if ( is_writable( ABSPATH ) || is_writable( $root_htaccess ) ) {
        insert_with_markers( $root_htaccess, 'Protect WP Core', evisa_get_security_rules( 'root' ) );
    }

    // BƯỚC 2: Ghi Rule Chuẩn WordPress (vào block # BEGIN WordPress) - FIX LỖI 404
    // Chúng ta dùng marker là 'WordPress' để hệ thống nhận diện đây là core rules
    if ( is_writable( ABSPATH ) || is_writable( $root_htaccess ) ) {
        insert_with_markers( $root_htaccess, 'WordPress', evisa_get_wp_standard_rules() );
    }

    // BƯỚC 3: Xử lý các thư mục con (wp-includes, uploads)
    $includes_dir = ABSPATH . WPINC;
    $includes_htaccess = $includes_dir . '/.htaccess';
    if ( is_dir( $includes_dir ) && is_writable( $includes_dir ) ) {
        insert_with_markers( $includes_htaccess, 'Protect WP Core - Includes', evisa_get_security_rules( 'wp-includes' ) );
    }

    $upload_dir = wp_upload_dir();
    $uploads_htaccess = $upload_dir['basedir'] . '/.htaccess';
    if ( is_dir( $upload_dir['basedir'] ) && is_writable( $upload_dir['basedir'] ) ) {
        insert_with_markers( $uploads_htaccess, 'Protect WP Core - Uploads', evisa_get_security_rules( 'uploads' ) );
    }
}

/**
 * 4. Hook kích hoạt và kiểm tra định kỳ
 */
function evisa_activate_plugin() {
    evisa_apply_htaccess_rules();
}
register_activation_hook( __FILE__, 'evisa_activate_plugin' );

add_action( 'admin_init', function() {
    // Chạy kiểm tra 1 lần mỗi ngày hoặc nếu file .htaccess bị xóa
    if ( ! get_transient( 'evisa_htaccess_check_fixed_404' ) || ! file_exists( ABSPATH . '.htaccess' ) ) {
        evisa_apply_htaccess_rules();
        set_transient( 'evisa_htaccess_check_fixed_404', '1', DAY_IN_SECONDS );
    }
});