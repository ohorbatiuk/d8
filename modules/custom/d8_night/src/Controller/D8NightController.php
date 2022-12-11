<?php

namespace Drupal\d8_night\Controller;

use Drupal\bootstrap\Bootstrap;
use Drupal\d8\Controller\D8MaintenanceController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller routines for D8+ Night routes.
 */
class D8NightController extends D8MaintenanceController {

  /**
   * TRUE when will be switching to dark mode.
   *
   * @var bool
   */
  private $dark;

  /**
   * Change the Bootstrap CDN theme according to light or dark mode.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   Information about the current HTTP request.
   * @param int $mode
   *   The one from the two following values indicates what mode will be
   *   activated:
   *   - '1': Dark mode.
   *   - '0': Light mode.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   The redirect response.
   */
  public function switch(Request $request, $mode) {
    $this->dark = !empty($mode);

    return $this->action($request);
  }

  /**
   * {@inheritdoc}
   */
  protected function doAction() {
    Bootstrap::getTheme('d8_theme')->setSetting(
      'cdn_theme',
      $this->dark ? 'slate' : 'bootstrap'
    );

    drupal_flush_all_caches();
  }

}
