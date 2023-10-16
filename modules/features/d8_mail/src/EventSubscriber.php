<?php

namespace Drupal\d8_mail;

use Drupal\Component\Utility\EmailValidatorInterface;
use Drupal\Core\Config\ConfigCrudEvent;
use Drupal\Core\Config\ConfigEvents;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Link;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use Drupal\Core\Logger\LoggerChannelTrait;
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

  use LoggerChannelTrait;
  use MessengerTrait;
  use StringTranslationTrait;

  /**
   * The common part of the status message and database log message.
   */
  private const MESSAGE = ' has been changed from %old to %new.';

  /**
   * The link label of the status message and database log message.
   */
  private const LABEL = 'The site E-mail address';

  /**
   * The prefix of the status message where the link will be inserted.
   */
  private const KEY = '@link';

  /**
   * The base E-mail address of the site.
   */
  private string $siteAddress;

  /**
   * The E-mail address from the username field of the mailer transport entity.
   */
  private string $mailerAddress;

  /**
   * EventSubscriber constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager.
   * @param \Drupal\Component\Utility\EmailValidatorInterface $emailValidator
   *   The E-mail validator.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The configuration factory.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $translation
   *   The string translation.
   * @param \Drupal\Core\Logger\LoggerChannelFactoryInterface $logger_factory
   *   The logger channel factory.
   */
  public function __construct(
    private readonly EntityTypeManagerInterface $entityTypeManager,
    private readonly EmailValidatorInterface $emailValidator,
    private readonly ConfigFactoryInterface $configFactory,
    MessengerInterface $messenger,
    TranslationInterface $translation,
    LoggerChannelFactoryInterface $logger_factory
  ) {
    $this
      ->setStringTranslation($translation)
      ->setLoggerFactory($logger_factory)
      ->setMessenger($messenger);
  }

  /**
   * Gets parameters set of message.
   *
   * @param string $key
   *   The link parameter name.
   * @param string $text
   *   The link label.
   */
  private function context(string $key, string $text): array {
    return [
      $key => Link::createFromRoute(
        $this->t($text),
        'system.site_information_settings',
        options: ['fragment' => 'edit-site-mail'],
      )->toString(),
      '%old' => $this->siteAddress,
      '%new' => $this->mailerAddress,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [ConfigEvents::SAVE => [['onConfigSave']]];
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
  public function onConfigSave(ConfigCrudEvent $event): void {
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

      $this->mailerAddress = $configuration->getConfiguration()['user'];

      if (
        empty($this->mailerAddress) ||
        !$this->emailValidator->isValid($this->mailerAddress)
      ) {
        return;
      }

      $site_config = $this->configFactory->getEditable('system.site');
      $this->siteAddress = $site_config->get($key = 'mail');

      if ($this->mailerAddress === $this->siteAddress) {
        return;
      }

      $site_config->set($key, $this->mailerAddress)->save();

      $this->messenger->addStatus($this->t(
        self::KEY . self::MESSAGE,
        $this->context(self::KEY, self::LABEL),
      ));

      $this->getLogger('d8_mail')->info(
        self::LABEL . self::MESSAGE,
        $this->context(substr(self::KEY, 1), 'Edit'),
      );
    }
  }

}
