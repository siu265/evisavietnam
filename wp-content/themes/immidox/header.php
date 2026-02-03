<?php
/**
 * The header for our theme
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @package IMMIGRO
 * @since   1.0
 * @version 1.0
 */
$options = immigro_WSH()->option();
$allowed_html = wp_kses_allowed_html( 'post' );
$icon_href = $options->get( 'image_favicon' ); 
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
	<meta charset="UTF-8">
	<?php if (function_exists( 'has_site_icon' ) || has_site_icon() ): ?>
		<?php if( $icon_href ):?>
		
		
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo esc_url($icon_href['url']); ?>">
        <link rel="icon" type="image/png" sizes="32x32" href="<?php echo esc_url($icon_href['url']); ?>">
        <link rel="icon" type="image/png" sizes="16x16" href="<?php echo esc_url($icon_href['url']); ?>">
        <link rel="manifest" href="<?php echo esc_url($icon_href['url']); ?>">
        
        
		<?php endif; ?>
		<?php endif; ?>
	<!-- Responsive -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	
    <?php wp_head(); ?>



</head>
	
<body <?php body_class(); ?>> 

<?php
if ( ! function_exists( 'wp_body_open' ) ) {
		function wp_body_open() {
			do_action( 'wp_body_open' );
		}
}?>
	
<main class="boxed_wrapper ltr <?php if ( $options->get( 'theme_rtl' ) ) { echo esc_attr( __( 'rtl', 'immigro' ) ); } ?>">	
	
<?php do_action( 'immigro_main_header' ); ?>	
<?php if($options->get( 'mouse_effect' ) ):?>	
<!-- mouse-pointer -->
<div class="mouse-pointer" id="mouse-pointer">
	<div class="icon"><i class="far fa-angle-left"></i><i class="far fa-angle-right"></i></div>
</div>
<!-- mouse-pointer end -->
<?php endif; ?>	
	
<?php if ( $options->get( 'theme_preloader' ) && ! ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-received' ) ) ) : ?>	
<?php
$preloader_type = $options->get( 'preloader_type' );
if ( empty( $preloader_type ) ) {
	$preloader_type = 'text';
}
?>
<!-- preloader -->
<div class="loader-wrap">
    <div class="preloader">
        <div class="preloader-close">x</div>
        <div class="handle-preloader">
            <div class="animation-preloader <?php echo esc_attr( $preloader_type === 'logo' ? 'preloader-logo-type' : '' ); ?>">
                <div class="spinner"></div>
                <?php if ( $preloader_type === 'logo' ) : ?>
                    <?php
                    $logo = $options->get( 'preloader_logo' );
                    $logo_url = '';
                    if ( ! empty( $logo['url'] ) ) {
                        $logo_url = $logo['url'];
                    } elseif ( ! empty( $logo['id'] ) ) {
                        $u = wp_get_attachment_url( (int) $logo['id'] );
                        if ( $u ) {
                            $logo_url = $u;
                        }
                    }
                    if ( $logo_url ) :
                    ?>
                    <div class="preloader-logo-wrap">
                        <div class="preloader-logo-ring"></div>
                        <img src="<?php echo esc_url( $logo_url ); ?>" alt="Loading" class="preloader-logo-img" />
                    </div>
                    <?php else : ?>
                    <div class="preloader-logo-wrap preloader-logo-placeholder">
                        <div class="preloader-logo-ring"></div>
                        <span class="preloader-logo-img">Logo</span>
                    </div>
                    <?php endif; ?>
                <?php else : ?>
                    <?php
                    $preloader_text = $options->get( 'preloader_text', ' ' );
                    // Tránh output "1" khi giá trị là integer/boolean từ DB
                    if ( ! is_string( $preloader_text ) || $preloader_text === '1' || $preloader_text === 1 ) {
                        $preloader_text = ' ';
                    }
                    $preloader_text = wp_check_invalid_utf8( $preloader_text ) ?: ' ';
                    echo wp_kses( $preloader_text, $allowed_html );
                    ?>
                <?php endif; ?>
            </div>  
        </div>
    </div>
</div>
<!-- preloader end -->
<?php endif; ?>  
		