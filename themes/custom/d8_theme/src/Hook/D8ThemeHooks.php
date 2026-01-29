<?php

namespace Drupal\d8_theme\Hook;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Hook\Attribute\Hook;

/**
 * Hook implementations for d8_theme.
 */
final class D8ThemeHooks {

  /**
   * Implements hook_form_FORM_ID_alter().
   */
  #[Hook('form_system_theme_settings_alter')]
  public function formSystemThemeSettingsAlter(
    array &$form,
    FormStateInterface $form_state,
    ?string $form_id = NULL,
  ): void {
    $group = &$form['components']['navbar'];

    $old = &$group['bootstrap_navbar_background']['#options'];
    $new = [];

    foreach ($old as $class => $label) {
      $new[$class === 'bg-light' ? 'bg-body-tertiary' : $class] = $label;
    }

    $old = $new;

    foreach (['maxlength', 'size'] as $key) {
      $group['bootstrap_navbar_class']["#$key"] = 50;
    }
  }

  /**
   * Implements hook_form_FORM_ID_alter().
   */
  #[Hook('form_user_form_alter')]
  public function formUserFormAlter(
    array &$form,
    FormStateInterface $form_state,
    string $form_id,
  ): void {
    $form['timezone']['#type'] = 'container';
  }

  /**
   * Implements hook_preprocess_HOOK().
   */
  #[Hook('preprocess_block')]
  public function preprocessBlock(array &$variables): void {
    if ($variables['plugin_id'] === 'system_menu_block:account') {
      $variables['attributes']['class'][] = 'dropdown';
    }

    unset($variables['attributes']['id']);

    foreach (['attributes', 'content_attributes'] as $element) {
      if (count($variables[$element]) === 0) {
        unset($variables[$element]);
      }
    }
  }

  /**
   * Implements hook_preprocess_HOOK().
   */
  #[Hook('preprocess_captcha')]
  public function preprocessCaptcha(array &$variables): void {
    $variables['attributes']['class'][] = 'mb-3';
  }

  /**
   * Implements hook_preprocess_HOOK().
   */
  #[Hook('preprocess_html')]
  public function preprocessHtml(array &$variables): void {
    $variables['html_attributes']->addClass('h-100');

    $variables['attributes']['class'] = [
      ...($variables['attributes']['class'] ?? []),
      'd-flex',
      'flex-column',
      'h-100',
    ];
  }

  /**
   * Implements hook_preprocess_HOOK().
   */
  #[Hook('preprocess_status_messages')]
  public function preprocessStatusMessages(array &$variables): void {
    $variables['attributes']['data-bs-autohide'] = 'false';
    $variables['attributes']['data-drupal-selector'] = 'messages';
    $variables['attributes']['class'][] = 'fade';

    foreach (['aria-label', 'data-bs-delay', 'role'] as $attribute) {
      unset($variables['attributes'][$attribute]);
    }
  }

}
