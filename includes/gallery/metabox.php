<?php

function mygallery_meta_box_callback( $post ){
	global $wpdb;
	
	$galleryData = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.'galleries Order by id' );
	
	// Add an nonce field so we can check for it later.
	wp_nonce_field( 'mygallery_meta_box', 'mygallery_meta_box_nonce' );

	/*
	 * Use get_post_meta() to retrieve an existing value
	 * from the database and use the value for the form.
	 */
	$value = get_post_meta( $post->ID, '_my_gallery_value_key', true );
	echo '<label for="myplugin_new_field">';
	echo __( 'Choose a gallery', 'wpfancy' );
	echo '</label> ';
	echo '<select class="widefat" id="mygallery_field" name="mygallery_field">
			<option value=""></option>
	';
	foreach($galleryData as $gallery){
		$selected = '';
		if($gallery->id == $value) {
			$selected = 'selected="selected"';
		}
		echo '<option '.$selected.' value="'.$gallery->id.'">'.$gallery->name.'</option>';
	}
	echo '</select>';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function mygallery_save_meta_box_data( $post_id ) {

	/*
	 * We need to verify this came from our screen and with proper authorization,
	 * because the save_post action can be triggered at other times.
	 */
	// Check if our nonce is set.
	if ( ! isset( $_POST['mygallery_meta_box_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['mygallery_meta_box_nonce'], 'mygallery_meta_box' ) ) {
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
	if ( ! isset( $_POST['mygallery_field'] ) ) {
		return;
	}

	// Sanitize user input.
	$my_data = sanitize_text_field( $_POST['mygallery_field'] );

	// Update the meta field in the database.
	update_post_meta( $post_id, '_my_gallery_value_key', $my_data );
}
