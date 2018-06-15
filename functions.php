<?php
/**
 * Wp Fancy functions and definitions
 *
 */

/**
 * Add support for a custom header image.
 */

/**
 * Wp Fancy only works in WordPress 3.6 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '3.6-alpha', '<' ) )
	require get_template_directory() . '/includes/back-compat.php';

/**
 * Wp Fancy setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * Wp Fancy supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add Visual Editor stylesheets.
 * @uses add_theme_support() To add support for automatic feed links, post
 * formats, and post thumbnails.
 * @uses register_nav_menu() To add support for a navigation menu.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Wp Fancy 1.0
 */
 
function fancy_setup() {
	/*
	 * Makes Wp Fancy available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Wp Fancy, use a find and
	 * template files.
	 */
	$load = load_theme_textdomain( 'wpfancy', get_template_directory() . '/languages' );
	$path = get_template_directory() . '/languages';
	$locale = apply_filters( 'theme_locale', get_locale(), 'wpfancy' );
	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Switches default core markup for search form, comment form,
	 * and comments to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * This theme supports all available post formats by default.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'audio', 'image', 'link', 'quote', 'status', 'video'
	) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', 'Main Menu' );

	/*
	 * This theme uses a custom image size for featured images, displayed on
	 * "standard" posts and pages.
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1780, 1220, false );

	// This theme uses its own gallery styles.
	add_filter( 'use_default_gallery_style', '__return_false' );
	
	//image 1780x1220
	add_image_size( 'gallery-bg', 1780, 1220, false ); 
}
add_action( 'after_setup_theme', 'fancy_setup' );

/**
 * Enqueue scripts and styles for the front end.
 *
 * @since Wp Fancy 1.0
 */
function fancy_scripts() {
	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	// Adds Masonry to handle vertical alignment of footer widgets.
	if ( is_active_sidebar( 'sidebar-1' ) )
		wp_enqueue_script( 'jquery-masonry' );

	// Loads JavaScript file with functionality specific to Wp Fancy.
	wp_enqueue_style( 'wp-mediaelement' );
	wp_enqueue_script( 'wp-mediaelement' );
	
	
	wp_enqueue_script( 'focuspoint-script', get_template_directory_uri() . '/resources/js/jquery.focuspoint.min.js', array( 'jquery' ), '2014-06-08', true );
	wp_enqueue_script( 'touchswipe', get_template_directory_uri() . '/resources/js/jquery.touchSwipe.min.js', array( 'jquery' ), '2014-06-08', true);
	wp_enqueue_script( 'simplelightbox', get_template_directory_uri() . '/resources/js/simple-lightbox.min.js', array( 'jquery' ), '2014-06-08', true );
	wp_enqueue_script( 'velocity', get_template_directory_uri() . '/resources/js/velocity.min.js', array( 'jquery' ), '2014-06-08', true );
	wp_enqueue_script( 'snapsvg', get_template_directory_uri() . '/resources/js/snap.svg-min.js', array( 'jquery' ), '2014-06-08', true );
	wp_enqueue_script( 'svg', get_template_directory_uri() . '/resources/js/src/svgLoader.js', array( 'jquery' ), '2014-06-08', true );
	wp_enqueue_script( 'fancy-script', get_template_directory_uri() . '/resources/js/src/fancy.js', array( 'jquery' ), '2014-06-08', true );
	wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/resources/css/font-awesome.min.css', array(), '4.3.0' );
	wp_enqueue_style( 'simplelightbox-style', get_template_directory_uri() . '/resources/css/simplelightbox.min.css', array(), '4.3.0' );
	wp_enqueue_style( 'fancy', get_template_directory_uri() . '/resources/css/fancy.css', array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'fancy_scripts' );

/**
 * Filter the page title.
 *
 * Creates a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since Wp Fancy 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep   Optional separator.
 * @return string The filtered title.
 */
function wpfancy_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'wpfancy' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'wpfancy_wp_title', 10, 2 );

/**
 * Register two widget areas.
 *
 * @since Wp Fancy 1.0
 */
function wpfancy_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Default Sidebar', 'wpfancy' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Appears in the sidebar of the site.', 'wpfancy' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
	
	register_sidebar( array(
		'name'          => __( 'Footercontent', 'wpfancy' ),
		'id'            => 'footercontent',
		'description'   => __( 'Appears in the sidebar at the bottom', 'wpfancy' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'wpfancy_widgets_init' );

if ( ! function_exists( 'wpfancy_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @since Wp Fancy 1.0
 */
function wpfancy_paging_nav() {
	global $wp_query;

	// Don't print empty markup if there's only one page.
	if ( $wp_query->max_num_pages < 2 )
		return;
	?>
	<nav class="navigation paging-navigation" role="navigation">
		<div class="nav-links">

			<?php if ( get_next_posts_link() ) : ?>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'wpfancy' ) ); ?></div>
			<?php endif; ?>

			<?php if ( get_previous_posts_link() ) : ?>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'wpfancy' ) ); ?></div>
			<?php endif; ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'wpfancy_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
*
* @since Wp Fancy 1.0
*/
function wpfancy_post_nav() {
	global $post;

	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous )
		return;
	?>
	<nav class="navigation post-navigation" role="navigation">
		<div class="nav-links">

			<?php previous_post_link( '%link', _x( '<span class="meta-nav">&larr;</span> %title', 'Previous post link', 'wpfancy' ) ); ?>
			<?php next_post_link( '%link', _x( '%title <span class="meta-nav">&rarr;</span>', 'Next post link', 'wpfancy' ) ); ?>

		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

if ( ! function_exists( 'wpfancy_entry_meta' ) ) :
/**
 *
 * @since Wp Fancy 1.0
 */
function wpfancy_entry_meta() {
	if ( is_sticky() && is_home() && ! is_paged() )
		echo '<span class="featured-post">' . __( 'Sticky', 'wpfancy' ) . '</span>';

	if ( ! has_post_format( 'link' ) && 'post' == get_post_type() )
		wpfancy_entry_date();

	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'wpfancy' ) );
	if ( $categories_list ) {
		echo '<span class="categories-links"><i class="fa fa-list-ul"></i> ' . $categories_list . '</span>';
	}

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'wpfancy' ) );
	if ( $tag_list ) {
		echo '<span class="tags-links"><i class="fa fa-tags"></i> ' . $tag_list . '</span>';
	}

	// Post author
	if ( 'post' == get_post_type() ) {
		printf( '<span class="author vcard"><i class="fa fa-user"></i> <a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
			esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'wpfancy' ), get_the_author() ) ),
			get_the_author()
		);
	}
}
endif;

if ( ! function_exists( 'wpfancy_entry_date' ) ) :
/**
 * Print HTML with date information for current post.
 *
 * Create your own wpfancy() to override in a child theme.
 *
 * @since Wp Fancy 1.0
 *
 * @param boolean $echo (optional) Whether to echo the date. Default true.
 * @return string The HTML-formatted post date.
 */
function wpfancy_entry_date( $echo = true ) {
	if ( has_post_format( array( 'chat', 'status' ) ) )
		$format_prefix = _x( '%1$s on %2$s', '1: post format name. 2: date', 'wpfancy' );
	else
		$format_prefix = '%2$s';

	$date = sprintf( '<span class="date"><i class="fa fa-clock-o"></i> <a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a></span>',
		esc_url( get_permalink() ),
		esc_attr( sprintf( __( 'Permalink to %s', 'wpfancy' ), the_title_attribute( 'echo=0' ) ) ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( sprintf( $format_prefix, get_post_format_string( get_post_format() ), get_the_date() ) )
	);

	if ( $echo )
		echo $date;

	return $date;
}
endif;

if ( ! function_exists( 'wpfancy_the_attached_image' ) ) :
/**
 * Print the attached image with a link to the next attached image.
 *
 * @since Wp Fancy 1.0
 */
function wpfancy_the_attached_image() {
	/**
	 * Filter the image attachment size to use.
	 *
	 * @since Wp Fancy 1.0
	 *
	 * @param array $size {
	 *     @type int The attachment height in pixels.
	 *     @type int The attachment width in pixels.
	 * }
	 */
	$attachment_size     = apply_filters( 'wpfancy_attachment_size', array( 724, 724 ) );
	$next_attachment_url = wp_get_attachment_url();
	$post                = get_post();

	/*
	 * Grab the IDs of all the image attachments in a gallery so we can get the URL
	 * of the next adjacent image in a gallery, or the first image (if we're
	 * looking at the last image in a gallery), or, in a gallery of one, just the
	 * link to that image file.
	 */
	$attachment_ids = get_posts( array(
		'post_parent'    => $post->post_parent,
		'fields'         => 'ids',
		'numberposts'    => -1,
		'post_status'    => 'inherit',
		'post_type'      => 'attachment',
		'post_mime_type' => 'image',
		'order'          => 'ASC',
		'orderby'        => 'menu_order ID'
	) );

	// If there is more than 1 attachment in a gallery...
	if ( count( $attachment_ids ) > 1 ) {
		foreach ( $attachment_ids as $attachment_id ) {
			if ( $attachment_id == $post->ID ) {
				$next_id = current( $attachment_ids );
				break;
			}
		}

		// get the URL of the next image attachment...
		if ( $next_id )
			$next_attachment_url = get_attachment_link( $next_id );

		// or get the URL of the first image attachment.
		else
			$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
	}

	printf( '<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>',
		esc_url( $next_attachment_url ),
		the_title_attribute( array( 'echo' => false ) ),
		wp_get_attachment_image( $post->ID, $attachment_size )
	);
}
endif;

/**
 * Return the post URL.
 *
 * @uses get_url_in_content() to get the URL in the post meta (if it exists) or
 * the first link found in the post content.
 *
 * Falls back to the post permalink if no URL is found in the post.
 *
 * @since Wp Fancy 1.0
 *
 * @return string The Link format URL.
 */
function twentythirteen_get_link_url() {
	$content = get_the_content();
	$has_url = get_url_in_content( $content );

	return ( $has_url ) ? $has_url : apply_filters( 'the_permalink', get_permalink() );
}

if ( ! function_exists( 'wpfancy_excerpt_more' ) && ! is_admin() ) :
/**
 * Replaces "[...]" (appended to automatically generated excerpts) with ...
 * and a Continue reading link.
 *
 * @since Wp Fancy 1.0
 *
 * @param string $more Default Read More excerpt link.
 * @return string Filtered Read More excerpt link.
 */
function wpfancy_excerpt_more( $more ) {
	return '';
}
add_filter( 'excerpt_more', 'wpfancy_excerpt_more' );
endif;

/**
 * Extend the default WordPress body classes.
 *
 * Adds body classes to denote:
 * 1. Single or multiple authors.
 * 2. Active widgets in the sidebar to change the layout and spacing.
 * 3. When avatars are disabled in discussion settings.
 *
 * @since Wp Fancy 1.0
 *
 * @param array $classes A list of existing body class values.
 * @return array The filtered body class list.
 */
function wpfancy_body_class( $classes ) {
	if ( ! is_multi_author() )
		$classes[] = 'single-author';

	if ( is_active_sidebar( 'sidebar-2' ) && ! is_attachment() && ! is_404() )
		$classes[] = 'sidebar';

	if ( ! get_option( 'show_avatars' ) )
		$classes[] = 'no-avatars';

	return $classes;
}
add_filter( 'body_class', 'wpfancy_body_class' );

/**
 * Adjust content_width value for video post formats and attachment templates.
 *
 * @since Wp Fancy 1.0
 */
function wpfancy_content_width() {
	global $content_width;

	if ( is_attachment() )
		$content_width = 724;
	elseif ( has_post_format( 'audio' ) )
		$content_width = 484;
}
add_action( 'template_redirect', 'wpfancy_content_width' );

/**
 * Add postMessage support for site title and description for the Customizer.
 *
 * @since Wp Fancy 1.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function wpfancy_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}
add_action( 'customize_register', 'wpfancy_customize_register' );

/**
 * Enqueue Javascript postMessage handlers for the Customizer.
 *
 * Binds JavaScript handlers to make the Customizer preview
 * reload changes asynchronously.
 *
 * @since Wp Fancy 1.0
 */
function wpfancy_customize_preview_js() {
	wp_enqueue_script( 'wpfancy-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array( 'customize-preview' ), '20141120', true );
}

add_action( 'customize_preview_init', 'wpfancy_customize_preview_js' );

// Gallery Features
add_action( 'admin_menu', 'register_my_custom_menu_page' );
if (isset($_GET['page']) && $_GET['page'] == 'gallerypage'){
	add_action( 'admin_enqueue_scripts', 'wpfancy_enqueue_scriptstyles' );
}

function wpfancy_enqueue_scriptstyles(){
	wp_enqueue_script('jquery');
	// This will enqueue the Media Uploader script
	wp_enqueue_media();
	
	// get focuspoint script for images	
	wp_register_script('focuspoint', get_template_directory_uri().'/resources/js/jquery.focuspoint.min.js',array('jquery'));
	wp_enqueue_script('focuspoint');
	
	wp_register_script('focuspoint-helper', get_template_directory_uri().'/resources/js/jquery.focuspoint.helpertool.js',array('jquery'));
	wp_enqueue_script('focuspoint-helper');
		
	wp_register_script('my-upload', get_template_directory_uri().'/includes/gallery/script.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('my-upload');
	
	wp_register_style('my-gallery', get_template_directory_uri().'/resources/css/admin.css', array(), '1.0.0');
	wp_enqueue_style( 'my-gallery');
}

if(isset($_GET['page']) && $_GET['page'] == 'wpfancy'){
	add_action( 'admin_enqueue_scripts', 'wpfancy_settings_enqueue_scriptstyles' );
}

function wpfancy_settings_enqueue_scriptstyles() {
	wp_enqueue_script('jquery');
	// This will enqueue the Media Uploader script
	wp_enqueue_media();
	wp_register_script('fancycolorpicker', get_template_directory_uri().'/resources/js/colorpicker.min.js',array('jquery'));
	wp_enqueue_script('fancycolorpicker');
	
	wp_register_script('my-upload', get_template_directory_uri().'/includes/admin/settings.js', array('jquery','media-upload','thickbox'));
	wp_enqueue_script('my-upload');
	
	wp_register_style('my-settings', get_template_directory_uri().'/resources/css/admin.css', array(), '1.0.0');
	wp_enqueue_style( 'my-settings');
}

require_once('includes/gallery/galleryAdmin.php');
function register_my_custom_menu_page(){
	add_menu_page( 'List of Galleries', __('Galleries', 'wpfancy'), 'manage_options', 'gallerypage', 'my_galleryPage',  'dashicons-images-alt', 6 ); 
	
	require_once('includes/admin/admin.php');
	add_menu_page( 'Settings for wpfancy', 'WPFancy', 'manage_options', 'wpfancy', 'wpfancy_settings',  'dashicons-admin-appearance' ); 
}

// Create table on active the theme
function on_activate() {
    global $wpdb;
    $create_table_query = "
            CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}galleries` (
              `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
              `name` text NOT NULL,
              `images` text NOT NULL,
              `created` int(11) NOT NULL default '0',
              UNIQUE KEY id (id)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
    ";
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $create_table_query );
		
		// add default settings, if not installed yet.
		if(!get_option('wp_fancy_settings')){
			$settings = json_decode('{"logo":"","favicon":"","galleryImage":"","default_gal":"","default_sidebar":"sidebar-1","enableSearch":"yes","search_items":"","maincolor":"#ffffff","mainheadcolor":"#4ab19a","sidebarcolor":"#3b3b42","sidebarheadcolor":"#25252c","textcolor":"#2b2127","sbtextcolor":"#ffffff","headlinecolor":"#4ab19a","sbheadlinecolor":"#ffffff","linkcolor":"#4ab19a","buttoncolor":"#4ab19a","custom_css":""}',true);
			update_option( 'wp_fancy_settings', json_encode($settings));
		}
}
add_action('after_switch_theme', 'on_activate'); 

// Add Meta Box to pages for gallerychoose
/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
require_once('includes/gallery/metabox.php');
function mygallery_add_meta_box() {
	$screens = array( 'post', 'page' );
	foreach ( $screens as $screen ) {
		add_meta_box(
			'myplugin_sectionid',
			'Gallery',
			'mygallery_meta_box_callback',
			$screen,
			'side'
		);
	}
}
add_action( 'add_meta_boxes', 'mygallery_add_meta_box' );
add_action( 'save_post', 'mygallery_save_meta_box_data' );

function my_init()   
{
	  
    if (!in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php')) && !is_admin())   
    {  
        wp_deregister_script('jquery');  
  
        // Load the copy of jQuery that comes with WordPress  
        // The last parameter set to TRUE states that it should be loaded  
        // in the footer.  
        wp_register_script('jquery', '/wp-includes/js/jquery/jquery.js', FALSE, '1.11.0', TRUE);  
  
        wp_enqueue_script('jquery');  
    }  
}  
add_action('init', 'my_init');  
/* Fontawesome */
require_once('includes/fontawesome/fontawesome.php');


/* Ajax */
add_action('wp_ajax_my_delete_gal', 'my_delete_gal_callback');
add_action('wp_ajax_my_delete_image', 'my_delete_image_callback');
add_action('wp_ajax_my_delete_sidebar', 'my_delete_sidebar_callback');

/* Search Widget */
$settings = json_decode(get_option('wp_fancy_settings'), true);
if($settings['enableSearch'] == 'yes'){
	add_filter('wp_nav_menu_items','add_search_box_to_menu', 10, 2);
	function add_search_box_to_menu( $items, $args ) {
	    if( $args->theme_location == 'primary' )
	        return $items.'
	        <li class="menu-header-search">
	        <a href="#" title="search">
	        	<i class="fa fa-search"></i>
	        </a>
	        <div class="formfield">
	        	<form action="" class="searchform" method="get"><input type="text" name="s" id="s" placeholder="Search"><button class="startSearch"><i class="fa fa-search"></i></button></form>
	        </div>
	        </li>';
	    return $items;
	}
}

/* Custom Sidebars */
require_once( 'includes/sidebar/plugin.php' );
if ( is_admin() ) {
	require_once( 'includes/sidebar/admin/admin.php' );
	Wpfancy_Sidebars_Admin::load();
} else {
}

// Pagination
function get_pagination($range = 4){
  // $paged - Nummer der derzeitigen Seite
  global $paged, $wp_query;
  // Wieviele Seiten haben wir?
  if ( !isset($max_page) ) {
    $max_page = $wp_query->max_num_pages;
  }
  // Wir brauchen die Paginierung nur wenn es mehr als eine Seite gibt
  if($max_page > 1){
  	echo '<div class="pagination">';
    if(!$paged){
      $paged = 1;
    }
    // Auf der ersten brauchen wir nicht den Erste-Link
    if($paged != 1){
      echo '<a href=" '. get_pagenum_link(1) .' "><i class="fa fa-angle-double-left"></i></a>';
    }
    // Zu vorherigen Seite
    previous_posts_link(' <i class="fa fa-angle-left"></i> ');
    // Wir brauchen den Slideeffekt nur, wenn die Seiten den Umfang ($range) uebersteigt
    if($max_page > $range){
      // When closer to the beginning
      if($paged < $range){
        for($i = 1; $i <= ($range + 1); $i++){
          echo "<a href='" . get_pagenum_link($i) ."'";
          if($i==$paged) echo "class='current'";
          echo ">$i</a>";
        }
      }
      //Wenn wir uns dem Ende naehern
      elseif($paged >= ($max_page - ceil(($range/2)))){
        for($i = $max_page - $range; $i <= $max_page; $i++){
          echo "<a href='" . get_pagenum_link($i) ."'";
          if($i==$paged) echo "class='current'";
          echo ">$i</a>";
        }
      }
      // Irgendwo in der Mitte
      elseif($paged >= $range && $paged < ($max_page - ceil(($range/2)))){
        for($i = ($paged - ceil($range/2)); $i <= ($paged + ceil(($range/2))); $i++){
          echo "<a href='" . get_pagenum_link($i) ."'";
          if($i==$paged) echo "class='current'";
          echo ">$i</a>";
        }
      }
    }
    // Weniger Seiten als der Umfang - keine Slide noetig
    else{
      for($i = 1; $i <= $max_page; $i++){
        echo "<a href='" . get_pagenum_link($i) ."'";
        if($i==$paged) echo "class='current'";
        echo ">$i</a>";
      }
    }
    // Naechste Seite
    next_posts_link(' <i class="fa fa-angle-right"></i> ');
    // Auf der letzten Seite kein Letzte-Link
    if($paged != $max_page){
      echo ' <a href=" '. get_pagenum_link($max_page) .' "><i class="fa fa-angle-double-right"></i></a>';
    }
	echo '<div class="clear"></div></div>';
  }
}

