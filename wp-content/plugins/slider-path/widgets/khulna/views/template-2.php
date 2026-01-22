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

?>

<!-- This is the Main Area Astart=================== --> 
<div id="sliderpath_id-<?php echo esc_attr( $unique_id ); ?>" class="slider_path  slider_path_style-<?php echo esc_attr( $style ); ?>">       

<!-- Slider Mask=================== -->
<div class="defult_slider_1">


				<!-- Slider for Plugin plugin_slides =================== --> 	
				<div class="sliderpath_banner-carousel-one owl-theme owl-carousel owl_dots_one " >
						<!-- Slider For Each Area =================== --> 	
							<?php foreach ( $repeat as $item ) : ?>   
									<div class="slider_path_elemntor">
										<?php 
											$post_id = slider_path_elemntor_content($item['slider_path_elemntor_template']);
											echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($post_id); ?>
									</div>
									<!-- Slider Defult area =================== -->	
					<!-- Slider For Each Area =================== --> 
   					<?php endforeach?>
				<!-- End  Plugin plugin_slides =================== --> 		
         		</div>    
	
<!-- End Slider Mask=================== -->
</div>   
	
<!-- End of Main Area =================== -->	
</div>