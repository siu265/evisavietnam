<?php
/**
 * Footer Template  File
 *
 * @package IMMIGRO
 * @author  Tona Theme
 * @version 1.0
 */

$options = immigro_WSH()->option();
$footer_shape_3    = $options->get( 'footer_shape_three' );
$footer_shape_3    = immigro_set( $footer_shape_3, 'url', IMMIGRO_URI . 'assets/images/shape/footer-shape-1.png' );

$footer_shape_4    = $options->get( 'footer_shape_four' );
$footer_shape_4    = immigro_set( $footer_shape_4, 'url', IMMIGRO_URI . 'assets/images/shape/footer-shape-2.png' );
$allowed_html = wp_kses_allowed_html( 'post' );
?>


    <!--Start subscribe Area-->
    <section class="subscribe-area">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                   <?php
						$icons = $options->get( 'footer_v2_social_share' );
						if ( ! empty( $icons ) ) :
					?>
                    <div class="left pull-left">
                        <div class="subscribe-social-links">
                            <ul>
                            <?php
								foreach ( $icons as $h_icon ) :
								$header_social_icons = json_decode( urldecode( immigro_set( $h_icon, 'data' ) ) );
								if ( immigro_set( $header_social_icons, 'enable' ) == '' ) {
									continue;
								}
								$icon_class = explode( '-', immigro_set( $header_social_icons, 'icon' ) );
								?>
								<li><a href="<?php echo esc_url(immigro_set( $header_social_icons, 'url' )); ?>" style="background-color:<?php echo esc_attr(immigro_set( $header_social_icons, 'background' )); ?>; color: <?php echo esc_attr(immigro_set( $header_social_icons, 'color' )); ?>"><i class="fa <?php echo esc_attr( immigro_set( $header_social_icons, 'icon' ) ); ?>"></i></a></li>
							<?php endforeach; ?>
                            </ul>
                        </div>    
                    </div>
                    <?php endif; ?>
                    
                    <div class="right pull-right">
                        <div class="subscribe-content-box clearfix">
                            <?php if( $options->get('newletter_form_title_v2') ):?>
                            <div class="subscribe-title">
                                <h3><?php echo wp_kses($options->get('newletter_form_title_v2'), $allowed_html);?></h3>
                            </div>
                            <?php endif; ?>
                            <?php if( $options->get('newletter_form_id_v2') ):?>
                            <div class="subscribe-box">
                                <div class="subscribe-form black_ver">
                                    <?php echo do_shortcode($options->get('newletter_form_id_v2'));?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </section>

        
    <!--Start footer area-->  
    <footer dir="rtl" class="footer-area rtl">
        <?php if ( is_active_sidebar( 'footer-sidebar-2' ) ) { ?>
        <div class="layer-outer">
            <div class="shape1 zoom-fade">
                <img src="<?php echo esc_url($footer_shape_3);?>" alt="<?php the_title_attribute();?>">
            </div>
            <div class="shape2" data-aos="fade-up" data-aos-easing="linear" data-aos-duration="2000" 
            style="background-image: url(<?php echo esc_url($footer_shape_4);?>)">     
            </div>    
        </div>
        <div class="footer">
            <div class="container">
                <div class="row text-right">
                    <?php dynamic_sidebar( 'footer-sidebar-2' ); ?>
                </div>
            </div>
        </div>
        <?php } ?>  
        <div class="footer-bottom">
            <div class="container">
                <div class="outer-box">
                    <div class="copyright-text text-center">
                        <p><?php echo wp_kses( $options->get( 'copyright_text2', '&copy; Copyright 2021 by <a href="#">immigro</a>' ), $allowed_html ); ?></p>
                    </div>
                </div>    
            </div>    
        </div>
        
    </footer>   
    <!--End footer area-->
