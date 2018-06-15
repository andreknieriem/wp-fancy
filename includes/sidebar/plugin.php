<?php

add_action( 'admin_enqueue_scripts', 'wpfancy_sidebar_enqueue_scriptstyles' );

function wpfancy_sidebar_enqueue_scriptstyles(){
	wp_enqueue_script('jquery');
	wp_register_style('my-settings', get_template_directory_uri().'/resources/css/admin.css', array(), '1.0.0');
	wp_enqueue_style( 'my-settings');
	wp_register_script('my-sidebar', get_template_directory_uri().'/includes/sidebar/sidebar.js', array('jquery'));
	wp_enqueue_script('my-sidebar');
}

if ( ! is_admin() ) {
	add_filter( 'sidebars_widgets', 'replace_sidebar' );
}

add_action( 'widgets_init', 'register_fancy_sidebars', 20 );

function register_fancy_sidebars() {
	$widget_areas = array();

	// Add widget areas using this filter.
	$widget_areas = apply_filters( 'wpfancy_sidebars_widget_areas', $widget_areas );

	// Verify id's exist, otherwise create them.
	// Help ensure widgets don't get mixed up if widget areas are added or removed.
	if ( ! empty( $widget_areas ) && is_array( $widget_areas ) ) {
		foreach ( $widget_areas as $key => $area ) {
			if ( is_numeric( $key ) ) {
				$widget_areas[ 'widget-area-' . sanitize_key( $area['name'] ) ] = $area;
				unset( $widget_areas[ $key ] );
			}
		}
	}

	// Override the default widget properties.
	$widget_area_defaults = array(
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="title">',
		'after_title'   => '</h4>'
	);

	$widget_area_defaults = apply_filters( 'wpfancy_sidebars_widget_defaults', $widget_area_defaults );

	// If any custom sidebars have been assigned to pages, merge them with already defined widget areas.
	$sidebars = json_decode(get_option('wp_fancy_sidebars'), true);
	if ( ! empty( $sidebars ) ) {
		foreach ( $sidebars as $sidebar ) {
			$page_sidebars[ 'page-sidebar-' . sanitize_key( $sidebar ) ] = array(
				'name'        => $sidebar,
				'description' => ''
			);
		}

		ksort( $page_sidebars );
		$widget_areas = array_merge_recursive( $widget_areas, $page_sidebars );
	}

	if ( ! empty( $widget_areas ) && is_array( $widget_areas ) ) {
		// Register the widget areas.
		foreach ( $widget_areas as $key => $area ) {
			register_sidebar(array(
				'id'            => $key,
				'name'          => $area['name'],
				'description'   => $area['description'],
				'before_widget' => ( isset( $area['before_widget'] ) ) ? $area['before_widget'] : $widget_area_defaults['before_widget'],
				'after_widget'  => ( isset( $area['after_widget'] ) )  ? $area['after_widget']  : $widget_area_defaults['after_widget'],
				'before_title'  => ( isset( $area['before_title'] ) )  ? $area['before_title']  : $widget_area_defaults['before_title'],
				'after_title'   => ( isset( $area['after_title'] ) )   ? $area['after_title']   : $widget_area_defaults['after_title']
			));
		}
	}
}

add_action( 'admin_menu', 'register_sidebar_menu_page' );
function register_sidebar_menu_page(){
	add_menu_page( 'List of Sidebars', 'Sidebars', 'manage_options', 'sidebarpage', 'my_sidebarPage',  'dashicons-align-left', 7 ); 
}

function my_sidebarPage(){
	$validActions = array('listSidebars','delete','newSidebar');
	$action = isset($_GET['action']) ? $_GET['action'] : 'listSidebars';
	if(in_array($action, $validActions)){
		call_user_func($action);
	} else {
		listSidebars();
	}
}

function listSidebars(){
	$sidebars = json_decode(get_option('wp_fancy_sidebars'), true);
	
	?>
	<div class="wrap">
		<h2><?php echo __('Sidebars', 'wpfancy'); ?> <a href="/wp-admin/admin.php?page=sidebarpage&action=newSidebar" class="add-new-h2"><?php echo __('Create', 'wpfancy'); ?></a></h2>
	<table class="wp-list-table widefat fixed">
		<thead>
		<tr>
			<th scope="col" id="title" class="manage-column column-title sortable desc" style="">
				<a href=""><span><?php echo __('Title', 'wpfancy'); ?></span>
					<span class="sorting-indicator"></span>
				</a>
			</th>
			<th class="delete-col"><?php echo __('Actions', 'wpfancy'); ?></th>
		</tr>
		</thead>
		<tbody>
			<?php
			$alternate = 'alternate';
			$count = 0;
			foreach($sidebars as $sb){
				$alternate = ($count % 2 == 0) ? 'alternate': '';
				echo '
					<tr class="'.$alternate.'">
						<td>'.$sb.'</td>		
						<td class="delete-col"><a class="sb" href="#" data-name="'.$sb.'">'.__('Delete', 'wpfancy').'</a></td>					
					</tr>
				';
				$count ++;
			}
			
			?>
		</tbody>		
	</table>
	</div>
	<?php
}

function my_delete_sidebar_callback(){
	global $wpdb;	
	$sidebars = json_decode(get_option('wp_fancy_sidebars'), true);
	
	$name = $_POST['name'];
	
	if(($key = array_search($name, $sidebars)) !== false) {
	    unset($sidebars[$key]);
	}
	update_option('wp_fancy_sidebars', json_encode($sidebars));
	
	// unset all sidebar from pages/posts
	$wpdb->delete($wpdb->prefix.'postmeta' , array('meta_value' => $name));
	
	echo json_encode(array('success' => true));
}

function newSidebar(){
	$name = '';
	$sidebars = json_decode(get_option('wp_fancy_sidebars'), true);
	if(isset($_POST['name']) && !empty($_POST['name'])) {
		$name = trim($_POST['name']);
		if(!in_array($name, $sidebars)){
			$sidebars[] = $name;
			update_option('wp_fancy_sidebars', json_encode($sidebars));
			$newURL = 'http://'.$_SERVER['HTTP_HOST'].'/wp-admin/admin.php?page=sidebarpage';
			echo '<script type="text/javascript">window.location = "'.$newURL.'"</script>';
			die();
		} else {
			// Display Error
			echo '<div id="message" class="error fancyupdate"><p>'.$name.__(' is already taken', 'wpfancy').'</p></div>';
		}
	}
	
	echo '
	<div id="wpfancy_settings">
		<div class="labelRow">
			<strong>'.__('Create Sidebar', 'wpfancy').'</strong>
		</div>
   		<form action="" method="post" id="createSbForm">
   		<div class="row">
	   		<div class="half">
		   		<div class="formline">
		   			<label for="name">'.__('Name', 'wpfancy').'</label><br/>
		   			<input class="widefat" value="'.$name.'" type="text" id="name" name="name" />
		   		</div>
	   		</div>
	   		<div class="half"></div>
	   		<div class="clear"></div>
   		</div>
   		<div class="row">
			<div class="leftCol">
			</div>
			<div class="rightCol">
				<input name="save" type="submit" class="pull-right button button-primary button-large" value="'.__('Add Sidebar', 'wpfancy').'">
			</div>
			<div class="clear"></div>
		</div>
   		</form>
	</div>
	';
}

add_action( 'save_post', 'wpfancy_save_meta_box_data' );

function wpfancy_save_meta_box_data($post_id){
	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */
	 
	// Check if our nonce is set.
	if ( ! isset( $_POST['wpfancy_page_sidebar_update_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['wpfancy_page_sidebar_update_nonce'], 'update-page-sidebar' ) ) {
		return;
	}
	
	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	/* OK, it's safe for us to save the data now. */
	
	// Make sure that it is set.
	if ( ! isset( $_POST['wpfancy_siderbarname'] ) ) {
		return;
	}

	// Sanitize user input.
	$my_data = sanitize_text_field( $_POST['wpfancy_siderbarname'] );
	// Update the meta field in the database.
	update_post_meta( $post_id, '_my_wpfancysidebar_value_key', $my_data );
}

function replace_sidebar($sidebars_widgets){
	global $post;

		$supports = ( isset( $post->post_type ) && post_type_supports( $post->post_type, 'simple-page-sidebars' ) ) ? true : false;

		if ( is_page() || $supports || ( is_home() && $posts_page = get_option( 'page_for_posts' ) ) ) {
			$post_id = ( ! empty( $posts_page ) ) ? $posts_page : $post->ID;
			
			$custom_sidebar = get_post_meta( $post_id, '_my_wpfancysidebar_value_key', true );
			$settings = json_decode(get_option( 'wp_fancy_settings' ), true);
			$default_sidebar_id = $settings['default_sidebar'];
			
			if ( $custom_sidebar && $default_sidebar_id ) {
				$custom_sidebar_id = 'page-sidebar-' . sanitize_key( $custom_sidebar );

				// Only replace the default sidebar if the custom sidebar has widgets.
				if ( ! empty( $sidebars_widgets[ $custom_sidebar_id ] ) ) {
					$sidebars_widgets[ $default_sidebar_id ] = $sidebars_widgets[ $custom_sidebar_id ];
				}
			}
		}

		return $sidebars_widgets;
}
