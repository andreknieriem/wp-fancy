<?php 

class Wpfancy_Sidebars_Admin {
	public static function load() {
		add_action( 'init', array( __CLASS__, 'init' ) );
	}
	
	public static function init() {
		add_action( 'add_meta_boxes', array( __CLASS__, 'register_sidebar_metabox' ) );
	}

	/**
	 * Meta box for adding a new sidebar or choosing an existing sidebar.
	 *
	 * @uses $wpdb, $wp_registered_sidebars
	 *
	 * @param object $page The post object being added or edited.
	 */
	public static function register_sidebar_metabox( $post_type ) {
		if ( $post_type == 'page' || post_type_supports( $post_type, 'simple-page-sidebars' ) ) {
			add_meta_box( 'simplepagesidebarsdiv', __( 'Sidebar', 'wpfancy' ), array( __CLASS__, 'add_sidebar_metabox' ), $post_type, 'side', 'default' );
		}
	}
	
	public static function add_sidebar_metabox($page){
		global $wpdb, $wp_registered_sidebars;
		
		$sidebar = self::get_page_sidebar( $page->ID );
		$custom_sidebars = self::wpfancy_page_sidebars_get_names();
		
		wp_nonce_field( 'update-page-sidebar', 'wpfancy_page_sidebar_update_nonce', false );
		include_once(TEMPLATEPATH.'/includes/sidebar/views/meta-box.php' );
	}

	/**
	 * Get a page sidebar.
	 *
	 * @param int $page_id ID of the page whose sidebar should be returned.
	 * @return string Sanitized sidebar name.
	 */
	public static function get_page_sidebar( $page_id ) {
		return trim( wp_strip_all_tags(( get_post_meta( $page_id, '_my_wpfancysidebar_value_key', true ) )));
	}
	
	/**
	 * Get an array of custom sidebar names.
	 *
	 * @since 0.2.0
	 * @return array Custom sidebar names.
	 */
	public static function wpfancy_page_sidebars_get_names() {
		$sidebars = json_decode(get_option('wp_fancy_sidebars'), true);
		return $sidebars;
	}
}