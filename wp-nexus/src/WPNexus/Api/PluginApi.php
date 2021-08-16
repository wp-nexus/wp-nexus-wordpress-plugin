<?php

namespace WPNexus\Api;

class PluginApi {

  public static function init() {
    \WPNexus\Api::registerApiMethod('plugin::list', array(static::class, 'listPlugins'));
    \WPNexus\Api::registerApiMethod('plugin::get', array(static::class, 'getPlugin'));
    \WPNexus\Api::registerApiMethod('plugin::create', array(static::class, 'createPlugin'));
    \WPNexus\Api::registerApiMethod('plugin::update', array(static::class, 'updatePlugin'));
    \WPNexus\Api::registerApiMethod('plugin::delete', array(static::class, 'deletePlugin'));
  }

  private static function getPluginsTable() {
    global $table_prefix;
    return $table_prefix . 'wpnexus_plugin';
  }

  public static function listPlugins() {
    global $wpdb;
    $wpnexusPluginsTable = self::getPluginsTable();
    return $wpdb->get_results( "SELECT id, name FROM $wpnexusPluginsTable" );
  }

  public static function getPlugin($params) {
    global $wpdb;
    $wpnexusPluginsTable = self::getPluginsTable();
    $id = intval($params['id']);
    $plugin = $wpdb->get_row( "SELECT * FROM $wpnexusPluginsTable WHERE id = $id" );
    $code = json_decode($plugin->code, true);
    
    $frameworkElements = \WPNexus\Compiler::getFrameworkElements();
    if (is_array($frameworkElements)) {
      foreach ($frameworkElements as $frameworkElement) {
        if (isset($frameworkElement['hooks']) && isset($frameworkElement['hooks']['pluginLoading'])) {
          try {
            eval($frameworkElement['hooks']['pluginLoading']);
          } catch (\Exception $err) {
          }
        }
      }
    }

    $plugin->code = json_encode($code);
    return $plugin;
  }

  public static function createPlugin($params) {
    global $wpdb;
    $wpnexusPluginsTable = self::getPluginsTable();
    $insert = array(
      'name' => $params['name'],
      'code' => $params['code']
    );
    $wpdb->insert( $wpnexusPluginsTable, $insert, array('%s', '%s') );
    return $wpdb->insert_id;
  }

  public static function updatePlugin($params) {
    global $wpdb;
    $wpnexusPluginsTable = self::getPluginsTable();
    $update = array(
      'name' => $params['name'],
      'code' => $params['code']
    );
    return $wpdb->update( $wpnexusPluginsTable, $update, array('id' => intval($params['id'])), array('%s', '%s'), array('%d') );
  }

  public static function deletePlugin($params) {
    global $wpdb;
    $wpnexusPluginsTable = self::getPluginsTable();
    return $wpdb->delete( $wpnexusPluginsTable, array('id' => $params['id']), array('%d') );
  }

}
