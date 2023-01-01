<?php

namespace Drupal\d8_night\Controller;

use Drupal\bootstrap\Bootstrap;
use Drupal\Core\Controller\ControllerBase;
use Drupal\d8_night\Form\D8NightForm;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller routines for D8+ Night routes.
 */
class D8NightController extends ControllerBase {

  /**
   * The cache tags invalidator.
   *
   * @var \Drupal\Core\Cache\CacheTagsInvalidatorInterface
   */
  private $invalidator;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);

    $instance->configFactory = $container->get('config.factory');
    $instance->invalidator = $container->get('cache_tags.invalidator');

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
    $settings = ($theme = Bootstrap::getTheme('d8_theme'))->settings();
    $sub_theme = $this->config('d8_night.settings')->get(D8NightForm::NAME);
    $was = $settings->get(D8NightForm::NAME) === $sub_theme;

    if ($update = ($now = !empty($mode)) !== $was) {
      $settings
        ->set(D8NightForm::NAME, $now ? $sub_theme : 'bootstrap')
        ->clear('cdn_cache')
        ->save();

      if ($tags = $theme->getSettingPlugin(D8NightForm::NAME)->getCacheTags()) {
        $this->invalidator->invalidateTags($tags);
      }

      $theme->getCache('settings')->deleteAll();
    }

    return new JsonResponse(['update' => $update]);
  }

}
