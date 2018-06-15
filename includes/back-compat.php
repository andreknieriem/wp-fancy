<?php
/**
 * Wp Fancy back compat functionality
 *
 * Prevents Wp Fancy from running on WordPress versions prior to 3.6,
 * since this theme is not meant to be backward compatible and relies on
 * many new functions and markup changes introduced in 3.6.
 *
 * @since Wp Fancy 1.0
 */

/**
 * Prevent switching to Wp Fancy on old versions of WordPress.
 *
 * Switches to the default theme.
 *
 * @since Wp Fancy 1.0
 */
function wpfancy_switch_theme() {
	switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'wpfancy_upgrade_notice' );
}
add_action( 'after_switch_theme', 'wpfancy_switch_theme' );

/**
 * Add message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * Wp Fancy on WordPress versions prior to 3.6.
 *
 * @since Wp Fancy 1.0
 */
function wpfancy_upgrade_notice() {
	$message = sprintf( __( 'Wp Fancy requires at least WordPress version 3.6. You are running version %s. Please upgrade and try again.', 'wpfancy' ), $GLOBALS['wp_version'] );
	printf( '<div class="error"><p>%s</p></div>', $message );
}

/**
 * Prevent the Customizer from being loaded on WordPress versions prior to 3.6.
 *
 * @since Wp Fancy 1.0
 */
function wpfancy_customize() {
	wp_die( sprintf( __( 'Wp Fancy requires at least WordPress version 3.6. You are running version %s. Please upgrade and try again.', 'wpfancy' ), $GLOBALS['wp_version'] ), '', array(
		'back_link' => true,
	) );
}
add_action( 'load-customize.php', 'wpfancy_customize' );

/**
 * Prevent the Theme Preview from being loaded on WordPress versions prior to 3.4.
 *
 * @since Wp Fancy 1.0
 */
function wpfancy_preview() {
	if ( isset( $_GET['preview'] ) ) {
		wp_die( sprintf( __( 'Wp Fancy requires at least WordPress version 3.6. You are running version %s. Please upgrade and try again.', 'wpfancy' ), $GLOBALS['wp_version'] ) );
	}
}
add_action( 'template_redirect', 'wpfancy_preview' );
