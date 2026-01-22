<?php
/**
 * Footer Template  File
 *
 * @package IMMIGRO
 * @author  Tona Theme
 * @version 1.0
 */

$options = immigro_WSH()->option();
$allowed_html = wp_kses_allowed_html( 'post' );
?>


 
<div  class="mrfooter bg-color-2">
   <div class="container">
            <div class="row">
              <?php dynamic_sidebar( 'footer-sidebar' ); ?>
            </div>
        </div>
</div>   
    <!--End footer area-->