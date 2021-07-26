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

  public static function listPlugins() {
    global $table_prefix, $wpdb;
    $wpnexusPluginsTable = $table_prefix . 'wpnexus_plugin';
    return $wpdb->get_results( "SELECT id, name FROM $wpnexusPluginsTable" );
  }

  public static function getPlugin($params) {
    global $table_prefix, $wpdb;
    $wpnexusPluginsTable = $table_prefix . 'wpnexus_plugin';
    $id = intval($params['id']);
    return $wpdb->get_row( "SELECT * FROM `$wpnexusPluginsTable` WHERE `id` = $id" );
  }

  public static function createPlugin($params) {
    global $table_prefix, $wpdb;
    $wpnexusPluginsTable = $table_prefix . 'wpnexus_plugin';
    $insert = array(
      'name' => $params['name'],
      'code' => $params['code']
    );
    $wpdb->insert( $wpnexusPluginsTable, $insert, array('%s', '%s') );
    return $wpdb->insert_id;
  }

  public static function updatePlugin($params) {
    global $table_prefix, $wpdb;
    $wpnexusPluginsTable = $table_prefix . 'wpnexus_plugin';
    $insert = array(
      'name' => $params['name'],
      'code' => $params['code']
    );
    return $wpdb->update( $wpnexusPluginsTable, $insert, array('%s', '%s'), array('id' => $params['id']), array('%d') );
  }

  public static function deletePlugin($params) {
    global $table_prefix, $wpdb;
    $wpnexusPluginsTable = $table_prefix . 'wpnexus_plugin';
    return $wpdb->delete( $wpnexusPluginsTable, array('id' => $params['id']), array('%d') );
  }

}
