<?php

namespace Drupal\d8_captcha;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;
use Drupal\d8_captcha\Service\D8CaptchaRecaptchaPreloaderHelper;

/**
 * Class D8CaptchaServiceProvider.
 *
 * @package Drupal\d8_captcha
 */
class D8CaptchaServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    if ($container->hasDefinition('recaptcha_preloader.helper')) {
      $container->getDefinition('recaptcha_preloader.helper')
        ->setClass(D8CaptchaRecaptchaPreloaderHelper::class);
    }
  }

}
