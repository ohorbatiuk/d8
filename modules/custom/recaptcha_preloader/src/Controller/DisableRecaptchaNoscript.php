<?php

namespace Drupal\recaptcha_preloader\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Disable reCAPTCHA noscript option.
 */
class DisableRecaptchaNoscript extends ControllerBase {

  /**
   * DisableRecaptchaNoscript constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory service.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * Disable option page.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   The redirect response.
   */
  public function action() {
    $this->configFactory->getEditable('recaptcha.settings')
      ->set('widget.noscript', FALSE)
      ->save();

    $this->messenger()->addStatus($this->t('The %option widget option of the %module module has been disabled.', [
      '%option' => 'Enable fallback for browsers with JavaScript disabled',
      '%module' => 'reCAPTCHA',
    ]));

    return $this->redirect('recaptcha_preloader.admin_settings_form');
  }

  /**
   * Checks access for the disable option page.
   *
   * @return \Drupal\Core\Access\AccessResult
   *   The access result.
   */
  public function access() {
    $config = $this->config('recaptcha.settings');

    return AccessResult::allowedIf(!empty($config->get('widget.noscript')));
  }

}
