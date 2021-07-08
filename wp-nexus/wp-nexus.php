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

/**
 * Allow CORS from IDE.
 */
function wpnexus_cors() {
  return substr($_SERVER['HTTP_REFERER'], 0, strlen(WPNEXUS_IDE_URL)) === WPNEXUS_IDE_URL;
}

/**
 * Handle AJAX requests.
 */
function wpnexus_ajax() {
  $request = json_decode(stripslashes($_POST['params']), true);
  //var_dump($request);

  $response = null;

  switch ($request['action']) {
    case 'getPostTypes':
      $response = get_post_types(array(), 'objects');
      break;
  }

  echo json_encode($response);

	wp_die();
}

/**
 * Register WP-Nexus menu.
 */
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

/**
 * Render WP-Nexus IDE.
 */
function wpnexus_admin_page_contents() {
  ?>
  <iframe id="wpnexus-ide-container" src="about:blank"></iframe>
    <script type="text/javascript">
      jQuery(function($){
        $('#wpnexus-ide-container').attr('src', <?php echo json_encode(WPNEXUS_IDE_URL . '#site_url=' . site_url()); ?> + '&ajax=' + ajaxurl);
      });
    </script>
  <?php
}

/**
 * Add custom admin styles.
 */
function wpnexus_register_admin_styles($hook) {
  if( $hook === 'toplevel_page_wp-nexus' ) {
    wp_register_style( 'wpnexus-admin', plugins_url( 'assets/admin.css', __FILE__ ) );
    wp_enqueue_style( 'wpnexus-admin' );
    wp_enqueue_script( 'jquery' );
  }
}

// Set up admin hooks
if ( is_admin() ) {
  add_filter('allowed_http_origin', 'wpnexus_cors');
  add_action('wp_ajax_wpnexus_ajax', 'wpnexus_ajax');
  add_action( 'admin_menu', 'wpnexus_admin_menu' );
  add_action( 'admin_enqueue_scripts', 'wpnexus_register_admin_styles' );
}
