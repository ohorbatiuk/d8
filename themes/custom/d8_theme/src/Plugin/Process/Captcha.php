<?php

namespace Drupal\d8_theme\Plugin\Process;

use Drupal\bootstrap\Plugin\Process\ProcessBase;
use Drupal\bootstrap\Plugin\Process\ProcessInterface;
use Drupal\bootstrap\Utility\Element;
use Drupal\Core\Form\FormStateInterface;

/**
 * Processes the "captcha" element.
 *
 * @ingroup plugins_process
 *
 * @BootstrapProcess("captcha")
 */
class Captcha extends ProcessBase implements ProcessInterface {

  /**
   * {@inheritdoc}
   */
  public static function processElement(
    Element $element,
    FormStateInterface $form_state,
    array &$complete_form
  ): void {
    if ($element->hasProperty('theme_wrappers')) {
      $element->appendProperty('theme_wrappers', 'form_element');
    }
    else {
      $element->setProperty('theme_wrappers', ['form_element']);
    }
  }

}
