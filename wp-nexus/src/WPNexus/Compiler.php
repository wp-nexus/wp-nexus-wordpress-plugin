<?php

namespace WPNexus;

class Compiler {

  const FRAMEWORK_ELEMENTS_CACHE_KEY = 'wpnexus_framework_elements';
  const FRAMEWORK_ELEMENTS_CACHE_EXPIRATION = 60 * 60; /* once every hour */

  public static function updateFrameworkElements($jsonData) {
  \set_transient(self::FRAMEWORK_ELEMENTS_CACHE_KEY, $jsonData, self::FRAMEWORK_ELEMENTS_CACHE_EXPIRATION);
  }

  public static function getFrameworkElements() {
    $schema = \get_transient(self::FRAMEWORK_ELEMENTS_CACHE_KEY);
    if ($schema) {
      foreach ($schema as &$frameworkElement) {
        if (isset($frameworkElement['hooks']) && isset($frameworkElement['hooks']['schema'])) {
          eval($frameworkElement['hooks']['schema']);
        }
      }
    }
    return $schema;
  }
}
