<?php

namespace Drupal\d8_night\Form;

use Drupal\bootstrap\Bootstrap;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure D8+ Night settings for this site.
 */
class D8NightForm extends ConfigFormBase {

  /**
   * The state.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  private $state;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);

    $instance->state = $container->get('state');

    return $instance;
  }

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
    Bootstrap::getTheme('d8_theme')->getSettingPlugin('cdn_theme')
      ->alterForm(
        $form,
        $form_state->setValue(
          'cdn_theme',
          $this->config($this->getEditableConfigNames()[0])->get('theme')
        )
      );

    $form['cdn']['#type'] = 'container';

    $theme = $this->state->get('d8_night');

    foreach ($form['cdn']['cdn_provider']['cdn_theme']['#options'] as &$themes) {
      if (isset($themes[$theme])) {
        unset($themes[$theme]);
      }
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config($this->getEditableConfigNames()[0])
      ->set('theme', $form_state->getValue('cdn_theme'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
