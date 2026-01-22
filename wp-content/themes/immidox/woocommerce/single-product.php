<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 1.6.4
 * 
 * Custom theme template - IMMIGRO
 * @package IMMIGRO
 * @author  Template Path
 */
get_header();
global $wp_query;
$data  = \IMMIGRO\Includes\Classes\Common::instance()->data( 'product' )->get();
$class = ( $data->get( 'layout' ) != 'full' ) ? 'col-xs-12 col-sm-12 col-md-12 col-lg-8' : 'col-xs-12 col-sm-12 col-md-12';
do_action( 'immigro_banner', $data );
$options = $data->get( 'sidebar' );

if ( class_exists( '\Elementor\Plugin' ) AND $data->get( 'tpl-type' ) == 'e' AND $data->get( 'tpl-elementor' ) ) {
	echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $data->get( 'tpl-elementor' ) );
} else {
	?>

<section class="mr_shop_single">
    <div class="container">
        <div class="row">
         
			
		<!-- sidebar area -->
           <?php if ( $data->get( 'layout' ) == 'left' ) { ?>		
			<div class="col-xl-4 col-md-12 col-sm-12">
				<div class="sidebar-wrapper">
					<?php dynamic_sidebar( $options ); ?>
				</div>
			</div>		
		 <?php	} ?>
		  <?php if ( $data->get( 'layout' ) == 'right' ) { ?>			
			<div class="col-xl-4 col-md-12 col-sm-12 side_right">
				<div class="sidebar-wrapper">
					<?php dynamic_sidebar( $options ); ?>
				</div>
			</div>		
		 <?php	} ?>	
		<!-- sidebar area -->
			
			<div class="content-side <?php echo esc_attr($class); ?> ">
            	
                <div class="shop-content mr_single_content">
                	<?php
						/**
						 * woocommerce_before_main_content hook
						 *
						 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
						 * @hooked woocommerce_breadcrumb - 20
						 */
						do_action( 'woocommerce_before_main_content' );
					?>
						<?php while ( have_posts() ) : the_post(); ?>
							<?php wc_get_template_part( 'content', 'single-product' ); ?>
						<?php endwhile; // end of the loop. ?>
					<?php
						/**
						 * woocommerce_after_main_content hook
						 *
						 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
						 */
						do_action( 'woocommerce_after_main_content' );
					?>
                </div>
                
            </div>  	
		</div>
	</div>
</section>
<?php
}
get_footer( 'shop' );
