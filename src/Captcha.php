<?php

namespace Drupal\d8;

use Drupal\Core\Form\FormStateInterface;
use Drupal\recaptcha\Form\ReCaptchaAdminSettingsForm;

/**
 * Configure reCAPTCHA settings for this profile.
 */
class Captcha extends ReCaptchaAdminSettingsForm {

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

    $section = $form['general'];

    unset($form['general'], $form['widget']);

    $form['#title'] = $this->t('reCAPTCHA keys');

    return $form + $section;
  }

}
