<?php
/**
 * The Template for displaying product archives, including the main shop page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 * 
 * Custom theme template - IMMIGRO
 * @package IMMIGRO
 * @author  tonatheme
 */
get_header();
global $wp_query;
$data  = \IMMIGRO\Includes\Classes\Common::instance()->data( 'shop' )->get();
$class = ( $data->get( 'layout' ) != 'full' ) ? 'col-xs-12 col-sm-12 col-md-12 col-lg-8' : 'col-xs-12 col-sm-12 col-md-12';
do_action( 'immigro_banner', $data );
$options = $data->get( 'sidebar' );

if ( class_exists( '\Elementor\Plugin' ) AND $data->get( 'tpl-type' ) == 'e' AND $data->get( 'tpl-elementor' ) ) {
	echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $data->get( 'tpl-elementor' ) );
} else {
	?>


<section class="blog-pagev2-area mr_shop">
    <div class="container">
        <div class="row">	
			<!-- sidebar area -->
            <?php if ( $data->get( 'layout' ) == 'left' ) { ?>
		
				<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
		<div class="sidebar-wrapper">
			<?php dynamic_sidebar( $options ); ?>
		</div>
	</div>
		
		 <?php	} ?>
		  <?php if ( $data->get( 'layout' ) == 'right' ) { ?>
			
		<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 side_right">
		<div class="sidebar-wrapper">
			<?php dynamic_sidebar( $options ); ?>
		</div>
	</div>
		
		 <?php	} ?>	

			<!-- sidebar area -->
			
			<div class="content-side <?php echo esc_attr($class); ?> ">
            	<div class="our-shop">
				<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
                	
                <!--Sort By-->
                <div class="items-sorting row  mr_shop_sorting">
                    <div class="text">
                        <?php woocommerce_result_count(); ?>
                    </div>
                    <div class="woocommerce-form">
                        <?php
                            /**
                             * woocommerce_before_shop_loop hook
                             *
                             * @hooked woocommerce_result_count - 20
                             * @hooked woocommerce_catalog_ordering - 30
                             */
                            do_action( 'woocommerce_before_shop_loop' );
                        ?>
                    </div>
                </div>   
                <?php endif; ?>
			
                
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

    
		</div>
	</div>
</section>
<?php
}
get_footer(); ?>
