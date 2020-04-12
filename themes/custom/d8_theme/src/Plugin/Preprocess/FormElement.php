<?php

namespace Drupal\d8_theme\Plugin\Preprocess;

use Drupal\bootstrap\Plugin\Preprocess\FormElement as BootstrapFormElement;
use Drupal\bootstrap\Utility\Element;
use Drupal\bootstrap\Utility\Variables;

/**
 * Pre-processes variables for the "form_element" theme hook.
 *
 * @ingroup plugins_preprocess
 *
 * @BootstrapPreprocess("form_element")
 */
class FormElement extends BootstrapFormElement {

  /**
   * {@inheritdoc}
   */
  public function preprocessElement(Element $element, Variables $variables) {
    parent::preprocessElement($element, $variables);

    if (
      !$element->isType('textfield') ||
      $element->hasClass('form-inline') ||
      !$element->hasAttribute('id')
    ) {
      return;
    }

    $items = [];

    admin_toolbar_search_toolbar_alter($items);

    if (
      isset($items['administration_search']) &&
      $items['administration_search']['tray']['search']['#attributes']['id'] === $element->getAttribute('id')
    ) {
      $variables->addClass('form-inline');
    }
  }

}
