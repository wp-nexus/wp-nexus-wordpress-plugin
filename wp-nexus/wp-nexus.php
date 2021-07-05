<?php
/**
 * Plugin Name: WP-Nexus
 * Plugin URI: http://wp-nexus.com/
 * Description: WP-Nexus plugin.
 * Version: 1.0.0
 * Author: Mindcrumbs
 * Author URI: http://wp-nexus.com/
 * Text Domain: wp-nexus
 * Domain Path: /i18n/languages/
 * Requires at least: 5.5
 * Requires PHP: 7.0
 *
 * @package WPNexus
 */

defined( 'ABSPATH' ) || exit;

define( 'WPNEXUS_IDE_URL', 'http://localhost:4200' );

function wpnexus_admin_menu() {
  add_menu_page(
			__( 'WP-Nexus', 'wp-nexus' ),
			__( 'WP-Nexus', 'wp-nexus' ),
			'manage_options',
			'wp-nexus',
			'wpnexus_admin_page_contents',
			'dashicons-schedule',
			61
		);
}
add_action( 'admin_menu', 'wpnexus_admin_menu' );

function wpnexus_admin_page_contents() {
  ?>
  <iframe id="wpnexus-ide-container" src="<?php echo WPNEXUS_IDE_URL; ?>"></iframe>
  <?php
}

function wpnexus_register_admin_styles($hook) {
  if( $hook === 'toplevel_page_wp-nexus' ) {
    wp_register_style( 'wpnexus-admin', plugins_url( 'assets/admin.css', __FILE__ ) );
    wp_enqueue_style( 'wpnexus-admin' );
  }
}
add_action( 'admin_enqueue_scripts', 'wpnexus_register_admin_styles' );
