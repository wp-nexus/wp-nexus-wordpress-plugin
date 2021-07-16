<?php

namespace WPNexus;

class Plugin {
  public static function init() {
    // Set up admin hooks
    if ( is_admin() ) {
      add_filter( 'allowed_http_origin', array(static::class, 'cors') );
      add_action( 'wp_ajax_wpnexus_ajax', array(static::class, 'handleAjax') );
      add_action( 'admin_menu', array(static::class, 'adminMenu') );
      add_action( 'admin_enqueue_scripts', array(static::class, 'registerAdminStyles') );
      add_action( 'init', array(static::class, 'runFrameworkElementInitHooks') );

      \WPNexus\Api::registerApiMethod('compiler::updateFrameworkElements', '\WPNexus\Compiler::updateFrameworkElements($params["updatedData"]);');
      \WPNexus\Api::registerApiMethod('compiler::getFrameworkElements', 'return \WPNexus\Compiler::getFrameworkElements();');
    }
  }

  /**
   * Allow CORS from IDE.
   */
  public static function cors() {
    return substr($_SERVER['HTTP_REFERER'], 0, strlen(WPNEXUS_IDE_URL)) === WPNEXUS_IDE_URL;
  }

  /**
   * Handle AJAX requests.
   */
  public static function handleAjax() {
    $request = json_decode(stripslashes($_POST['params']), true);
    $response = Api::handle($request['action'], $request['params']);
    echo json_encode($response);
  	wp_die();
  }

  /**
   * Register WP-Nexus menu.
   */
  public static function adminMenu() {
    add_menu_page(
  			__( 'WP-Nexus', 'wp-nexus' ),
  			__( 'WP-Nexus', 'wp-nexus' ),
  			'manage_options',
  			'wp-nexus',
  			'\WPNexus\Plugin::adminPageContents',
  			'dashicons-schedule',
  			61
  		);
  }

  /**
   * Render WP-Nexus IDE.
   */
  public static function adminPageContents() {
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
  public static function registerAdminStyles($hook) {
    if( $hook === 'toplevel_page_wp-nexus' ) {
      wp_register_style( 'wpnexus-admin', plugins_url( 'assets/admin.css', WPNEXUS_PLUGIN_FILE ) );
      wp_enqueue_style( 'wpnexus-admin' );
      wp_enqueue_script( 'jquery' );
    }
  }

  /**
   * Setup framework element hooks.
   */
  public static function runFrameworkElementInitHooks() {
    $frameworkElements = Compiler::getFrameworkElements();
    if (is_array($frameworkElements)) {
      foreach ($frameworkElements as $frameworkElement) {
        if (isset($frameworkElement['hooks']) && isset($frameworkElement['hooks']['init'])) {
          try {
            eval($frameworkElement['hooks']['init']);
          } catch (\Exception $err) {
          }
        }
      }
    }
  }

}
