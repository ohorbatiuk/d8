<?php

/**
 * @file
 * Basis for the site on Drupal 8.
 */

use Drupal\Component\Utility\UrlHelper;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

/**
 * Implements hook_form_FORM_ID_alter().
 */
function d8_form_install_configure_form_alter(array &$form, FormStateInterface $form_state) {
  $db = Database::getConnectionInfo();
  $form['admin_account']['account']['name']['#default_value'] = $db['default']['username'];
  $form['update_notifications']['update_status_module']['#default_value'] = [1];
}

/**
 * Implements hook_form_FORM_ID_alter().
 */
function d8_form_user_login_form_alter(array &$form, FormStateInterface $form_state) {
  $form['#submit'][] = '_d8_user_login_form_submit';
}

/**
 * Redirect on the front page after logging in if this page is set.
 */
function _d8_user_login_form_submit(array &$form, FormStateInterface $form_state) {
  if (\Drupal::request()->request->has('destination')) {
    return;
  }

  $path = \Drupal::configFactory()->get('system.site')->get('page.front');

  if (!empty($path)) {
    $form_state->setRedirectUrl(Url::fromUserInput($path));
  }
}

/**
 * Implements hook_page_attachments_alter().
 */
function d8_page_attachments_alter(array &$attachments) {
  $theme = \Drupal::config('system.theme')->get('default');

  if ($theme != \Drupal::theme()->getActiveTheme()->getName() && theme_get_setting('features.favicon') && !empty($attachments['#attached']['html_head_link']) && is_array($attachments['#attached']['html_head_link'])) {
    foreach ($attachments['#attached']['html_head_link'] as &$links) {
      if (!empty($links) && is_array($links)) {
        foreach ($links as &$link) {
          if (!empty($link) && is_array($link) && !empty($link['rel']) && $link['rel'] == 'shortcut icon' && !empty($link['type']) && $link['type'] == theme_get_setting('favicon.mimetype') && !empty($link['href'])) {
            $favicon = theme_get_setting('favicon.url', $theme);
            $link['href'] = UrlHelper::stripDangerousProtocols($favicon);
            return;
          }
        }
      }
    }
  }
}
