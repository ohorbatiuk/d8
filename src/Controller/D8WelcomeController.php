<?php

namespace Drupal\d8\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\service\ConfigFactoryTrait;
use Drupal\service\EntityTypeManagerTrait;
use Drupal\service\RouteMatchTrait;
use Drupal\service\StateTrait;
use Drupal\service\TitleResolverTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for d8 pages.
 */
class D8WelcomeController extends ControllerBase {

  use ConfigFactoryTrait;
  use EntityTypeManagerTrait;
  use RouteMatchTrait;
  use StateTrait;
  use TitleResolverTrait;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    return parent::create($container)
      ->setContainer($container)
      ->setConfigFactory()
      ->setRouteMatch()
      ->setState()
      ->setTitleResolver();
  }

  /**
   * Re-saves site name and E-mail and shows an introduction page.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *    The request.
   *
   * @see _d8_install_configure_form_submit()
   */
  public function page(Request $request): array {
    $config = $this->configFactory()->getEditable('system.site');

    foreach ((array) $this->state()->get('d8') as $key => $value) {
      $config->set($key, $value);
    }

    $config->save();

    $this->state()->delete('d8');

    $title = $this->titleResolver()
      ->getTitle($request, $this->routeMatch()->getRouteObject());

    return ['#plain_text' => $title];
  }

}
