<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element
 *
 */

$settings = json_decode(get_option('wp_fancy_settings'), true);  
 
?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/resources/js/html5.js"></script>
	<![endif]-->
	<script>(function(){document.documentElement.className='js'})();</script>
	<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700|Slabo+27px' rel='stylesheet' type='text/css'>
	<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-22248575-1']);
  _gaq.push(['_setDomainName', 'andreknieriem.de']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
	<?php wp_head();
		$favicon = (isset($settings['favicon']) && !empty($settings['favicon'])) ? $settings['favicon'] : esc_url( get_template_directory_uri() ).'/resources/img/favicon.ico';
		$filetype = wp_check_filetype($favicon);
		echo '<link rel="icon" type="'.$filetype['type'].'" href="'.$favicon.'" />';
	?>
	<style>
		@media screen and (max-width: 991px) {
			html {
				margin-top: 0 !important;
			}
			* html body { margin-top: 0 !important; }
		}
	</style>
</head>

<body <?php body_class(); ?>>
<header id="header">
	<a href="<?php echo get_home_url(); ?>" class="logo">
		<?php
		if(!empty($settings['logo'])) {
			echo '<img src="'.$settings['logo'].'" />';
		} else {
			echo '<img class="logoimg" src="'.get_template_directory_uri().'/resources/img/logo.png">';
		}
		?>
		
	</a>
<button type="button" class="navbar-toggle">
    <span class="sr-only">Toggle navigation</span>
    <span class="icon-bar topicon"></span>
    <span class="icon-bar"></span>
</button>
<button type="button" class="search-toggle">
	<i class="fa fa-search"></i>
</button>
</header>
<div class="topmenu">
	<?php 
		wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' , 'container' => 'nav') ); 
	?>
</div>
<div class="mobileSearch">
	<form action="" class="searchform" method="get"><input type="text" name="s" id="s" placeholder="Search"></form>
</div>
<div id="wholecontent">