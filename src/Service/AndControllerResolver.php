<?php

namespace Drupal\and\Service;

use Drupal\Core\Controller\ControllerResolver;
use Drupal\and\Controller\AndUpdateController;
use Drupal\system\Controller\DbUpdateController;

/**
 * ControllerResolver to enhance controllers beyond Symfony's basic handling.
 */
class AndControllerResolver extends ControllerResolver {

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
      $controller = AndUpdateController::class . static::METHOD;
    }

    return parent::getControllerFromDefinition($controller, $path);
  }

}
