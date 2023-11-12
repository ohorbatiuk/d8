<?php

namespace Drupal\d8\Form;

use Drupal\Core\Extension\ModuleInstallerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Drupal\recaptcha\Form\ReCaptchaAdminSettingsForm;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure reCAPTCHA settings for this profile.
 */
class D8CaptchaForm extends ReCaptchaAdminSettingsForm {

  /**
   * The module installer.
   */
  protected ModuleInstallerInterface $moduleInstaller;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): static {
    $instance = parent::create($container)
      ->setStringTranslation($container->get('string_translation'));

    $instance->moduleInstaller = $container->get('module_installer');

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'install_captcha_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return [...parent::getEditableConfigNames(), 'recaptcha_preloader.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form = parent::buildForm($form, $form_state);

    $form['#title'] = $this->t('reCAPTCHA settings');

    $form['widget']['#type'] = 'container';
    $form['widget']['#weight'] = 0;

    $field = &$form['widget']['recaptcha_size'];
    $field['#type'] = 'radios';

    if (!empty($field['#default_value'])) {
      $field['#options'][''] = $this->t('Normal');

      $field['#options'][$field['#default_value']] .= sprintf(
        ' (%s)',
        $this->t('default'),
      );
    }

    foreach (Element::children($form['widget']) as $key) {
      if ($key !== 'recaptcha_size') {
        $form['widget'][$key]['#access'] = FALSE;
      }
    }

    $form['general']['#type'] = 'container';
    $form['general']['#weight'] = 1;

    foreach (Element::children($form['general']) as $key) {
      if (!in_array($key, ['recaptcha_site_key', 'recaptcha_secret_key'])) {
        $form['general'][$key]['#access'] = FALSE;
      }
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    parent::submitForm($form, $form_state);

    if ($form_state->getValue('recaptcha_size') !== 'invisible') {
      $this->moduleInstaller->install(['recaptcha_preloader']);
    }

    drupal_flush_all_caches();
  }

}
