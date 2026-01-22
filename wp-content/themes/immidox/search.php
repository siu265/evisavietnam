<?php
/**
 * Tag Main File.
 *
 * @package IMMIGRO
 * @author  tonatheme
 * @version 1.0
 */

get_header();
global $wp_query;
$data  = \IMMIGRO\Includes\Classes\Common::instance()->data( 'search' )->get();
$layout = $data->get( 'layout' );
$sidebar = $data->get( 'sidebar' );
$layout = ( $layout ) ? $layout : 'right';
$sidebar = ( $sidebar ) ? $sidebar : 'default-sidebar';
if (is_active_sidebar( $sidebar )) {$layout = 'right';} else{$layout = 'full';}
$class = ( !$layout || $layout == 'full' ) ? 'col-xs-12 col-sm-12 col-md-12' : 'col-xl-8 col-lg-7 col-xs-12 col-sm-12';
if ( class_exists( '\Elementor\Plugin' ) AND $data->get( 'tpl-type' ) == 'e' AND $data->get( 'tpl-elementor' ) ) {
	echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $data->get( 'tpl-elementor' ) );
} else {
	?>
	
<?php if ( class_exists( '\Elementor\Plugin' )):?>


	<?php do_action( 'immigro_banner', $data );?>
<?php else:?> 
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
<?php endif;?>


 <?php if( have_posts() ) : ?>    
<section class="sidebar-page-container p_relative sec-pad">
	<div class="auto-container">
		<div class="row clearfix">
		
		<!--Sidebar Start-->
		<?php
		if ( $data->get( 'layout' ) == 'left' ) {
			do_action( 'immigro_sidebar', $data );
		}
		?>
		
		<div class="content-side <?php echo esc_attr( $class ); ?>">
			
			 <div class="blog-standard-content p_relative d_block">
			
				<?php
					while ( have_posts() ) :
						the_post();
						immigro_template_load( 'templates/blog/blog.php', compact( 'data' ) );
					endwhile;
					wp_reset_postdata();
				?>
				
			</div>
			
			<!--Pagination-->
			<div class="pagination-wrapper clearfix">
			
				<?php immigro_the_pagination(); ?>
			</div>
			
		</div>
		
		<!--Sidebar Start-->
		<?php
		if ( $data->get( 'layout' ) == 'right' ) {
			do_action( 'immigro_sidebar', $data );
		}
		?>
	</div>
</div>
</section>	
<?php else : ?>  
<?php //get_template_part('templates/search'); ?>	
<section class="search-not-found p_relative centred">

	<div class="pattern-layer" style="background-image: url(<?php echo esc_url(get_template_directory_uri().'/assets/images/shape/shape-45.png');?>);"></div>

	<div class="auto-container">
		<div class="inner-box">
			<figure class="image-box">
				<img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/icons/search.png');?>" alt="<?php echo wp_kses( $options->get( '404_title'), $allowed_html ); ?>">
			</figure>

			<?php if($options->get('search_page_title' ) ): ?>

			<h2><?php echo wp_kses( $options->get( 'search_page_title'), $allowed_html ); ?></h2>

			<?php else : ?>

			<h2><?php esc_html_e( 'Search Not Found!', 'immigro' ); ?></h2>

			<?php endif; ?>	

			<?php if($options->get('search_page_text' ) ): ?>

			<p><?php echo(wp_kses($options->get('search_page_text' ), $allowed_html )) ?></p>

			<?php else : ?>

			<div class="search_text">	

				<p><?php esc_html_e( 'Cant find what you need? Take a moment and do a search below or start from our', 'immigro' ); ?></p>

			</div>

			<?php endif; ?>	

			<div class="btn-box">
				
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-1"><?php esc_html_e( 'Back to Homepage', 'immigro' ); ?><span></span></a>
			</div>

			<?php echo get_search_form(); ?>

		</div>
	</div>
</section>
<?php endif; ?>
<?php
}
get_footer();
