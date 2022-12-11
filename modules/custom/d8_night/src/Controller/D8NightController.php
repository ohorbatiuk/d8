<?php

namespace Drupal\d8_night\Controller;

use Drupal\bootstrap\Bootstrap;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller routines for D8+ Night routes.
 */
class D8NightController extends ControllerBase {

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
    Bootstrap::getTheme('d8_theme')->setSetting(
      'cdn_theme',
      $mode ? 'slate' : 'bootstrap'
    );

    drupal_flush_all_caches();

    return $request->server->has('HTTP_REFERER')
      ? new RedirectResponse($request->server->get('HTTP_REFERER'))
      : $this->redirect('<front>');
  }

}
