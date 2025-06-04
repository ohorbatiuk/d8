<?php

namespace Drupal\d8\Hook;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Url;

/**
 * Hook implementations for d8.
 */
final class D8Hooks {

  /**
   * Implements hook_form_FORM_ID_alter().
   */
  #[Hook('page_attachments')]
  public function formUserLoginFormAlter(
    array &$form,
    FormStateInterface $form_state,
  ): void {
    $form['#submit'][] = [static::class, 'submit'];
  }

  /**
   * Redirect on the front page after logging in if this page is set.
   */
  public static function submit(
    array &$form,
    FormStateInterface $form_state,
  ): void {
    if (!\Drupal::request()->request->has('destination')) {
      $path = \Drupal::configFactory()->get('system.site')->get('page.front');

      if (!empty($path)) {
        $form_state->setRedirectUrl(Url::fromUserInput($path));
      }
    }
  }

}
