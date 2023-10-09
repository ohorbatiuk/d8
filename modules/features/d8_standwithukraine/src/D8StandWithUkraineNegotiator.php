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
   * D8StandWithUkraineNegotiator constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The configuration factory.
   * @param \Drupal\Core\Theme\ThemeManagerInterface $themeManager
   *   The theme.
   * @param \Drupal\Core\Routing\RouteMatchInterface $routeMatch
   *   The currently active route match object.
   * @param \Drupal\Core\Menu\LocalTaskManagerInterface $localTaskManager
   *   The local task manger.
   */
  public function __construct(
    private readonly ConfigFactoryInterface $configFactory,
    private readonly ThemeManagerInterface $themeManager,
    private readonly RouteMatchInterface $routeMatch,
    private readonly LocalTaskManagerInterface $localTaskManager
  ) {}

  /**
   * {@inheritdoc}
   */
  public function applies(StandWithUkraineSettingsInterface $settings): bool {
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
  public function override(StandWithUkraineSettingsInterface $settings): void {
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
      ->setText(StandWithUkraineSettingsInterface::TEXT)
      ->setDouble()
      ->setSizes(FALSE, TRUE)
      ->setPosition(StandWithUkraineSettingsInterface::POSITION_RIGHT)
      ->setOffset($offset);
  }

}
