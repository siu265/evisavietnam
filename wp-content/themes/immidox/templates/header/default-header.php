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
?>

<!-- main header -->
<header class="main-header header-style-one">
	<!-- header-top -->
	<div class="header-top">
		<div class="top-inner">
			<div class="top-left">
				<ul class="info clearfix">
					<?php if( $options->get( 'email_v1' ) ):?>
					<li><i class="icon-1"></i><a href="mailto:<?php echo wp_kses( $options->get( 'email_link_v1'), $allowed_html ); ?>"><?php echo wp_kses( $options->get( 'email_v1'), $allowed_html ); ?></a></li>
					<?php endif; ?>
					<?php if( $options->get( 'time_title_v1' ) ):?>
					<li><i class="icon-2"></i><?php echo wp_kses( $options->get( 'time_title_v1'), $allowed_html ); ?></li>
					<?php endif; ?>
					
				</ul>
			</div>
			<div class="top-right">
				<ul class="info clearfix">
					<?php if( $options->get( 'phone_v1' ) ):?>
					<li><i class="icon-3"></i><a href="tel:<?php echo wp_kses( $options->get( 'phone_link_v1'), $allowed_html ); ?>"><?php echo wp_kses( $options->get( 'phone_v1'), $allowed_html ); ?></a></li>
					<?php endif; ?>
					<?php if( $options->get( 'login_v1' ) ):?>
					<li><i class="icon-4"></i><a href="<?php echo wp_kses( $options->get( 'login_link_v1'), $allowed_html ); ?>"><?php echo wp_kses( $options->get( 'login_v1'), $allowed_html ); ?></a></li>
					<?php endif; ?>
				</ul>
				
				
				<?php if( $options->get( 'header_social_show_v1' ) ):?>
				<?php if( $options->get( 'social_block_v1' ) ):?>
				<ul class="social-links clearfix">
					<?php echo wp_kses( $options->get( 'social_block_v1'), $allowed_html ); ?>
				</ul>
				<?php endif; ?>
				<?php endif; ?>
				
			</div>
		</div>
	</div>
	<!-- header-lower -->
	<div class="header-lower">
		<div class="outer-box">
			<div class="main-box">
				<div class="logo-box">
					<figure class="logo">
						<?php echo immigro_logo( $logo_type, $image_logo, $logo_dimension, $logo_text, $logo_typography ); ?>
					</figure>
				</div>
				<div class="menu-area clearfix">
					<!--Mobile Navigation Toggler-->
					<div class="mobile-nav-toggler">
						<i class="icon-bar"></i>
						<i class="icon-bar"></i>
						<i class="icon-bar"></i>
					</div>
					<nav class="main-menu navbar-expand-md navbar-light">
						<div class="collapse navbar-collapse show clearfix" id="navbarSupportedContent">
							<ul class="navigation clearfix">
							<?php wp_nav_menu( array( 'theme_location' => 'main_menu', 'container_id' => 'navbarSupportedContent',
							'container_class'=>'collapse navbar-collapse sub-menu-bar',
							'menu_class'=>'nav navbar-nav',
							'fallback_cb'=>false, 
							'add_li_class'  => 'nav-item',
							'items_wrap' => '%3$s', 
							'container'=>false,
							'depth'=>'3',
							'walker'=> new Bootstrap_walker()  
							) ); ?>
							</ul>
						</div>
					</nav>
					<?php if( $options->get( 'button_show_v1' ) ):?>
					<?php if( $options->get( 'button_v1' ) ):?>
					<div class="btn-box">
						<a href="<?php echo wp_kses( $options->get( 'button_link_v1'), $allowed_html ); ?>"><?php echo wp_kses( $options->get( 'button_v1'), $allowed_html ); ?> <i class="icon-7"></i>							</a>
					</div>
					<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>
			<?php if( $options->get( 'header_search_show_v1' ) ):?>
			<ul class="nav-right">
				
				<li class="search-box-outer search-toggler">
					<i class="icon-6"></i>
				</li>
				
				<li class="nav-btn nav-toggler navSidebar-button clearfix">
					<i class="icon-5"></i>
				</li>
			</ul>
			<?php endif; ?>
		</div>
	</div>

	<!--sticky Header-->
	<div class="sticky-header">
		<div class="outer-container">
			<div class="outer-box">
				<div class="logo-box">
					<figure class="logo">
						<?php echo immigro_logo( $logo_type, $image_logo2, $logo_dimension2, $logo_text, $logo_typography ); ?>
					</figure>
				</div>
				<div class="menu-area clearfix">
					<nav class="main-menu clearfix">
						<!--Keep This Empty / Menu will come through Javascript-->
					</nav>
				</div>
				<?php if( $options->get( 'header_search_show_v1' ) ):?>
				<ul class="nav-right">
					
					<li class="search-box-outer search-toggler">
						<i class="icon-6"></i>
					</li>
					
					<li class="nav-btn nav-toggler navSidebar-button clearfix">
						<i class="icon-5"></i>
					</li>
				</ul>
				<?php endif; ?>
			</div>
		</div>
	</div>
</header>

<!-- Mobile Menu  -->
<div class="mobile-menu">
	<div class="menu-backdrop"></div>
	<div class="close-btn"><i class="fas fa-times"></i></div>
	
	<nav class="menu-box">
		<div class="nav-logo">
			<?php echo immigro_logo( $logo_type, $image_logo3, $logo_dimension3, $logo_text, $logo_typography ); ?>
		</div>
		<div class="menu-outer"><!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header--></div>
		<div class="contact-info">
			<?php if( $options->get( 'address_title_v1' ) ):?>
			<h4><?php echo wp_kses( $options->get( 'address_title_v1'), $allowed_html ); ?></h4>
			<?php endif; ?>
			<ul>
				<?php if( $options->get( 'address_v1' ) ):?>
				<li><?php echo wp_kses( $options->get( 'address_v1'), $allowed_html ); ?></li>
				<?php endif; ?>
				<?php if( $options->get( 'phone_v1' ) ):?>
				<li><a href="tel:<?php echo wp_kses( $options->get( 'phone_link_v1'), $allowed_html ); ?>"><?php echo wp_kses( $options->get( 'phone_v1'), $allowed_html ); ?></a></li>
				<?php endif; ?>
				
				<?php if( $options->get( 'email_v1' ) ):?>
				<li><a href="mailto:<?php echo wp_kses( $options->get( 'email_link_v1'), $allowed_html ); ?>"><?php echo wp_kses( $options->get( 'email_v1'), $allowed_html ); ?></a></li>
				<?php endif; ?>
				
			</ul>
		</div>

		<?php if( $options->get( 'header_social_show_v1' ) ):?>
		<?php if( $options->get( 'social_block_v1' ) ):?>
		<div class="social-link">
			<ul class="clearfix">
				<?php echo wp_kses( $options->get( 'social_block_v1'), $allowed_html ); ?>
			</ul>
		</div>
		<?php endif; ?>
		<?php endif; ?>  
		
	</nav>
</div><!-- End Mobile Menu -->