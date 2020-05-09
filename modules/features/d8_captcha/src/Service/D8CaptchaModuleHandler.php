<?php

namespace Drupal\d8_captcha\Service;

use Drupal\Core\Extension\ModuleHandler;

/**
 * Class D8CaptchaModuleHandler.
 *
 * @package Drupal\d8_captcha\Service
 */
class D8CaptchaModuleHandler extends ModuleHandler {

  /**
   * {@inheritdoc}
   */
  public function invoke($module, $hook, array $args = []) {
    $data = parent::invoke($module, $hook, $args);

    if (
      $module === 'recaptcha_preloader' &&
      $hook === 'captcha' &&
      $args[0] === 'generate' &&
      $args[1] === recaptcha_preloader_captcha('list')[0] &&
      isset($data['form']['#attached']['library'])
    ) {
      unset($data['form']['#attached']['library']);
    }

    return $data;
  }

}
