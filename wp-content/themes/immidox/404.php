<?php
/**
 * 404 page file
 *
 * @package    WordPress
 * @subpackage Immigro
 * @author     tonatheme
 * @version    1.0
 */

$text = sprintf(__('It seems we can\'t find what you\'re looking for. Perhaps searching can help ', 'immigro'), esc_url(home_url('/')));
$allowed_html = wp_kses_allowed_html( 'post' );
?>
<?php get_header();
$data = \IMMIGRO\Includes\Classes\Common::instance()->data( '404' )->get();
do_action( 'immigro_banner', $data );
$options = immigro_WSH()->option();

if ( class_exists( '\Elementor\Plugin' ) AND $data->get( 'tpl-type' ) == 'e' AND $data->get( 'tpl-elementor' ) ) {
	echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $data->get( 'tpl-elementor' ) );
} else {
	?>
	


<section class="error-section p_relative centred">

	<div class="pattern-layer" style="background-image: url(<?php echo esc_url(get_template_directory_uri().'/assets/images/shape/shape-46.png');?>);">

	</div>
	<div class="auto-container">
		<div class="inner-box">
			<figure class="error-image">
				<img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/icons/error-1.png');?>" alt="<?php echo wp_kses( $options->get( '404_page_text'), $allowed_html ); ?>">
			</figure>

			<?php if($options->get('404_page_title' ) ): ?>	
			<h2><?php echo wp_kses( $options->get( '404_title'), $allowed_html ); ?></h2>
			<?php else: ?>
			<h2 class="d_block fs_50 lh_60 fw_bold mb_12"><?php esc_html_e( 'Oops! That Page Can Not be Found.', 'immigro' ); ?></h2>
			<?php endif; ?>

			<?php if($options->get('back_home_btn' ) ): ?>		
			<?php if($options->get('back_home_btn_label' ) ): ?>
			
			<div class="btn-box ">
				<a href="<?php echo( home_url( '/' ) ); ?>" class="btn-1"><?php echo wp_kses( $options->get( 'back_home_btn_label'), $allowed_html ); ?><span></span></a>
			</div>

			<?php else: ?>
			
			<div class="btn-box ">
				<a href="<?php echo( home_url( '/' ) ); ?>" class="btn-1"><?php esc_html_e( 'Back to Homepage', 'immigro' ); ?><span></span></a>
			</div>

			<?php endif; ?>		
			<?php endif; ?>

		</div>
	</div>
</section>
	
  
<?php
}
get_footer(); ?>
