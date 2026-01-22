<?php
/**
 * Templates Library
 */

?>

<div class="sliderpath-library">
    <div class="sliderpath-library-header">
        <div class="template-search">
            <input type="text" placeholder="<?php esc_html_e( 'Start typing...', 'slider-path' ); ?>">
        </div>
    </div>

    <div class="sliderpath-templates">

		<?php foreach ( sliderpath()->get_plugin_data( 'templates' ) as $template_id => $template_group ) : ?>

            <a href="<?php echo esc_url( admin_url( 'admin.php?page=sliderpath-library&template-group=' . $template_id ) ) ?>"
               data-filter-tags="<?php echo esc_attr( implode( '-', sliderpath()->get_settings_atts( 'tags', array(), $template_group ) ) ); ?>"
               class="sliderpath-template">
                <img src="<?php echo esc_url( sliderpath()->get_template_group_thumb( $template_group ) ); ?>"
                     alt="<?php echo esc_html( sliderpath()->get_settings_atts( 'title', '', $template_group ) ) ?>">
                <div class="template-details">
                    <h3><?php echo esc_html( sliderpath()->get_settings_atts( 'title', '', $template_group ) ) ?></h3>
                    <div class="template-info">
                        <span><?php esc_html_e( sprintf( '%s Pages', count( sliderpath()->get_settings_atts( 'pages', '', $template_group ) ) ), 'slider-path' ); ?></span>
						<?php printf( '<div class="template-tags">%s</div>', implode( '', array_map( function ( $tag ) {
							return sprintf( '<span>%s</span>', $tag );
						}, sliderpath()->get_settings_atts( 'tags', array(), $template_group ) ) ) ); ?>
                    </div>
                </div>
            </a>

		<?php endforeach; ?>

    </div>
</div>
