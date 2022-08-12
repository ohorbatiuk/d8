<?php

namespace Drupal\d8_standwithukraine;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Theme\ThemeManagerInterface;

/**
 * Defines the helper service.
 */
class D8StandWithUkraineHelper implements D8StandWithUkraineHelperInterface {

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
   * D8StandWithUkraineHelper constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   * @param \Drupal\Core\Theme\ThemeManagerInterface $theme_manager
   *   The theme.
   */
  public function __construct(
    ConfigFactoryInterface $config_factory,
    ThemeManagerInterface $theme_manager
  ) {
    $this->configFactory = $config_factory;
    $this->themeManager = $theme_manager;
  }

  /**
   * {@inheritdoc}
   */
  public function theme() {
    $theme = $this->configFactory->get('system.theme')->get('admin');

    if (!empty($theme)) {
      return $this->themeManager->getActiveTheme()->getName() === $theme;
    }

    return FALSE;
  }

}
