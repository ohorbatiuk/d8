<?php

declare(strict_types=1);

/**
 * @file
 * Provides installation profile for Drupal 11.
 *
 * It is based on the most common modules and themes that form the basis for
 * creating a stable site.
 */

use Drupal\Core\Form\FormStateInterface;
use Drupal\RecipeKit\Installer\Hooks;

/**
 * Implements hook_form_alter().
 */
function d8_form_alter(
  array &$form,
  FormStateInterface $form_state,
  string $form_id,
): void {
  Hooks::formAlter($form, $form_state, $form_id);
}
