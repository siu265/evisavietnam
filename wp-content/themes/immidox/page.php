<?php
/**
 * Default Template Main File.
 *
 * @package IMMIGRO
 * @author  tonatheme
 * @version 1.0
 */

get_header();
$data  = \IMMIGRO\Includes\Classes\Common::instance()->data( 'single' )->get();
$layout = $data->get( 'layout' );
$sidebar = $data->get( 'sidebar' );
$class = ( $data->get( 'layout' ) != 'full' ) ? 'col-xs-12 col-sm-12 col-md-12 col-lg-8' : 'col-xs-12 col-sm-12 col-md-12';
?>

<?php if ( !$data->get( 'enable_banner' ) ) : ?>

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




<section class="sidebar-page-container p_relative sec-pad">
	<div class="auto-container">
		<div class="row clearfix">
		
        	<?php
            if ( $data->get( 'layout' ) == 'left' ) {
                do_action( 'immigro_sidebar', $data );
            }
            ?>
            <div class="content-side col-12">
                <div class="wp-style blog-details-content blog-details-content blog-standard-content p_relative d_block">
                    
					<?php while ( have_posts() ): the_post(); ?>
                        <?php the_content(); ?>
                    <?php endwhile; ?>
                    
                    <div class="clearfix"></div>
                    <?php
                    $defaults = array(
                        'before' => '<div class="paginate_links">' . esc_html__( 'Pages:', 'immigro' ),
                        'after'  => '</div>',
    
                    );
                    wp_link_pages( $defaults );
                    ?>
                    <?php comments_template() ?>
                </div>
            </div>
            <?php
            if ( $layout == 'right' ) {
                $data->set('sidebar', 'default-sidebar');
                do_action( 'immigro_sidebar', $data );
            }
            ?>
        
        </div>
	</div>
</section><!-- blog section with pagination -->
<?php get_footer(); ?>