<?php

namespace Drupal\d8_mail;

use Drupal\Component\Utility\EmailValidatorInterface;
use Drupal\Core\Config\ConfigCrudEvent;
use Drupal\Core\Config\ConfigEvents;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Link;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\symfony_mailer\MailerTransportInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Config import subscriber for config save event.
 */
class EventSubscriber implements EventSubscriberInterface {

  use StringTranslationTrait;
  use MessengerTrait;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private $entityTypeManager;

  /**
   * The E-mail validator.
   *
   * @var \Drupal\Component\Utility\EmailValidatorInterface
   */
  private $emailValidator;

  /**
   * The configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  private $configFactory;

  /**
   * EventSubscriber constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Component\Utility\EmailValidatorInterface $email_validator
   *   The E-mail validator.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $translation
   *   The string translation.
   */
  public function __construct(
    EntityTypeManagerInterface $entity_type_manager,
    EmailValidatorInterface $email_validator,
    ConfigFactoryInterface $config_factory,
    MessengerInterface $messenger,
    TranslationInterface $translation
  ) {
    $this
      ->setStringTranslation($translation)
      ->setMessenger($messenger);

    $this->entityTypeManager = $entity_type_manager;
    $this->emailValidator = $email_validator;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[ConfigEvents::SAVE][] = ['onConfigSave'];
    return $events;
  }

  /**
   * Changes site E-mail address based on default transporter.
   *
   * Reacts to default mailer transport change and records username of default
   * mailer transport as the site E-mail address.
   *
   * @param \Drupal\Core\Config\ConfigCrudEvent $event
   *   The event.
   */
  public function onConfigSave(ConfigCrudEvent $event) {
    $mailer_config = $event->getConfig();

    if (
      $mailer_config->getName() !== 'symfony_mailer.settings' ||
      !$event->isChanged($key = 'default_transport')
    ) {
      return;
    }

    $entity = $this->entityTypeManager->getStorage('mailer_transport')
      ->load($mailer_config->get($key));

    if ($entity instanceof MailerTransportInterface) {
      /** @var \Drupal\Component\Plugin\LazyPluginCollection $configuration */
      $configuration = $entity->getPluginCollections()['configuration'];

      if (
        !empty($mailer_address = $configuration->getConfiguration()['user']) &&
        $this->emailValidator->isValid($mailer_address)
      ) {
        $site_config = $this->configFactory->getEditable('system.site');
        $site_address = $site_config->get($key = 'mail');

        if ($mailer_address !== $site_address) {
          $site_config->set($key, $mailer_address)->save();

          $this->messenger->addStatus($this->t(
            '@link has been changed from %old to %new.',
            [
              '@link' => Link::createFromRoute(
                $this->t('The site E-mail address'),
                'system.site_information_settings',
                [],
                ['fragment' => 'edit-site-mail']
              )->toString(),
              '%old' => $site_address,
              '%new' => $mailer_address,
            ]
          ));
        }
      }
    }
  }

}
