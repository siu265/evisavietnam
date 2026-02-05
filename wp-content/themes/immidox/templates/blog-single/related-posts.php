<?php
/**
 * Related Posts – Hiển thị bài viết liên quan dạng slide (4 cột, tối đa 10 item)
 * Sắp xếp theo ngày đăng (mới nhất trước)
 *
 * @package    WordPress
 * @subpackage IMMIGRO
 */
global $post;

$current_id = get_the_ID();
$limit      = 10;

$args = array(
	'post_type'      => 'post',
	'posts_per_page' => $limit,
	'post__not_in'   => array( $current_id ),
	'orderby'        => 'date',
	'order'          => 'DESC',
	'post_status'    => 'publish',
);

$categories = get_the_category( $current_id );
$tags       = get_the_tags( $current_id );

if ( ! empty( $categories ) ) {
	$cat_ids = array_map( function( $c ) { return $c->term_id; }, $categories );
	$args['category__in'] = $cat_ids;
	$related = new WP_Query( $args );
} else {
	$related = new WP_Query( $args );
}

if ( $related->post_count < $limit && ! empty( $tags ) ) {
	$tag_ids   = array_map( function( $t ) { return $t->term_id; }, $tags );
	$args      = array(
		'post_type'      => 'post',
		'posts_per_page' => $limit - $related->post_count,
		'post__not_in'   => array_merge( array( $current_id ), wp_list_pluck( $related->posts, 'ID' ) ),
		'tag__in'        => $tag_ids,
		'orderby'        => 'date',
		'order'          => 'DESC',
		'post_status'    => 'publish',
	);
	$tag_query = new WP_Query( $args );
	if ( $tag_query->have_posts() ) {
		$related->posts = array_merge( $related->posts, $tag_query->posts );
		$related->post_count = count( $related->posts );
	}
	wp_reset_postdata();
}

if ( $related->post_count < $limit ) {
	$args = array(
		'post_type'      => 'post',
		'posts_per_page' => $limit - $related->post_count,
		'post__not_in'   => array_merge( array( $current_id ), wp_list_pluck( $related->posts, 'ID' ) ),
		'orderby'        => 'date',
		'order'          => 'DESC',
		'post_status'    => 'publish',
	);
	$recent = new WP_Query( $args );
	if ( $recent->have_posts() ) {
		$related->posts   = array_merge( $related->posts, $recent->posts );
		$related->post_count = count( $related->posts );
	}
	wp_reset_postdata();
}

if ( empty( $related->posts ) ) {
	return;
}

$options = function_exists( 'immigro_WSH' ) ? immigro_WSH()->option() : null;
$allowed_html = wp_kses_allowed_html( 'post' );
?>

<div class="immigro-related-posts">
	<h3 class="related-posts-title"><?php esc_html_e( 'Related Posts', 'immigro' ); ?></h3>
	<div class="related-posts-carousel owl-carousel four-item-carousel">
		<?php foreach ( $related->posts as $rel_post ) : setup_postdata( $rel_post ); ?>
		<div class="related-posts-item">
			<div class="news-block-one">
				<div class="inner-box">
					<?php if ( has_post_thumbnail( $rel_post->ID ) ) : ?>
					<figure class="image-box">
						<a href="<?php echo esc_url( get_permalink( $rel_post->ID ) ); ?>">
							<?php echo get_the_post_thumbnail( $rel_post->ID, 'immigro_370x290' ); ?>
						</a>
					</figure>
					<?php endif; ?>
					<div class="lower-content">
						<h4><a href="<?php echo esc_url( get_permalink( $rel_post->ID ) ); ?>"><?php echo esc_html( get_the_title( $rel_post->ID ) ); ?></a></h4>
						<ul class="post-info clearfix">
							<li><?php echo esc_html( get_the_date( 'd/m/Y', $rel_post->ID ) ); ?><?php if ( $rel_post->post_author ) : ?> | <?php esc_html_e( 'By', 'immigro' ); ?> <?php echo esc_html( get_the_author_meta( 'display_name', $rel_post->post_author ) ); ?><?php endif; ?></li>
						</ul>
						<?php
						$excerpt = has_excerpt( $rel_post->ID ) ? get_the_excerpt( $rel_post->ID ) : wp_trim_words( get_post_field( 'post_content', $rel_post->ID ), 15 );
						if ( $excerpt ) :
						?>
						<p class="related-excerpt"><?php echo wp_kses( $excerpt, $allowed_html ); ?></p>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<?php endforeach; wp_reset_postdata(); ?>
	</div>
</div>
