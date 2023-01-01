<?php

namespace Drupal\d8_night\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure D8+ Night settings for this site.
 */
class D8NightForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['d8_night.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'd8_night_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['theme'] = [
      '#type' => 'select',
      '#title' => $this->t('Theme'),
      '#description' => $this->t('Bootstrap CDN theme for dark mode.'),
      '#options' => [],
      '#default_value' => $this->config($this->getEditableConfigNames()[0])
        ->get('theme'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config($this->getEditableConfigNames()[0])
      ->set('theme', $form_state->getValue('theme'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
