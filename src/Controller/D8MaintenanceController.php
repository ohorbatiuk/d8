<?php

namespace Drupal\d8\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\service\StateTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller routines for installation profile routes.
 */
class D8MaintenanceController extends ControllerBase {

  use StateTrait;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return parent::create($container)->setState($container);
  }

  /**
   * Switch maintenance mode.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   Information about the current HTTP request.
   */
  public function action(Request $request): RedirectResponse {
    $this->state()->set(
      $key = 'system.maintenance_mode',
      !$this->state()->get($key),
    );

    return $request->server->has($key = 'HTTP_REFERER')
      ? new RedirectResponse($request->server->get($key))
      : $this->redirect('<front>');
  }

}
