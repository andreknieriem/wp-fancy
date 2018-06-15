<?php
/**
 * The template for displaying the footer
 */
?>
	<footer id="colophon" class="site-footer" role="contentinfo"></footer>

</div><!-- .site -->

<?php wp_footer(); ?>

<?php 
# Styling & Google Analytics
$settings = json_decode(get_option('wp_fancy_settings'), true);
echo '
<style>

body {
	color: '.$settings['textcolor'].';
}
h1,h2,h3,h4,h5 {
	color: '.$settings['headlinecolor'].';
}
a,
.topmenu li a:hover,
.topmenu li.current-menu-item>a, .topmenu li.current-menu-parent>a {
	color: '.$settings['linkcolor'].';
}

.topmenu li ul,
.topmenu li.menu-header-search .formfield {
	border-top: 3px solid '.$settings['mainheadcolor'].';
}

#mainContent .card .format-status {
	border-left: 3px solid '.$settings['mainheadcolor'].';
}

button,
#gallery .openGallery,
#gallery .navigation .closeGallery,
#mainContent .card .pagination a:hover, 
#mainContent .card .pagination a.current,
.topmenu li.menu-header-search .formfield button.startSearch {
	background: '.$settings['buttoncolor'].';
}

#mainContent .card {
	background-color: '.$settings['maincolor'].';
}
#mainContent .headerbar {
	background-color: '.$settings['mainheadcolor'].';
}
#sidebar .card {
	background-color: '.$settings['sidebarcolor'].';
	color: '.$settings['sbtextcolor'].';
}

#sidebar .headerbar,
#sidebar h1,
#sidebar h2,
#sidebar h3,
#sidebar h4,
#sidebar h5 {
	color: '.$settings['sbheadlinecolor'].';
}



'.$settings['custom_css'].'

</style>';

echo $settings['gacode'];
?>
<div id="mobile-indicator"></div>
<div id="tablet-indicator"></div>

<div id="loader" class="pageload-overlay" data-opening="M 40 -21.875 C 11.356078 -21.875 -11.875 1.3560784 -11.875 30 C -11.875 58.643922 11.356078 81.875 40 81.875 C 68.643922 81.875 91.875 58.643922 91.875 30 C 91.875 1.3560784 68.643922 -21.875 40 -21.875 Z">
	<svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="xMidYMid slice">
		<path d="M40,30 c 0,0 0,0 0,0 0,0 0,0 0,0 0,0 0,0 0,0 0,0 0,0 0,0 Z"/>
	</svg>
</div>

</body>
</html>
