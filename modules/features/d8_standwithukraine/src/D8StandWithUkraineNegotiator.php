<?php

namespace Drupal\d8_standwithukraine;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Menu\LocalTaskManagerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Theme\ThemeManagerInterface;
use Drupal\standwithukraine\Position;
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
   *   The theme manager.
   * @param \Drupal\Core\Routing\RouteMatchInterface $routeMatch
   *   The currently active route match object.
   * @param \Drupal\Core\Menu\LocalTaskManagerInterface $localTaskManager
   *   The local task manager.
   */
  public function __construct(
    protected ConfigFactoryInterface $configFactory,
    protected ThemeManagerInterface $themeManager,
    protected RouteMatchInterface $routeMatch,
    protected LocalTaskManagerInterface $localTaskManager
  ) {}

  /**
   * {@inheritdoc}
   */
  public function applies(StandWithUkraineSettingsInterface $settings): bool {
    if ($settings->isSingle() || $settings->getPosition() !== Position::Right) {
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
      ->setText('Stand With' . PHP_EOL . 'Ukraine')
      ->setDouble()
      ->setSizes(FALSE, TRUE)
      ->setPosition(Position::Right)
      ->setOffset($offset);
  }

}
