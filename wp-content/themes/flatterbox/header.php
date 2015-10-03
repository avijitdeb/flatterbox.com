<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<title><?php wp_title( ' | ', true, 'right' ); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="p:domain_verify" content="4b8e3db349c6277c94992f7aebc95f9b" />
	<link href='//fonts.googleapis.com/css?family=PT+Sans:400,500,700,400italic' rel='stylesheet' type='text/css'>
	<link href='//fonts.googleapis.com/css?family=Droid+Serif:400,400italic' rel='stylesheet' type='text/css'>
	<link href='//fonts.googleapis.com/css?family=Sanchez' rel='stylesheet' type='text/css'>
	<!--[if IE]><script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/ie.js"></script><![endif]-->
	<!--[if lt IE 9]><link href="<?php bloginfo('stylesheet_directory'); ?>/ie.css" rel="stylesheet" media="all"><![endif]-->
	<link rel="icon" type="image/png" href="<?php bloginfo('stylesheet_directory'); ?>/images/flatterbox_favicon.png">
	<?php wp_head(); ?>
	<?php // For Bob Clements @ Extract Marketing ?>
	<meta name="google-site-verification" content="F3ozfGAl6mcPWfR-9KHMAYrqNRTd38tNqrDACGBbo5g" />

	<script type="text/javascript"> jQuery.noConflict(); </script>
	<link media="all" rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.tagsinput.css">
	<?php if (is_front_page()) : ?><script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/slick.min.js"></script><?php endif; ?>
	<link media="all" rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/all.css">
	<?php if (is_front_page()) : ?><link media="all" rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/js/slick.css"><?php endif; ?>
	
	<?php if (is_page_template('page-step3-options.php')) : ?>
	<script type="text/javascript">
	jQuery.noConflict();
	jQuery( document ).ready(function() {
		jQuery(".datepicker").datepicker({ minDate: 0 });
	});
	</script>	
	<?php endif; ?>
	
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.profanityfilter.js"></script>
	<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/fancybox/jquery.fancybox-1.3.4.js"></script>
	<link media="all" rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/js/fancybox/jquery.fancybox-1.3.4.css">
	
	<script>
		jQuery.noConflict();
		jQuery(document).ready(function(){
			jQuery(".email-preview").fancybox();
		});
	</script>		
	
</head>
<body <?php body_class(); ?>>
	<input type="hidden" name="process_check" id="process_check" value="0" />
	<div id="wrapper">
		<?php $siteMessage = get_field('site_wide_message_headline', 'option');
		$siteNote = get_field('site_wide_message_small_note', 'option');
		if ($siteMessage != "") : ?>
		<div id="message">
			<div class="holder">
				<h2><?php echo $siteMessage; ?></h2>
				<?php if ($siteNote) : ?>
				<div class="smallnote">
					<h5><?php echo $siteNote; ?></h5>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<?php endif; ?>
		<header id="header" class="open-close">
			<div class="holder">
				<div class="logo"><a href="<?php bloginfo('url'); ?>"><img src="<?php bloginfo('stylesheet_directory'); ?>/images/flatterbox_TM.png" height="83" width="390" alt="Flatterbox | Little gift, huge impact."></a></div>
				<a href="#" class="menu-button opener">Button</a>
				<div class="mobile-menu slide">
					<div class="utilitynav-mobile">
						<?php wp_nav_menu( array( 'theme_location' => 'utility-menu' ) ); ?>
					</div>
					<div class="nav-mobile">
						<?php wp_nav_menu( array( 'theme_location' => 'main-menu' ) ); ?>
					</div>
					<a class="btn orangebtn mobilebtn" href="<?php bloginfo('url'); ?>/choose-card/">Create Your Flatterbox</a>
				</div>
				<div class="utilitynav">
					<?php wp_nav_menu( array( 'theme_location' => 'utility-menu' ) ); ?>
				</div>
				<nav id="nav">
					<?php wp_nav_menu( array( 'theme_location' => 'main-menu' ) ); ?>
				</nav>
			</div>
		</header>
		<div class="lightbox login">
			<?php include('includes/lightbox-login.php'); ?>
		</div>
		<div class="lightbox-step2 login-step2">
			<?php include('includes/lightbox-login-step2.php'); ?>
		</div>		