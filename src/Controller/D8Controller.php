<?php

namespace Drupal\d8\Controller;

use Drupal\Core\Controller\ControllerBase;
use Laminas\Diactoros\Response\RedirectResponse;

/**
 * Class D8Controller.
 *
 * @package Drupal\d8\Controller
 */
class D8Controller extends ControllerBase {

  /**
   * Switch maintenance mode.
   */
  public function maintenance() {
    $this->state()->set(
      'system.maintenance_mode',
      !$this->state()->get('system.maintenance_mode')
    );

    if (\Drupal::request()->server->has('HTTP_REFERER')) {
      return new RedirectResponse(
        \Drupal::request()->server->get('HTTP_REFERER')
      );
    }

    return $this->redirect('<front>');
  }

}
