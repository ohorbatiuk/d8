<?php

namespace Drupal\recaptcha_preloader\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;

/**
 * Configure reCAPTCHA Preloader settings for this site.
 */
class RecaptchaPreloaderSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'recaptcha_preloader_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['recaptcha_preloader.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    if ($this->config('recaptcha.settings')->get('widget.noscript')) {
      $this->messenger()->addWarning($this->t('For using this functionality %module widget option %option must be disabled. For disabling that option click @url.', [
        '%module' => 'reCAPTCHA',
        '%option' => 'Enable fallback for browsers with JavaScript disabled',
        '@url' => Link::createFromRoute($this->t('here'), 'recaptcha_preloader.disable_recaptcha_noscript')->toString(),
      ]));

      return $form;
    }

    $config = $this->config('recaptcha_preloader.settings');

    $form['use'] = [
      '#default_value' => $config->get('use'),
      '#description' => $this->t('Disable submit form elements and show the message when reCAPTCHA is loading.'),
      '#title' => $this->t('Enable'),
      '#type' => 'checkbox',
    ];

    $form['appearance'] = [
      '#default_value' => $config->get('appearance'),
      '#title' => $this->t('Appearance'),
      '#description' => $this->t('Apply reCAPTCHA styling for a message.'),
      '#type' => 'checkbox',
      '#states' => [
        'enabled' => [
          ':input[name="use"]' => [
            'checked' => TRUE,
          ],
        ],
      ],
    ];

    $form['message'] = [
      '#default_value' => $config->get('message'),
      '#description' => $this->t('Special text on the reCAPTCHA place while reCAPTCHA is loading.'),
      '#title' => $this->t('Message'),
      '#type' => 'textfield',
      '#states' => [
        'enabled' => [
          ':input[name="use"]' => [
            'checked' => TRUE,
          ],
        ],
        'required' => [
          ':input[name="appearance"]' => [
            'checked' => TRUE,
          ],
        ],
      ],
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('recaptcha_preloader.settings');

    $config
      ->set('use', $form_state->getValue('use'))
      ->set('appearance', $form_state->getValue('appearance'))
      ->set('message', $form_state->getValue('message'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
