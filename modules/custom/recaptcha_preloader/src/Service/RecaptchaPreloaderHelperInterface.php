<?php

namespace Drupal\recaptcha_preloader\Service;

/**
 * Interface RecaptchaPreloaderHelperInterface.
 *
 * @package Drupal\recaptcha_preloader\Service
 */
interface RecaptchaPreloaderHelperInterface {

  /**
   * Disable submit form elements.
   *
   * @param array $elements
   *   The element array.
   */
  public function search(array &$elements);

}
