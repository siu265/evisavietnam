<?php
/**
 * Blog Post Main File.
 *
 * @package IMMIGRO
 * @author  tonatheme
 * @version 1.0
 */

get_header();
$data    = \IMMIGRO\Includes\Classes\Common::instance()->data( 'single' )->get();
$layout = $data->get( 'layout' );
$sidebar = $data->get( 'sidebar' );
if (is_active_sidebar( $sidebar )) {$layout = 'right';} else{$layout = 'full';}
$class = ( !$layout || $layout == 'full' ) ? 'col-xs-12 col-sm-12 col-md-12' : 'col-xl-8 col-lg-7 col-xs-12 col-sm-12';
$options = immigro_WSH()->option();

if ( class_exists( '\Elementor\Plugin' ) && $data->get( 'tpl-type' ) == 'e') {
	
	while(have_posts()) {
	   the_post();
	   the_content();
    }

} else {
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

<section class="sidebar-page-container p_relative">
	<div class="auto-container">
		<div class="row clearfix">
			<?php
				if ( $data->get( 'layout' ) == 'left' ) {
					do_action( 'immigro_sidebar', $data );
				}
			?>
			<div class="wp-style content-side <?php echo esc_attr( $class ); ?>">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php immigro_template_load( 'templates/blog-single/single-content.php', compact( 'options', 'data' ) ); ?>
				<?php endwhile; ?>
			</div>
			<?php
				if ( $data->get( 'layout' ) == 'right' ) {
					do_action( 'immigro_sidebar', $data );
				}
			?>
		</div>
	</div>
</section>

<?php
}
get_footer();
