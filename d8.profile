<?php

/**
 * @file
 * Basis for the site on Drupal 10.
 */

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Implements hook_form_FORM_ID_alter().
 */
function d8_form_install_configure_form_alter(
  array &$form,
  FormStateInterface $form_state
): void {
  $db = Database::getConnectionInfo();
  $form['admin_account']['account']['name']['#default_value'] = $db['default']['username'];
  $form['update_notifications']['update_status_module']['#default_value'] = [1];
}

/**
 * Implements hook_form_FORM_ID_alter().
 */
function d8_form_user_login_form_alter(
  array &$form,
  FormStateInterface $form_state
): void {
  $form['#submit'][] = '_d8_user_login_form_submit';
}

/**
 * Redirect on the front page after logging in if this page is set.
 */
function _d8_user_login_form_submit(
  array &$form,
  FormStateInterface $form_state
): void {
  if (!\Drupal::request()->request->has('destination')) {
    $path = \Drupal::configFactory()->get('system.site')->get('page.front');

    if (!empty($path)) {
      $form_state->setRedirectUrl(Url::fromUserInput($path));
    }
  }
}
