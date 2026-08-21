<?php

namespace Drupal\d8_ban\Hook;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\d8\D8HooksBase;

/**
 * Hook implementations for d8_ban.
 */
final class D8BanHooks extends D8HooksBase {

  /**
   * {@inheritdoc}
   */
  protected function module(): ?string {
    return 'autoban';
  }

  /**
   * Implements hook_form_FORM_ID_alter().
   *
   * @see \Drupal\dblog\Form\DblogFilterForm::buildForm()
   */
  #[Hook('form_dblog_filter_form_alter')]
  public function formDblogFilterFormAlter(
    array &$form,
    FormStateInterface $form_state,
    string $form_id,
  ): void {
    $form['filters']['#open'] = FALSE;
  }

}
