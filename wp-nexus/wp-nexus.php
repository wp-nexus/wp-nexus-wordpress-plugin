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

error_reporting(E_ALL);
ini_set('display_errors', 'on');

defined( 'ABSPATH' ) || exit;

define( 'WPNEXUS_PLUGIN_FILE', __FILE__ );
define( 'WPNEXUS_IDE_URL', 'http://localhost:4200' );

/**
 * Class loader.
 */
function wpnexus_load_class($className) {
  if (substr($className, 0, 7) === 'WPNexus') {
    require_once __DIR__ . '/src/' . str_replace('\\', '/', $className) . '.php';
  }
}

spl_autoload_register('wpnexus_load_class');

\WPNexus\Plugin::init();
