<?php

namespace Drupal\and;

use Drupal\service\ClassResolverBase;
use Drupal\service\ConfigFactoryTrait;

/**
 * Provides a base for hook wrappers.
 *
 * @internal
 *   This is an internal utility class wrapping hook implementations.
 */
abstract class AndBuilderBase extends ClassResolverBase {

  use ConfigFactoryTrait;

  /**
   * {@inheritdoc}
   */
  protected function creation(): static {
    return $this->addConfigFactory();
  }

}
