<?php
/**
 * Banner Template
 *
 * @package    WordPress
 * @subpackage Tona Theme
 * @author     Tona Theme
 * @version    1.0
 */

if ( $data->get( 'enable_banner' ) AND $data->get( 'banner_type' ) == 'e' AND ! empty( $data->get( 'banner_elementor' ) ) ) {
	echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $data->get( 'banner_elementor' ) );

	return false;
}

?>


<?php if ( $data->get( 'enable_banner' ) ) : ?>

<section class="page-title p_relative centred">
	<?php if ( $data->get( 'banner' ) ) : ?>
	<div class="bg-layer" style="background-image: url(<?php echo esc_url( $data->get( 'banner' ) ); ?>);"></div>
	<?php else : ?>	
	<div class="bg-layer" style="background-image: url(<?php echo esc_url(get_template_directory_uri().'/assets/images/background/page-title.jpg');?>);"></div>
	<?php endif; ?>	
	
	
	
	<div class="auto-container">
		<div class="title">
			<h1><?php if( $data->get( 'title' ) ) echo wp_kses( $data->get( 'title' ), true ); else( wp_title( '' ) ); ?></h1>
		</div>
	</div>
</section>


<div class="bread-crumb">
	<div class="auto-container">
		<ul class="list clearfix">
			<?php echo immigro_the_breadcrumb(); ?>
		</ul>
	</div>
</div>

<?php endif; ?>
