<?php

namespace Drupal\recaptcha_preloader\Service;

use Drupal\Core\Render\Element;

/**
 * Class RecaptchaPreloaderHelper.
 *
 * @package Drupal\recaptcha_preloader\Service
 */
class RecaptchaPreloaderHelper implements RecaptchaPreloaderHelperInterface {

  /**
   * {@inheritdoc}
   */
  public function search(array &$elements) {
    foreach (Element::children($elements) as $key) {
      if (is_array($elements[$key])) {
        switch ($elements[$key]['#type']) {
          case 'actions':
            $this->search($elements[$key]);
            break;

          case 'submit':
            $elements[$key]['#disabled'] = TRUE;
            $elements[$key]['#attributes']['class'][] = 'blocked-by-recaptcha';
            break;
        }
      }
    }
  }

}
