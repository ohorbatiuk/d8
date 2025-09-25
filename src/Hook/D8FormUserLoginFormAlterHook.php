<?php

declare(strict_types=1);

namespace Drupal\d8\Hook;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Url;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Implements hook_form_FORM_ID_alter().
 */
#[Hook('form_user_login_form_alter')]
final readonly class D8FormUserLoginFormAlterHook {

  /**
   * D8FormUserLoginFormAlterHook constructor.
   *
   * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
   *   The request stack.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The configuration factory.
   */
  public function __construct(
    private RequestStack $requestStack,
    private ConfigFactoryInterface $configFactory,
  ) {}

  /**
   * Implements hook_form_FORM_ID_alter().
   */
  public function __invoke(array &$form, FormStateInterface $form_state): void {
    $form['#submit'][] = [$this, 'submit'];
  }

  /**
   * Redirect on the front page after logging in if this page is set.
   */
  public function submit(
    array &$form,
    FormStateInterface $form_state,
  ): void {
    if (!$this->requestStack->getCurrentRequest()->request->has('destination')) {
      $path = $this->configFactory->get('system.site')->get('page.front');

      if (!empty($path)) {
        $form_state->setRedirectUrl(Url::fromUserInput($path));
      }
    }
  }

}
