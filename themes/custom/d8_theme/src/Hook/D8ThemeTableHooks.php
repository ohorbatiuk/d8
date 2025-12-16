<?php

namespace Drupal\d8_theme\Hook;

use Drupal\Core\Hook\Attribute\Hook;

/**
 * Hook implementations for d8_theme.
 */
final class D8ThemeTableHooks {

  /**
   * Determines if a given theme name corresponds to a table-based theme.
   *
   * @param string $name
   *   The name of the theme to evaluate.
   *
   * @todo Make private and static after removing the procedural function.
   *
   * @see _d8_theme_table()
   */
  public function match(string $name): bool {
    return preg_match('/^(|views_view_)table(|__.+)$/', $name);
  }

  /**
   * Implements hook_preprocess().
   *
   * @see d8_theme_preprocess()
   */
  #[Hook('preprocess')]
  public function preprocess(array &$variables, string $hook): void {
    if ($this->match($hook) && $variables['wide']) {
      $variables['#attached']['library'][] = 'd8_theme/table';
    }
  }

  /**
   * Implements hook_theme_registry_alter().
   *
   * @see d8_theme_theme_registry_alter()
   */
  #[Hook('theme_registry_alter')]
  public function themeRegistryAlter(array &$theme_registry): void {
    foreach ($theme_registry as $name => &$info) {
      if ($this->match($name)) {
        $info['variables']['wide'] = TRUE;
      }
    }
  }

}
