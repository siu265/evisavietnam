<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce\Templates
 * @version     4.7.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();
global $wp_query;
$data  = \IMMIGRO\Includes\Classes\Common::instance()->data( 'single' )->get();
$layout = $data->get( 'layout' );
$sidebar = $data->get( 'sidebar' );
$layout = ( $layout ) ? $layout : 'right';
$sidebar = ( $sidebar ) ? $sidebar : 'default-sidebar';
if (is_active_sidebar( $sidebar )) {$layout = 'right';} else{$layout = 'full';}
$class = ( !$layout || $layout == 'full' ) ? 'col-xs-12 col-sm-12 col-md-12' : 'col-xs-12 col-sm-12 col-md-12 col-lg-12';
if ( class_exists( '\Elementor\Plugin' ) AND $data->get( 'tpl-type' ) == 'e' AND $data->get( 'tpl-elementor' ) ) {
	echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $data->get( 'tpl-elementor' ) );
} else {
?>

<?php if ( class_exists( '\Elementor\Plugin' )): ?>
	<?php do_action( 'immigro_banner', $data ); ?>
<?php else: ?>

<section class="page-title p_relative centred">
	
	<?php if ( $data->get( 'banner' ) ) : ?>
	<div class="bg-layer p_absolute l_0 parallax_none parallax-bg" data-parallax='{"y": 100}' style="background-image: url(<?php echo esc_url( $data->get( 'banner' ) ); ?>);"></div>
	<?php else : ?>
	<div class="bg-layer p_absolute l_0 parallax_none parallax-bg" data-parallax='{"y": 100}' style="background-image: url(<?php echo esc_url(get_template_directory_uri().'/assets/images/background/page-title.jpg');?>);"></div>
	<?php endif; ?>	
	<div class="auto-container">
		<div class="content-box">
			<h1 class="d_block fs_60 lh_70 fw_bold mb_10"><?php if( $data->get( 'title' ) ) echo wp_kses( $data->get( 'title' ), true ); else( wp_title( '' ) ); ?></h1>
			<ul class="bread-crumb p_relative d_block mb_8 clearfix">
				<?php echo immigro_the_breadcrumb(); ?>
			</ul>
		</div>
	</div>
</section>
<?php endif; ?>

<!--Start Shop area-->
<section class="mr_shop mr_shop_cat">
    <div class="thm-container">
        <div class="row">	
			
			<!-- sidebar area -->
            <?php
				if ( $data->get( 'layout' ) == 'left' ) {
					do_action( 'immigro_sidebar', $data );
					
					/**
					 * woocommerce_sidebar hook
					 *
					 * @hooked woocommerce_get_sidebar - 10
					 */
					do_action( 'woocommerce_sidebar' );
				}
			?>
			<!-- sidebar area -->
			
			<div class="content-side <?php echo esc_attr($class); ?> ">
            	<div class="our-shop">
			
			
                
                <?php
                    /**
                     * woocommerce_archive_description hook
                     *
                     * @hooked woocommerce_taxonomy_archive_description - 10
                     * @hooked woocommerce_product_archive_description - 10
                     */
                    do_action( 'woocommerce_archive_description' );
                ?>
                
				<?php if ( have_posts() ) : ?>
            
					<?php woocommerce_product_loop_start(); ?>
        
                        <?php woocommerce_product_subcategories(); ?>
        
                        <?php while ( have_posts() ) : the_post(); ?>
        
                            <?php wc_get_template_part( 'content', 'product' ); ?>
        
                        <?php endwhile; // end of the loop. ?>
        
                    <?php woocommerce_product_loop_end(); ?>
        
                    <?php
                        /**
                         * woocommerce_after_shop_loop hook
                         *
                         * @hooked woocommerce_pagination - 10
                         */
                        do_action( 'woocommerce_after_shop_loop' );
                    ?>
        
                <?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>
        
                    <?php wc_get_template( 'loop/no-products-found.php' ); ?>
        
                <?php endif; ?>
                
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
            <!-- sidebar area -->
            <?php
				if ( $data->get( 'layout' ) == 'right' ) {
					do_action( 'immigro_sidebar', $data );
					
					/**
					 * woocommerce_sidebar hook
					 *
					 * @hooked woocommerce_get_sidebar - 10
					 */
					do_action( 'woocommerce_sidebar' );
				}
			?>
    		<!--Sidebar-->
    
		</div>
	</div>
</section>

<?php
}
get_footer( 'shop' );
