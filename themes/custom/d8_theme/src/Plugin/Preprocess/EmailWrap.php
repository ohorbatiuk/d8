<?php

namespace Drupal\d8_theme\Plugin\Preprocess;

use Drupal\bootstrap\Plugin\Preprocess\PreprocessBase;
use Drupal\bootstrap\Utility\Variables;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Pre-processes variables for the "email_wrap" theme hook.
 *
 * @ingroup plugins_preprocess
 *
 * @BootstrapPreprocess("email_wrap")
 */
class EmailWrap extends PreprocessBase implements ContainerFactoryPluginInterface {

  /**
   * The configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  private $configFactory;

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  private $dateFormatter;

  /**
   * The time service.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  private $time;

  /**
   * The scheme and HTTP host.
   *
   * @var string
   */
  private $host;

  /**
   * {@inheritdoc}
   */
  public static function create(
    ContainerInterface $container,
    array $configuration,
    $plugin_id,
    $plugin_definition
  ) {
    $instance = (new static($configuration, $plugin_id, $plugin_definition))
      ->setStringTranslation($container->get('string_translation'));

    $instance->configFactory = $container->get('config.factory');
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->time = $container->get('datetime.time');

    $instance->host = $container->get('request_stack')
      ->getCurrentRequest()
      ->getSchemeAndHttpHost();

    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function preprocessVariables(Variables $variables) {
    parent::preprocessVariables($variables);

    if (!$variables->is_html) {
      return;
    }

    $variables->logo = $this->host . theme_get_setting('logo')['url'];

    /** @var \Drupal\symfony_mailer\InternalEmailInterface $email */
    $email = $variables->email;

    if (
      is_array($parameter = $email->getParam('legacy_message')) &&
      isset($parameter['params']['account'])
    ) {
      $account = $parameter['params']['account'];
    }
    else {
      $account = $email->getAccount();
    }

    $variables->welcome = $this->t(
      'Hello @recipient-name,',
      ['@recipient-name' => $account->getDisplayName()]
    );

    $variables->year = $this->dateFormatter->format(
      $this->time->getRequestTime(),
      'html_year'
    );

    $variables->site_name = $this->configFactory->get('system.site')
      ->get('name');
  }

}
