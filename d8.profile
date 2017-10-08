<?php

/**
 * @file
 * Basis for the site on Drupal 8.
 */

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

/**
 * Implements hook_form_FORM_ID_alter().
 */
function d8_form_install_configure_form_alter(array &$form, FormStateInterface $form_state) {
  $db = Database::getConnectionInfo();
  $form['admin_account']['account']['name']['#default_value'] = $db['default']['username'];
  $form['update_notifications']['update_status_module']['#default_value'] = array(1);
}
