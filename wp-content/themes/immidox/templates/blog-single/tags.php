<?php
/**
 * Single Post tags File.
 *
 * @package IMMIGRO
 * @author  Tona Theme
 * @version 1.0
 */

?>
<?php $tags = wp_get_post_tags( get_the_ID() );
$get_tags   = get_the_tag_list( get_the_ID() );
?>
<?php if ( has_category() || $get_tags ) : ?>
	<div class="tags-area">
		<?php if ( $get_tags ) : ?>


			<span><i class="fa fa-tags"></i><?php esc_html_e( 'tags:', 'immigro' ); ?></span>

			<ul>

				<?php foreach ( $tags as $tag ) : ?>

					<li>
						<a href="<?php echo esc_url( get_tag_link( immigro_set( $tag, 'term_id' ) ) ); ?>"><?php echo esc_html( immigro_set( $tag, 'name' ) ); ?></a>
					</li>

				<?php endforeach; ?>

			</ul>
		<?php endif; ?>
		<?php if ( has_category() ) : ?>
			<div class="cat-area">

				<span><i class="fa fa-list-alt"></i><?php esc_html_e( 'categories:', 'immigro' ); ?></span>

				<ul>

					<li><?php wp_kses( the_category( ' , ' ), true ); ?></li>

				</ul>

			</div>
		<?php endif; ?>

	</div>
<?php endif; ?>