<?php

namespace Drupal\d8\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Installer\Form\SiteConfigureForm;

/**
 * Provides the site configuration form.
 *
 * @phpstan-ignore-next-line
 */
class D8ConfigureForm extends SiteConfigureForm {

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    if ($form_state->getValue('enable_update_status_module')) {
      if (!$form_state->getValue('enable_update_status_emails')) {
        $this->resetConfigFactory();
      }

      $this->config('update.settings')->set('news', TRUE)->save(TRUE);
    }
  }

}
