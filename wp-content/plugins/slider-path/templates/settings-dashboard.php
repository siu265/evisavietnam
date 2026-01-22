<?php
/**
 * Dashboard Settings
 */

$posted_data = wp_unslash( $_POST );
$nonce       = sliderpath()->get_settings_atts( 'sliderpath_nonce', '', $posted_data );

if ( wp_verify_nonce( $nonce, 'sliderpath_nonce_action' ) ) {

	$elements_active = sliderpath()->get_settings_atts( 'sliderpath_elements_active', array(), $posted_data );

	if ( is_array( $elements_active ) ) {
		update_option( 'sliderpath_elements_active', $elements_active );
	}
}

?>

<form action="<?php menu_page_url( 'sliderpath-settings' ); ?>" class="page-wrapper" method="post" enctype="multipart/form-data">


    <div class="element-page">
        <div class="wrapper-box">

            <div class="sidebar">
                <div class="logo">
					<?php printf( '<img src="%sassets/images/logo.png" alt="%s">', SLIDERPATH_PLUGIN_URL, esc_html( 'Slider Path Addons' ) ); ?>
                    <h4>Slider Path </h4>
                </div>
                <ul class="nav nav-tabs tab-btn-style-one" id="myTab" role="tablist" >
                    <li class="nav-item">
                        <a class="nav-link active" id="tab-one-area" data-bs-toggle="tab" href="#tab-one" role="tab" aria-controls="tab-one" aria-selected="true">
                            <h4><span class="dashicons dashicons-screenoptions"></span> <?php esc_html_e( 'Dashboard', 'slider-path' ); ?></h4>
                        </a>
                    </li>
                </ul>
                <div class="link-btn">
                    <a href="<?php echo esc_url(SLIDERPATH_PLUGIN_TUTORIAL) ; ?>"><span class="dashicons dashicons-welcome-learn-more"></span> <?php esc_html_e( 'Go Pro', 'slider-path' ); ?></a>
                </div>
            </div>
            
            <div class="content-box">
                <!-- Tab panes -->
                <div class="tab-content wow fadeInUp" data-wow-delay="200ms" data-wow-duration="1200ms">

                    
                    <div class="tab-pane fadeInUp animated active" id="tab-one" role="tabpanel" aria-labelledby="tab-one">
                        <div class="row">
                        <h2><?php esc_html_e( 'Welcome to Slider Path', 'slider-path' ); ?></h2>
                        <div class="text"><?php esc_html_e( 'Make your Slider form our libarary followed by very easy Steps. ', 'slider-path' ); ?></div>

                            
                            <div class="col-lg-6 feature-block">
                                <div class="inner-box">
                                    <div class="icon"><span class="eicon-form-horizontal"></span></div>
                                    <h4><?php esc_html_e( 'Easy Documentation', 'slider-path' ); ?></h4>
                                    <div class="text"><?php esc_html_e( 'Check the Documetation Its supper Easy. Just follow some Basic Steps. Plese check the Video also', 'slider-path' ); ?></div>
                                    <div class="link-btn"><a href="<?php echo esc_url(SLIDERPATH_PLUGIN_DOC) ; ?> " class="btn-style-one"><span> <?php esc_html_e( 'Documetatin', 'slider-path' ); ?></span></a></div>
                                </div>
                            </div>

                            <div class="col-lg-6 feature-block">
                                <div class="inner-box">
                                    <div class="icon"><span class="eicon-media-carousel"></span></div>
                                    <h4><?php esc_html_e( 'Demo Sldiers', 'slider-path' ); ?></h4>
                                    <div class="text"><?php esc_html_e( 'You will have Numbers of Diffrent Sliders with all the Catagories you need. ', 'slider-path' ); ?></div>
                                    <div class="link-btn"><a href="<?php echo esc_url(SLIDERPATH_PLUGIN_DEMO) ; ?>" class="btn-style-one"><span> <?php esc_html_e( 'Demo View', 'slider-path' ); ?></span></a></div>
                                </div>
                            </div>
                            <div class="col-md-12 feature-block-two">
                                <div class="inner-box">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="text-block">
                                                <h4><?php esc_html_e( 'Support & Request', 'slider-path' ); ?></h4>
                                                <div class="text"><?php esc_html_e( 'Contact Us for any Help or Request a Features on The Slider. ', 'slider-path' ); ?></div>
                                                <div class="link-btn"><a href="<?php echo esc_url(SLIDERPATH_PLUGIN_CONTACT) ; ?>" class="btn-style-one"><span> <?php esc_html_e( 'Get Support', 'slider-path' ); ?></span></a></div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="image"><?php printf( '<img src="%sassets/images/feature.png" alt="%s">', SLIDERPATH_PLUGIN_URL, esc_html( 'Get Support' ) ); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

	<?php wp_nonce_field( 'sliderpath_nonce_action', 'sliderpath_nonce' ); ?>
</form>
