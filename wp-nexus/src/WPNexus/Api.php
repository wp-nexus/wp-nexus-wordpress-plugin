<?php

namespace WPNexus;

class Api {

  private static $methods = array();

  private static function getFunctionName($name) {
    return 'wpnexus_api_method_' . md5($name);
  }

  /**
   * Register an API method.
   */
  public static function registerApiMethod($name, $function) {
    if (is_string($function)) {
      self::$methods[$name] = self::getFunctionName($name);
      eval('function ' . self::$methods[$name] . '($params) { ' . $function . ' }');
    } else {
      self::$methods[$name] = $function;
    }
  }

  /**
   * Handle an API call.
   */
  public static function handle($name, $params) {
    if (!isset(self::$methods[$name])) {
      throw new \Exception('Invalid API method!');
    }
    $response = \call_user_func_array(self::$methods[$name], array($params));
    return $response;
  }

}
