<?php

namespace WPNexus;

class SchemaUtil {

  public static function getFrameworkElementById($schema, $id) {
    foreach ($schema as &$frameworkElement) {
      if ($frameworkElement['id'] === $id) {
        return $frameworkElement;
      }
    }
    return null;
  }

}
