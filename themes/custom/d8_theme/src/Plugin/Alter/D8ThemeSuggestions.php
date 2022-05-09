<?php

namespace Drupal\d8_theme\Plugin\Alter;

use Drupal\bootstrap\Plugin\Alter\ThemeSuggestions;

/**
 * Implements hook_theme_suggestions_alter().
 *
 * @ingroup plugins_alter
 *
 * @BootstrapAlter("theme_suggestions")
 */
class D8ThemeSuggestions extends ThemeSuggestions {

  /**
   * Dynamic alter method for "maintenance_page".
   */
  protected function alterMaintenancePage() {
    $this->addSuggestion('maintenance_page__d8');
  }

}
