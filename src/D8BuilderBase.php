<?php

namespace Drupal\d8;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\service\ConfigFactoryTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a base for hook wrappers.
 *
 * @internal
 *    This is an internal utility class wrapping hook implementations.
 */
abstract class D8BuilderBase implements ContainerInjectionInterface {

  use ConfigFactoryTrait;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return (new static())->addConfigFactory($container);
  }

}
