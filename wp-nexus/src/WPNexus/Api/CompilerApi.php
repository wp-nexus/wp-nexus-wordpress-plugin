<?php

namespace WPNexus\Api;

class CompilerApi {

  public static function init() {
    \WPNexus\Api::registerApiMethod('compiler::updateFrameworkElements', array(static::class, 'updateFrameworkElements'));
    \WPNexus\Api::registerApiMethod('compiler::getFrameworkElements', array(static::class, 'getFrameworkElements'));
  }

  public static function updateFrameworkElements($params) {
    \WPNexus\Compiler::updateFrameworkElements($params["updatedData"]);
  }

  public static function getFrameworkElements() {
    return \WPNexus\Compiler::getFrameworkElements();
  }

}
