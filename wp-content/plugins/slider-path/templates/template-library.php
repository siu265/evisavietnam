<?php
/**
 * Pages of a single Template
 */

global $template_group;

?>

<div class="sliderpath-library">
    <div class="sliderpath-library-header">
        <a class="template-back" href="<?php echo esc_url( admin_url( 'admin.php?page=sliderpath-library' ) ); ?>"><?php esc_html_e( 'All Templates', 'slider-path' ); ?></a>
        <span class="template-name"><?php echo esc_html( sliderpath()->get_settings_atts( 'title', '', $template_group ) ); ?></span>
    </div>

    <div class="sliderpath-templates">

		<?php foreach ( sliderpath()->get_settings_atts( 'pages', array(), $template_group ) as $template_id => $template ) :
			$is_pro = (bool) sliderpath()->get_settings_atts( 'pro', false, $template );
			$is_pro_class = $is_pro ? 'template-pro' : '';
			?>
            
            <div class="sliderpath-template sliderpath-template-page <?php echo esc_attr( $is_pro_class ); ?>">
                <img src="<?php echo esc_url( sliderpath()->get_settings_atts( 'thumb', '', $template ) ); ?>"
                     alt="<?php echo esc_html( sliderpath()->get_settings_atts( 'title', '', $template ) ) ?>">
                <div class="template-details">
                    <h3><?php echo esc_html( sliderpath()->get_settings_atts( 'title', '', $template ) ) ?></h3>
                    <div class="template-info">
                        <a target="_blank" href="<?php echo esc_url( sliderpath()->get_settings_atts( 'demo', '', $template ) ); ?>"><?php esc_html_e( 'Preview', 'slider-path' ); ?></a>
                        <div class="sliderpath-import"
                             data-template-group="<?php echo esc_attr( sliderpath()->get_settings_atts( 'template_group', '', $template_group ) ); ?>"
                             data-template="<?php echo esc_attr( $template_id ); ?>">
							<?php if ( $is_pro ) {
								esc_html_e( 'Premium', 'slider-path' );
							} else {
								esc_html_e( 'Import', 'slider-path' );
							} ?>
                        </div>
                    </div>
                </div>
            </div>

		<?php endforeach; ?>

    </div>

    <div class="sliderpath-import-window">
        <div class="sliderpath-import"></div>
    </div>
</div>

