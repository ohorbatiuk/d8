<?php

namespace Drupal\d8\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\recaptcha\Form\ReCaptchaAdminSettingsForm;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure reCAPTCHA settings for this profile.
 */
class D8CaptchaForm extends ReCaptchaAdminSettingsForm {

  /**
   * Constructs a D8CaptchaForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The string translation service.
   */
  public function __construct(
    ConfigFactoryInterface $config_factory,
    TranslationInterface $string_translation
  ) {
    parent::__construct($config_factory);

    $this->setStringTranslation($string_translation);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('string_translation')
    );
  }

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
