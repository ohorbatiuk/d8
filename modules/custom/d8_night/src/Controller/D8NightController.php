<?php

namespace Drupal\d8_night\Controller;

use Drupal\bootstrap\Bootstrap;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller routines for D8+ Night routes.
 */
class D8NightController extends ControllerBase {

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
    $now = !empty($mode);
    $theme = Bootstrap::getTheme('d8_theme');
    $was = $theme->getSetting('cdn_theme') === 'slate';

    if ($update = $now !== $was) {
      $theme->setSetting('cdn_theme', $now ? 'slate' : 'bootstrap');
      drupal_flush_all_caches();
    }

    return new JsonResponse(['update' => $update]);
  }

}
