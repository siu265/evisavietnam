
<?php if($options->get('single_author_box' ) ): ?>	
<div class="author-box p_relative d_block pt_45 pr_30 pb_40 pl_170 mb_60">
	<figure class="author-thumb p_absolute l_40 t_40 w_100 h_100 b_radius_50">
		<?php echo get_avatar( get_the_author_meta( 'ID' ), 160 ); ?>
	</figure>
	<h4 class="d_block fs_20 lh_30 mb_11"><?php echo esc_html( get_the_author_meta( 'display_name' ) ); ?></h4>
	<p class="font_family_poppins"><?php echo esc_html( get_the_author_meta( 'description' ) ); ?></p>
</div>
<?php endif; ?>
