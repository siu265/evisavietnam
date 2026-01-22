<?php
$options = immigro_WSH()->option();
$allowed_html = wp_kses_allowed_html();

/**
 * Blog Content Template
 *
 * @package    WordPress
 * @subpackage IMMIGRO
 * @author     Tona Theme
 * @version    1.0
 */

if ( class_exists( 'Immigro_Resizer' ) ) {
	$img_obj = new Immigro_Resizer();
} else {
	$img_obj = array();
}
$allowed_tags = wp_kses_allowed_html('post');
global $post;
?>

<div class="news-block-one mb_40">
	<div class="inner-box">
	
		<?php	if ( has_post_thumbnail() ) { ?>
			<figure class="image-box">
				<a href="<?php echo esc_url(get_permalink(get_the_id()));?>">
					<?php the_post_thumbnail(); ?>
				</a>
			</figure>
		<?php } ?>	
	
		<div class="lower-content">
			
			<?php if( ! empty( $post->post_title ) ) : ?>
			<h3><a href="<?php echo esc_url( the_permalink( get_the_id() ) );?>"><?php the_title(); ?></a></h3>
			<?php endif; ?>
			
			<ul class="post-info clearfix">
			
				<?php if(!$options->get('blog_post_author' ) ): ?>
				<li class="admin">
					<figure class="admin-thumb"><?php echo get_avatar(get_the_author_meta('ID'), 90); ?></figure>
					<a href="<?php echo esc_url(get_author_posts_url( get_the_author_meta('ID') )); ?>"><?php the_author(); ?></a>
				</li>
				<?php endif;?>
				<?php if(!$options->get('blog_post_comment' ) ): ?>
				<li><?php comments_number(); ?></li>
				<?php endif;?>
			</ul>
			
			
			<?php the_excerpt(); ?>
			
			
			<?php if(!$options->get('blog_post_readmore' ) ): ?>						
			<?php if($options->get('blog_post_readmoretext' ) ): ?>
			
			<div class="btn-box">
				<a href="<?php echo esc_url(get_permalink(get_the_id()));?>" class="btn-4"><?php echo wp_kses( $options->get( 'blog_post_readmoretext'), $allowed_html ); ?><span></span></a>
			</div>
			<?php else: ?>		
			<div class="btn-box">
				<a href="<?php echo esc_url(get_permalink(get_the_id()));?>" class="btn-4"><?php esc_html_e('Read More', 'immigro');?><span></span></a>
			</div>
			<?php endif; ?>	
			<?php endif;?>
			
			
		</div>
	</div>
</div>