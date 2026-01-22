<?php
/**
* Single Post Content Template
*
* @package    WordPress
* @subpackage IMMIGRO
* @author     Tona Theme
* @version    1.0
*/
?>
<?php global $wp_query;

$options = immigro_WSH()->option();
$allowed_html = wp_kses_allowed_html();

$page_id = ( $wp_query->is_posts_page ) ? $wp_query->queried_object->ID : get_the_ID();

$gallery = get_post_meta( $page_id, 'immigro_gallery_images', true );

$video = get_post_meta( $page_id, 'immigro_video_url', true );


$audio_type = get_post_meta( $page_id, 'immigro_audio_type', true );

?>

<div class="blog-details-content">
	<div class="news-block-one">
		<div class="inner-box">
		
			<?php	if ( has_post_thumbnail() ) { ?>
				<figure class="image-box">
					<?php the_post_thumbnail(); ?>
				</figure>
			<?php } ?>	
			
			<div class="lower-content">
				
				
				

				
				
				
				<ul class="post-info clearfix">
					<?php if(!$options->get('single_post_author' ) ): ?>
					<li class="admin">
						<figure class="admin-thumb"><?php echo get_avatar(get_the_author_meta('ID'), 90); ?></figure>
						<a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta('ID') )); ?>"><?php the_author(); ?></a>
					</li>
					<?php endif;?>
					<?php if(!$options->get('single_post_comments' ) ): ?>
					<li><?php comments_number(); ?></li>
					<?php endif;?>
				</ul>
				<div class="text">
                <?php the_content(); ?>
                <div class="clearfix"></div>
                <?php wp_link_pages(array('before'=>'<div class="paginate_links">'.esc_html__('Pages: ', 'immigro'), 'after' => '</div>', 'link_before'=>'', 'link_after'=>'')); ?>
            </div>
			</div>
		</div>
	</div>
	
	
	<?php immigro_template_load( 'templates/blog-single/social_share.php', compact( 'options', 'data' ) ); ?>
    
    <?php comments_template(); ?> 
	
</div>