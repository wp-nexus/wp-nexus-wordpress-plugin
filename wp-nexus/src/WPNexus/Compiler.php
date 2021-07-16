<?php

namespace WPNexus;

class Compiler {

  const FRAMEWORK_ELEMENTS_CACHE_KEY = 'wpnexus_framework_elements';
  const FRAMEWORK_ELEMENTS_CACHE_EXPIRATION = 60; /*24 * 60 * 60 /* once a day */

  public static function updateFrameworkElements($jsonData) {
  \set_transient(self::FRAMEWORK_ELEMENTS_CACHE_KEY, $jsonData, self::FRAMEWORK_ELEMENTS_CACHE_EXPIRATION);
  }

  public static function getFrameworkElements() {
    return \get_transient(self::FRAMEWORK_ELEMENTS_CACHE_KEY);
  }
}
