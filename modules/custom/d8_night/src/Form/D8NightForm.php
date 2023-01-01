<?php

namespace Drupal\d8_night\Form;

use Drupal\bootstrap\Bootstrap;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

/**
 * Configure D8+ Night settings for this site.
 */
class D8NightForm extends ConfigFormBase {

  /**
   * The Bootstrap CDN theme setting name.
   */
  const NAME = 'cdn_theme';

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
    Bootstrap::getTheme('d8_theme')->getSettingPlugin(self::NAME)
      ->alterForm(
        $form,
        $form_state->setValue(
          self::NAME,
          $this->config($this->getEditableConfigNames()[0])->get(self::NAME)
        )
      );

    foreach (Element::children($form) as $key) {
      $form[$key]['#type'] = 'container';
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config($this->getEditableConfigNames()[0])
      ->set(self::NAME, $form_state->getValue(self::NAME))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
