<?php

namespace Drupal\d8\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for d8 pages.
 */
class D8WelcomeController extends ControllerBase {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    $instance = parent::create($container)
      ->setStringTranslation($container->get('string_translation'));

    $instance->configFactory = $container->get('config.factory');
    $instance->stateService = $container->get('state');

    return $instance;
  }

  /**
   * Re-saves site name and E-mail and shows an introduction page.
   *
   * @see _d8_install_configure_form_submit()
   */
  public function page(): array {
    $config = $this->configFactory->getEditable('system.site');

    foreach ((array) $this->state()->get('d8') as $key => $value) {
      $config->set($key, $value);
    }

    $config->save();

    $this->state()->delete('d8');

    return ['#plain_text' => $this->t('Welcome')];
  }

}
