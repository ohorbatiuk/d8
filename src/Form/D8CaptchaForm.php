<?php

namespace Drupal\d8\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\recaptcha\Form\ReCaptchaAdminSettingsForm;

/**
 * Configure reCAPTCHA settings for this profile.
 */
class D8CaptchaForm extends ReCaptchaAdminSettingsForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'install_captcha_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    $form['#title'] = $this->t('reCAPTCHA keys');

    $form['general']['#type'] = 'container';
    $form['general']['recaptcha_verify_hostname']['#access'] = FALSE;
    $form['general']['recaptcha_use_globally']['#access'] = FALSE;

    $form['widget']['#access'] = FALSE;

    return $form;
  }

}
