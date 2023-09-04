<?php

namespace Drupal\d8_mail\Entity;

use Drupal\symfony_mailer\Entity\MailerTransport as MailerTransportBase;

/**
 * Defines a Mailer Transport configuration entity class.
 */
class MailerTransport extends MailerTransportBase {

  /**
   * {@inheritdoc}
   */
  public function setAsDefault() {
    parent::setAsDefault();

    /** @var \Drupal\Component\Plugin\LazyPluginCollection $configuration */
    $configuration = $this->getPluginCollections()['configuration'];

    if (!empty($new_address = $configuration->getConfiguration()['user'])) {
      /** @var \Drupal\Component\Utility\EmailValidatorInterface $validator */
      $validator = \Drupal::service('email.validator');

      if ($validator->isValid($new_address)) {
        $config = \Drupal::configFactory()->getEditable('system.site');

        if (($old_address = $config->get('mail')) !== $new_address) {
          $config->set('mail', $new_address)->save();

          \Drupal::messenger()->addStatus(t(
            'The site E-mail address has been changed from %old to %new.',
            [
              '%old' => $old_address,
              '%new' => $new_address,
            ]
          ));
        }
      }
    }

    return $this;
  }

}
