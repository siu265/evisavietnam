<?php
/**
 * Widget Render: slider
 *
 * @package widgets/slider/views/template-2.php
 * @copyright rashid87
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Utils;



$unique_id = uniqid();
$style         = sliderpath()->get_settings_atts( 'style' );
$repeat    = sliderpath()->get_settings_atts( 'repeat' );


?>


 <?php
      echo '
     <script>
 jQuery(document).ready(function($)
 {

//put the js code under this line 

  if ($(".banner-carousel-one").length) {
        $(".banner-carousel-one").owlCarousel({
            animateOut: "fadeOut",
            animateIn: "fadeIn",
            loop:true,
            margin:0,
            dots: true,
            nav:true,
            singleItem:true,
            smartSpeed: 500,
            autoplay: true,
            autoplayTimeout:6000,
           
            responsive:{
                0:{
                    items:1
                },
                600:{
                    items:1
                },
                1024:{
                    items:1
                }
            }
        });         
    }

//put the code above the line 

  });
</script>';

// CSS Class for Style controll
//medical_dhaka
// slider_path
//medical_dhaka_slider
//slider_path_iamge_layer
//slider_path_iamge_layer
//slider_path_subtitle
//slider_path_title
//slider_path_text
//slider_path_button_box
//slider_path_button
//slider_path_button_box_2
//slider_path_button_2
//slider_path_button_container
//slider_path_right_image
?>

 
<div id="sliderpath_id-<?php echo esc_attr( $unique_id ); ?>" class="slider_path  slider_path_style-<?php echo esc_attr( $style ); ?>">
   
    <!-- Slider One -->
        <section class="defult_slider_1">

            <div class="banner-carousel-one owl-theme owl-carousel owl_dots_none ">
        
                <?php foreach ( $repeat as $item ) :

                $slider_path_image = sliderpath()->get_settings_atts( 'url', '', sliderpath()->get_settings_atts( 'slider_path_image', '', $item ) );
                $slider_path_image2 = sliderpath()->get_settings_atts( 'url', '', sliderpath()->get_settings_atts( 'slider_path_image2', '', $item ) );
                $slider_path_image3 = sliderpath()->get_settings_atts( 'url', '', sliderpath()->get_settings_atts( 'slider_path_image3', '', $item ) );
                ?>      
                <!-- Slide -->
  

                <div class="slider_path_slide" style="background-image:url(<?php echo esc_url( $slider_path_image ); ?>)">
                    <div class="slider_path_container slider_path_container_flex" >
                 
                        <div class="slider_path_left">  
                                <?php if ( ! empty($item['slider_path_subtitle']) ) : ?>
                                <h5 class="slider_path_subtitle">$slider_path_background_position = sliderpath()->get_settings_atts('slider_path_background_position'); </h5>
                                <?php endif; ?>


                                <?php if ( ! empty($item['slider_path_title']) ) : ?>
                                <h2 class="slider_path_title"><?php echo esc_html($item['slider_path_title']);?></h2>
                                <?php endif; ?>


                                <?php if ( ! empty($item['slider_path_text']) ) : ?>
                                <p class="slider_path_text"><?php echo esc_html($item['slider_path_text']);?></p>
                                <?php endif; ?>

                            <div class=" slider_path_button_container">
                                 <?php if ( ! empty($item['slider_path_button']) ) : ?>
                                <div class=" slider_path_button_box">
                                    <a href=" <?php echo esc_url($item['slider_path_link']['url']);?>" class="slider_path_button"> <?php echo esc_html($item['slider_path_button']);?></a>
                                </div>
                                <?php endif; ?>

                                <?php if ( ! empty($item['slider_path_button_2']) ) : ?>
                                <div class=" slider_path_button_box_2">
                                    <a href=" <?php echo esc_url($item['slider_path_link_2']['url']);?>" class="slider_path_button_2"> <?php echo esc_html($item['slider_path_button_2']);?></a>
                                </div>
                                <?php endif; ?>
                            </div> 
                        </div> 

                            <div class="slider_path_right_image">
                                <img src="<?php echo esc_url( $slider_path_image2 ); ?>" alt="">
                            </div>
                    </div>
                </div>              
                <!-- Slide -->
            <?php endforeach; ?>

             
            </div>
        </section>
        <!-- End Slider One -->
</div>