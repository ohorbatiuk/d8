<?php

namespace Drupal\and\Controller;

use Drupal\service\ConfigFactoryTrait;
use Drupal\service\ControllerBase;
use Drupal\service\EntityTypeManagerTrait;
use Drupal\service\RouteMatchTrait;
use Drupal\service\StateTrait;
use Drupal\service\TitleResolverTrait;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller for pages.
 */
class AndWelcomeController extends ControllerBase {

  use ConfigFactoryTrait;
  use EntityTypeManagerTrait;
  use RouteMatchTrait;
  use StateTrait;
  use TitleResolverTrait;

  /**
   * {@inheritdoc}
   */
  protected function creation(): static {
    return $this
      ->addConfigFactory()
      ->addRouteMatch()
      ->addState()
      ->addTitleResolver();
  }

  /**
   * Re-saves site name and E-mail and shows an introduction page.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *    The request.
   *
   * @see \Drupal\d8\Form\D8ConfigureForm::submitForm()
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
