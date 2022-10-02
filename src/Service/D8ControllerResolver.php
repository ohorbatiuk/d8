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
  const METHOD = '::handle';

  /**
   * {@inheritdoc}
   */
  public function getControllerFromDefinition($controller, $path = '') {
    if (
      $controller === '\\' . DbUpdateController::class . self::METHOD &&
      $path === ''
    ) {
      $controller = D8UpdateController::class . self::METHOD;
    }

    return parent::getControllerFromDefinition($controller, $path);
  }

}
