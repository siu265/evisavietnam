<?php
/**
 * Plugin Name: Protect WordPress Core
 * Description: Chặn chỉnh sửa/thêm file trong wp-includes, wp-admin và thư mục core. Tạo .htaccess bảo vệ.
 * Version: 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Thư mục cần bảo vệ khỏi ghi file
 */
function evisa_protect_core_dirs() {
	return array(
		ABSPATH . 'wp-includes/',
		ABSPATH . 'wp-admin/',
		ABSPATH, // root
	);
}

/**
 * Nội dung .htaccess bảo vệ
 */
function evisa_protect_htaccess_content() {
	return "# Protect WP Core - Auto-generated\n" .
		"<IfModule mod_rewrite.c>\n" .
		"RewriteEngine On\n" .
		"# Chặn truy cập trực tiếp file .php lạ (chỉ cho phép file đã định nghĩa)\n" .
		"</IfModule>\n" .
		"# Tắt directory listing\n" .
		"Options -Indexes\n" .
		"# Chặn thực thi PHP trong thư mục uploads nếu có\n" .
		"<FilesMatch \"\\.ph(p[3457]?|tml|ar)$\">\n" .
		"  <IfModule mod_authz_core.c>\n" .
		"    # Chỉ áp dụng trong thư mục cần bảo vệ\n" .
		"  </IfModule>\n" .
		"</FilesMatch>\n";
}

/**
 * Tạo .htaccess bảo vệ cho các thư mục core
 */
function evisa_protect_write_htaccess() {
	$content = "# Bảo vệ thư mục WordPress Core\n" .
		"# Tạo bởi Protect WordPress Core mu-plugin\n" .
		"# Ngày: " . gmdate( 'Y-m-d H:i:s' ) . "\n\n" .
		"Options -Indexes\n" .
		"<IfModule mod_rewrite.c>\n" .
		"RewriteEngine On\n" .
		"</IfModule>\n";

	$dirs = evisa_protect_core_dirs();
	foreach ( $dirs as $dir ) {
		if ( is_dir( $dir ) && is_writable( $dir ) ) {
			$htaccess = $dir . '.htaccess';
			$exists   = file_exists( $htaccess );
			$written  = @file_put_contents( $htaccess, $content );
			if ( $written && ! $exists ) {
				// Ghi log lần đầu tạo
				if ( function_exists( 'error_log' ) ) {
					error_log( '[PROTECT-WP-CORE] Created .htaccess: ' . $htaccess );
				}
			}
		}
	}
}

/**
 * Kiểm tra và cảnh báo nếu thư mục core có quyền ghi
 */
function evisa_protect_check_writable() {
	if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$dirs    = evisa_protect_core_dirs();
	$writable = array();
	foreach ( $dirs as $dir ) {
		if ( is_dir( $dir ) && is_writable( $dir ) ) {
			$writable[] = str_replace( ABSPATH, '', $dir );
		}
	}
	if ( ! empty( $writable ) && get_transient( 'evisa_protect_warn_writable' ) !== '1' ) {
		add_action( 'admin_notices', function() use ( $writable ) {
			echo '<div class="notice notice-warning"><p><strong>Protect WP Core:</strong> Các thư mục sau đang cho phép ghi: ' . esc_html( implode( ', ', $writable ) ) . '. Để bảo vệ tối đa, chạy script <code>protect-core-permissions.sh</code> qua SSH hoặc đặt chmod 555 cho thư mục.</p></div>';
		});
	}
}

/**
 * Chặn truy cập Theme/Plugin Editor khi DISALLOW_FILE_EDIT chưa set
 */
function evisa_protect_block_file_editor() {
	if ( defined( 'DISALLOW_FILE_EDIT' ) && DISALLOW_FILE_EDIT ) {
		return;
	}
	// Redirect khỏi plugin/theme editor
	if ( isset( $_GET['file'] ) && ( strpos( $_SERVER['SCRIPT_NAME'] ?? '', 'plugin-editor' ) !== false || strpos( $_SERVER['SCRIPT_NAME'] ?? '', 'theme-editor' ) !== false ) ) {
		$file = sanitize_text_field( wp_unslash( $_GET['file'] ) );
		$blocked = array( 'wp-includes', 'wp-admin', 'wp-config', 'index.php' );
		foreach ( $blocked as $b ) {
			if ( strpos( $file, $b ) !== false ) {
				wp_die( esc_html__( 'Chỉnh sửa file core bị chặn bởi Protect WP Core.', 'immigro' ), 403 );
			}
		}
	}
}

/**
 * Hook: Kích hoạt - tạo .htaccess
 */
register_activation_hook( __FILE__, 'evisa_protect_write_htaccess' );

add_action( 'init', function() {
	// Chạy 1 lần/ngày để đảm bảo .htaccess tồn tại
	if ( get_transient( 'evisa_protect_htaccess_check' ) !== '1' ) {
		evisa_protect_write_htaccess();
		set_transient( 'evisa_protect_htaccess_check', '1', DAY_IN_SECONDS );
	}
}, 5 );

add_action( 'admin_init', 'evisa_protect_check_writable', 5 );
add_action( 'admin_init', 'evisa_protect_block_file_editor', 1 );
