<?php
$options = immigro_WSH()->option();
$allowed_html = wp_kses_allowed_html( 'post' );

//Logo Settings
$image_logo = $options->get( 'image_normal_logo' );
$logo_dimension = $options->get( 'normal_logo_dimension' );

$image_logo2 = $options->get( 'image_normal_logo2' );
$logo_dimension2 = $options->get( 'normal_logo_dimension2' );

$image_logo3 = $options->get( 'image_normal_logo3' );
$logo_dimension3 = $options->get( 'normal_logo_dimension3' );

$logo_type = '';
$logo_text = '';
$logo_typography = '';
/**
 * Footer Main File.
 *
 * @package IMMIGRO
 * @author  tonatheme
 * @version 1.0
 */
global $wp_query;
$page_id = ( $wp_query->is_posts_page ) ? $wp_query->queried_object->ID : get_the_ID();
$options = immigro_WSH()->option();
?>



<?php immigro_template_load( 'templates/footer/footer.php', compact( 'page_id' ) );?>



<?php if(!$options->get( 'to_top' ) ):?>
<div class="scroll-to-top">
	<div>
		<div class="scroll-top-inner">
			<div class="scroll-bar">
				<div class="bar-inner"></div>
			</div>
			<div class="scroll-bar-text g_color">Go To Top</div>
		</div>
	</div>
</div>
<?php endif; ?>

<!--Search Popup-->
<div id="search-popup" class="search-popup">
	<div class="popup-inner">
		<div class="upper-box clearfix">
			<figure class="logo-box pull-left">
				<?php echo immigro_logo( $logo_type, $image_logo2, $logo_dimension2, $logo_text, $logo_typography ); ?>
			</figure>
			<div class="close-search pull-right"><span class="fa fa-times"></span></div>
		</div>
		<div class="overlay-layer"></div>
		<div class="auto-container">
			<div class="search-form">
				<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<div class="form-group">
						<fieldset>
							<input type="search" class="form-control" name="s" value="" placeholder="<?php echo wp_kses( $options->get( 'search_text_v1'), $allowed_html ); ?>" required >
							<button type="submit"><i class="fa fa-search"></i></button>
						</fieldset>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- sidebar cart item -->
<div class="xs-sidebar-group info-group info-sidebar">
	<div class="xs-overlay xs-bg-black"></div>
	<div class="xs-sidebar-widget">
		<div class="sidebar-widget-container">
			<div class="widget-heading">
				<a href="#" class="close-side-widget"><img src="<?php echo esc_url(get_template_directory_uri().'/assets/images/close.png');?>" alt="<?php echo esc_attr($settings['alt_text']);?>"/></a>
			</div>
			<div class="sidebar-textwidget">
				<div class="sidebar-info-contents">
					<div class="content-inner">
						<div class="logo">
							<?php echo immigro_logo( $logo_type, $image_logo2, $logo_dimension2, $logo_text, $logo_typography ); ?>
						</div>
						<div class="content-box">
							<?php if( $options->get( 'about_title_v1')):?>	
							<h4><?php echo wp_kses( $options->get( 'about_title_v1'), $allowed_html ); ?></h4>
							<?php endif; ?>
							<?php if( $options->get( 'about_text_v1')):?>	
							<p><?php echo wp_kses( $options->get( 'about_text_v1'), $allowed_html ); ?></p>
							<?php endif; ?>
						</div>
						<div class="form-inner">
							<?php if( $options->get( 'about_text_v1')):?>
							<h4><?php echo wp_kses( $options->get( 'form_title_v1'), $allowed_html ); ?></h4>
							<?php endif; ?>
							<form title="Sidebar Form">
								<div class="form-group">
									<input type="text" name="name" placeholder="Name" required="">
								</div>
								<div class="form-group">
									<input type="email" name="email" placeholder="Email" required="">
								</div>
								<div class="form-group">
									<textarea name="message" placeholder="Message..."></textarea>
								</div>
								<div class="form-group message-btn">
									<button class="theme-btn-one">Submit Now</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- sidebar widget item end -->


</main>
<?php wp_footer(); ?>
</body>
</html>
