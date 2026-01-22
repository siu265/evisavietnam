<?php 

$options = immigro_WSH()->option();
$allowed_html = wp_kses_allowed_html(); ?>



<?php if(!$options->get('single_post_tag' ) ): ?>
<div class="post-tags">
	<ul class="tags-list clearfix">
		<h5 class="tag_text"><?php esc_html_e( 'Tags:', 'immigro' ); ?></h5>
		<li><?php the_tags(' ', '<span class="commax">,</span>  ', ''); ?></li>
	</ul>
</div>
<?php endif; ?>