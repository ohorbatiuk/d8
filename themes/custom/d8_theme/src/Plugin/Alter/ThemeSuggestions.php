<?php

namespace Drupal\d8_theme\Plugin\Alter;

use Drupal\bootstrap\Plugin\Alter\ThemeSuggestions as ThemeSuggestionsBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Implements hook_theme_suggestions_alter().
 *
 * @ingroup plugins_alter
 *
 * @BootstrapAlter("theme_suggestions")
 */
class ThemeSuggestions extends ThemeSuggestionsBase implements ContainerFactoryPluginInterface {

  /**
   * The route match.
   */
  private readonly RouteMatchInterface $routeMatch;

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ): self {
    $instance = new static($configuration, $plugin_id, $plugin_definition);

    $instance->routeMatch = $container->get('current_route_match');

    return $instance;
  }

  /**
   * Dynamic alter method for "maintenance_page".
   */
  protected function alterMaintenance_page(): void {
    if ($this->routeMatch->getRouteName() !== 'system.db_update') {
      $this->addSuggestion($this->originalHook . '__guest');
    }
  }

}
