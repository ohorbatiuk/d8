<?php

namespace Drupal\d8_night\Controller;

use Drupal\bootstrap\Bootstrap;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller routines for D8+ Night routes.
 */
class D8NightController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);

    $instance->configFactory = $container->get('config.factory');

    return $instance;
  }

  /**
   * Change the Bootstrap CDN theme according to light or dark mode.
   *
   * @param int $mode
   *   The one from the two following values indicates what mode will be
   *   activated:
   *   - '1': Dark mode.
   *   - '0': Light mode.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   The JSON response.
   */
  public function switch($mode) {
    $theme = Bootstrap::getTheme('d8_theme');
    $sub_theme = $this->config('d8_night.settings')->get('theme');
    $was = $theme->getSetting('cdn_theme') === $sub_theme;

    if ($update = ($now = !empty($mode)) !== $was) {
      $theme->setSetting('cdn_theme', $now ? $sub_theme : 'bootstrap');
      drupal_flush_all_caches();
    }

    return new JsonResponse(['update' => $update]);
  }

}
