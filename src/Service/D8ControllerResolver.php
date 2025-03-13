<?php

namespace Drupal\d8\Service;

use Drupal\Core\Controller\ControllerResolver;
use Drupal\d8\Controller\D8UpdateController;
use Drupal\system\Controller\DbUpdateController;

/**
 * ControllerResolver to enhance controllers beyond Symfony's basic handling.
 */
class D8ControllerResolver extends ControllerResolver {

  /**
   * The method name for building a database update page.
   */
  protected const string METHOD = '::handle';

  /**
   * {@inheritdoc}
   */
  public function getControllerFromDefinition(
    mixed $controller,
    $path = '',
  ): mixed {
    if (
      $controller === '\\' . DbUpdateController::class . static::METHOD &&
      $path === ''
    ) {
      $controller = D8UpdateController::class . static::METHOD;
    }

    return parent::getControllerFromDefinition($controller, $path);
  }

}
