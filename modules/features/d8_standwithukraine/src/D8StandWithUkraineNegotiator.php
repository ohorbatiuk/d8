<?php

namespace Drupal\d8_standwithukraine;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Menu\LocalTaskManagerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Theme\ThemeManagerInterface;
use Drupal\standwithukraine\Service\StandWithUkraineNegotiatorInterface;
use Drupal\standwithukraine\StandWithUkraineSettingsInterface;

/**
 * Determines message settings for admin theme.
 */
class D8StandWithUkraineNegotiator implements StandWithUkraineNegotiatorInterface {

  /**
   * The configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  private $configFactory;

  /**
   * The theme.
   *
   * @var \Drupal\Core\Theme\ThemeManagerInterface
   */
  private $themeManager;

  /**
   * The currently active route match object.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  private $routeMatch;

  /**
   * The local task manger.
   *
   * @var \Drupal\Core\Menu\LocalTaskManagerInterface
   */
  private $localTaskManager;

  /**
   * D8StandWithUkraineNegotiator constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   * @param \Drupal\Core\Theme\ThemeManagerInterface $theme_manager
   *   The theme.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The currently active route match object.
   * @param \Drupal\Core\Menu\LocalTaskManagerInterface $local_task_manager
   *   The local task manger.
   */
  public function __construct(
    ConfigFactoryInterface $config_factory,
    ThemeManagerInterface $theme_manager,
    RouteMatchInterface $route_match,
    LocalTaskManagerInterface $local_task_manager
  ) {
    $this->configFactory = $config_factory;
    $this->themeManager = $theme_manager;
    $this->routeMatch = $route_match;
    $this->localTaskManager = $local_task_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function applies(StandWithUkraineSettingsInterface $settings) {
    if (
      $settings->isSingle() ||
      $settings->getPosition() !== StandWithUkraineSettingsInterface::POSITION_RIGHT
    ) {
      $theme = $this->configFactory->get('system.theme')->get('admin');

      if (!empty($theme)) {
        return $this->themeManager->getActiveTheme()->getName() === $theme;
      }
    }

    return FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public function override(StandWithUkraineSettingsInterface $settings) {
    $route_name = $this->routeMatch->getRouteName();
    $offset = 85;

    if ($route_name !== 'system.batch_page.html') {
      $offset += 32;
      $items = $this->localTaskManager->getLocalTasksForRoute($route_name);

      foreach ($items as $group_delta => &$group) {
        /** @var \Drupal\Core\Menu\LocalTaskInterface $item */
        foreach ($group as $item_delta => $item) {
          if ($item->getRouteName() === $route_name) {
            unset($group[$item_delta]);
          }
        }

        if (empty($group)) {
          unset($items[$group_delta]);
        }
      }

      if (!empty($items)) {
        $offset += 51;
      }
    }

    $settings
      ->setDouble()
      ->setPosition(StandWithUkraineSettingsInterface::POSITION_RIGHT)
      ->setOffset($offset);
  }

}
