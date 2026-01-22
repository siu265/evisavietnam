<?php
/**
 * Widget Render: slider
 *
 * @package widgets/slider/views/template-1.php
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
$slider_style         = sliderpath()->get_settings_atts( 'slider_style' );
$slider_path_elemntor_template_x  = sliderpath()->get_settings_atts( 'slider_path_elemntor_template_x' );

$repeat    = sliderpath()->get_settings_atts( 'repeat' );
$slider_path_background_size = sliderpath()->get_settings_atts('slider_path_background_size'); 
$slider_path_background_position = sliderpath()->get_settings_atts('slider_path_background_position'); 
$slider_path_arrow_squer = sliderpath()->get_settings_atts('slider_path_arrow_squer');
?>


 <?php
      echo '
     <script>
 jQuery(document).ready(function($)
 {

//put the js code under this line 

if ($(".sliderpath_banner-carousel-one").length) {
    $(".sliderpath_banner-carousel-one").owlCarousel({
        loop:true,
        margin:0,
        nav:true,
        active: true,
        smartSpeed: 1000,
        autoplay: 6000,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            800:{
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
?>

<!-- This is the Main Area Astart=================== --> 
<div id="sliderpath_id-<?php echo esc_attr( $unique_id ); ?>" class="slider_path  slider_path_style-<?php echo esc_attr( $style ); ?>">       

<!-- Slider Mask=================== -->
<div class="defult_slider_1">

		<!-- Slider for Teheme theme_slides =================== -->              
			<?php  if ( 'theme_slides' === $slider_style ) : ?>
                <div class="slider_path_elemntor">
                    <?php  $post_id = slider_path_elemntor_content($slider_path_elemntor_template_x);
					echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($post_id);  ?>
                </div>
			 <?php endif;?> 
		<!-- Slider for Teheme =================== -->    
			
		<!-- Slider for Plugin plugin_slides =================== --> 	
			<?php  if ( 'plugin_slides' === $slider_style ) : ?>
				<!-- Slider for Plugin plugin_slides =================== --> 	
				<div class="sliderpath_banner-carousel-one owl-theme owl-carousel owl_dots_one " >

						<!-- Slider For Each Area =================== --> 	
							<?php foreach ( $repeat as $item ) : ?>   

									<!-- Slider Elembnetor Template =================== --> 	
									<?php  if ( 'template' === $item['slider_type'] ) : ?>
									<div class="slider_path_elemntor">
										<?php 
											$post_id = slider_path_elemntor_content($item['slider_path_elemntor_template']);
											echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($post_id); ?>
									</div>
									<?php endif;?>
									<!-- Slider Elembnetor Template =================== --> 

									<!-- Slider Defult area =================== -->
									<?php  if ( 'content' === $item['slider_type'] ) :  ?>

									 <?php $slider_path_image = sliderpath()->get_settings_atts( 'url', '', sliderpath()->get_settings_atts( 'slider_path_image', '', $item ) );
									 ?>
										<div class="slider_path_slide"  style=" 
										background-image:url(<?php echo esc_url( $slider_path_image ); ?>);  
										background-size: <?php echo esc_attr($slider_path_background_size);?>;  
										background-position: <?php echo esc_attr($slider_path_background_position);?>; 
											">
											<div class="slider_path_container slider_path_container_flex" >
												<div class="slider_path_left">  
															<?php if ( ! empty($item['slider_path_subtitle']) ) : ?>
															<h5 class="slider_path_subtitle"><?php echo esc_html($item['slider_path_subtitle']);?></h5>
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
											</div>
										</div>   
                			<!-- Slider Defult area =================== -->
                			<?php endif;?> 
					<!-- Slider For Each Area =================== --> 
   					<?php endforeach?>
				<!-- End  Plugin plugin_slides =================== --> 		
         		</div>    
	 		<?php endif;?> 
		
<!-- End Slider Mask=================== -->
</div>   
	
<!-- End of Main Area =================== -->	
</div>