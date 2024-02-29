<?php

namespace Drupal\d8_theme\Plugin\Alter;

use Drupal\bootstrap\Plugin\Alter\ThemeSuggestions as ThemeSuggestionsBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\service\RouteMatchTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Implements hook_theme_suggestions_alter().
 *
 * @ingroup plugins_alter
 *
 * @BootstrapAlter("theme_suggestions")
 */
class ThemeSuggestions extends ThemeSuggestionsBase implements ContainerFactoryPluginInterface {

  use RouteMatchTrait;

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ): static {
    return (new static($configuration, $plugin_id, $plugin_definition))
      ->addRouteMatch($container);
  }

  /**
   * Dynamic alter method for "maintenance_page".
   */
  protected function alterMaintenance_page(): void {
    if ($this->routeMatch()->getRouteName() !== 'system.db_update') {
      $this->addSuggestion($this->originalHook . '__guest');
    }
  }

}
