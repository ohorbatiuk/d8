<?php

namespace Drupal\d8\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Controller\TitleResolverInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for d8 pages.
 */
class D8WelcomeController extends ControllerBase {

  /**
   * The current route match.
   */
  private readonly RouteMatchInterface $routeMatch;

  /**
   * The title resolver.
   */
  private readonly TitleResolverInterface $titleResolver;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    $instance = parent::create($container);

    $instance->configFactory = $container->get('config.factory');
    $instance->routeMatch = $container->get('current_route_match');
    $instance->stateService = $container->get('state');
    $instance->titleResolver = $container->get('title_resolver');

    return $instance;
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
    $config = $this->configFactory->getEditable('system.site');

    foreach ((array) $this->state()->get('d8') as $key => $value) {
      $config->set($key, $value);
    }

    $config->save();

    $this->state()->delete('d8');

    $title = $this->titleResolver
      ->getTitle($request, $this->routeMatch->getRouteObject());

    return ['#plain_text' => $title];
  }

}
