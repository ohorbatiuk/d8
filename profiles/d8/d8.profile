<?php

/**
 * @file
 * Enables modules and site configuration for a D8 site installation.
 */

/**
 * Implements hook_modules_installed().
 */
function portfolio_modules_installed($modules) {
  foreach (['seven', 'bootstrap'] as $module) {
    if (in_array($module, $modules)) {
      $module_list = ['d8_feature_blocks_' . $module];
      \Drupal::service('module_installer')->install($module_list, TRUE);
    }
  }
}
