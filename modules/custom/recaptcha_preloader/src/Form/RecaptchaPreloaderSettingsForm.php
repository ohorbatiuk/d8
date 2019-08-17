<?php

namespace Drupal\recaptcha_preloader\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure reCAPTCHA Preloader settings for this site.
 */
class RecaptchaPreloaderSettingsForm extends ConfigFormBase {

  /**
   * Constructs a RecaptchaPreloaderSettingsForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The string translation service.
   */
  public function __construct(
    ConfigFactoryInterface $config_factory,
    MessengerInterface $messenger,
    TranslationInterface $string_translation
  ) {
    parent::__construct($config_factory);

    $this
      ->setStringTranslation($string_translation)
      ->setMessenger($messenger);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('messenger'),
      $container->get('string_translation')
    );
  }

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

    $config = $this->config($this->getEditableConfigNames()[0]);

    $form['use'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable'),
      '#description' => $this->t('Disable submit form elements and show the message when reCAPTCHA is loading.'),
      '#default_value' => $config->get('use'),
    ];

    $form['appearance'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Appearance'),
      '#description' => $this->t('Apply reCAPTCHA styling for a message.'),
      '#default_value' => $config->get('appearance'),
      '#states' => [
        'enabled' => [
          ':input[name="use"]' => [
            'checked' => TRUE,
          ],
        ],
      ],
    ];

    $form['message'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Message'),
      '#description' => $this->t('Special text on the reCAPTCHA place while reCAPTCHA is loading.'),
      '#default_value' => $config->get('message'),
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
    $config = $this->config($this->getEditableConfigNames()[0]);

    foreach (['use', 'appearance', 'message'] as $name) {
      $config->set($name, $form_state->getValue($name));
    }

    $config->save();

    parent::submitForm($form, $form_state);
  }

}
