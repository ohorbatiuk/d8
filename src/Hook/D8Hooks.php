<?php

declare(strict_types=1);

namespace Drupal\d8\Hook;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\RecipeKit\Installer\Hooks;

/**
 * Hook implementations for d8.
 */
final class D8Hooks {

  /**
   * Implements hook_form_alter().
   */
  #[Hook('form_alter')]
  public function formAlter(
    array &$form,
    FormStateInterface $form_state,
    string $form_id,
  ): void {
    Hooks::formAlter($form, $form_state, $form_id);
  }

}
