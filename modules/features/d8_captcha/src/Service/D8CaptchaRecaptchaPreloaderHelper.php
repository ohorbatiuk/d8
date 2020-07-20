<?php

namespace Drupal\d8_captcha\Service;

use Drupal\recaptcha_preloader\Service\RecaptchaPreloaderHelper;

/**
 * Class D8CaptchaRecaptchaPreloaderHelper.
 *
 * @package Drupal\d8_captcha\Service
 */
class D8CaptchaRecaptchaPreloaderHelper extends RecaptchaPreloaderHelper {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $captcha = parent::build();

    if (isset($captcha['form']['#attached']['library'])) {
      unset($captcha['form']['#attached']['library']);
    }

    return $captcha;
  }

}
