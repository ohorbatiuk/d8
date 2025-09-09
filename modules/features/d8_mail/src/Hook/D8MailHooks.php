<?php

namespace Drupal\d8_mail\Hook;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\and\AndHooksBase;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Hook implementations for d8_mail.
 */
final class D8MailHooks extends AndHooksBase {

  /**
   * D8MailHooks constructor.
   *
   * @param \Drupal\Core\Extension\ModuleExtensionList $moduleExtensionList
   *   The module extension list.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   *   The module handler.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $translation
   *   The string translation.
   * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
   *   The request stack.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $dateFormatter
   *   The date formatter.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   The time.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The configuration factory.
   */
  public function __construct(
    ModuleExtensionList $moduleExtensionList,
    ModuleHandlerInterface $moduleHandler,
    TranslationInterface $translation,
    private readonly RequestStack $requestStack,
    private readonly DateFormatterInterface $dateFormatter,
    private readonly TimeInterface $time,
    private readonly ConfigFactoryInterface $configFactory,
  ) {
    parent::__construct($moduleExtensionList, $moduleHandler, $translation);
  }

  /**
   * Implements hook_library_info_alter().
   */
  #[Hook('library_info_alter')]
  public function libraryInfoAlter(array &$libraries, string $extension): void {
    if ($extension === 'symfony_mailer') {
      unset($libraries['test']['css']['theme']['css/test.email.css']);
    }
  }

  /**
   * Implements hook_preprocess_HOOK().
   */
  #[Hook('preprocess_email_wrap__html')]
  public function preprocessHtmlEmailWrapper(array &$variables): void {
    $variables['logo'] =
      $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost() .
      theme_get_setting('logo')['url'];

    /** @var \Drupal\symfony_mailer\InternalEmailInterface $email */
    $email = $variables['email'];

    $parameter = (array) $email->getParam('legacy_message');
    $account = $parameter['params']['account'] ?? $email->getAccount();

    $variables['welcome'] = $this->t(
      'Hello @recipient-name,',
      ['@recipient-name' => $account->getDisplayName()],
    );

    $variables['year'] = $this->dateFormatter
      ->format($this->time->getRequestTime(), 'html_year');

    $variables['site_name'] = $this->configFactory->get('system.site')
      ->get('name');
  }

  /**
   * Implements hook_theme().
   */
  #[Hook('theme')]
  public function theme(
    array $existing,
    string $type,
    string $theme,
    string $path,
  ): array {
    $name = 'email_wrap';
    return ["{$name}__html" => ['base hook' => $name]];
  }

  /**
   * Implements hook_theme_suggestions_HOOK_alter().
   */
  #[Hook('theme_suggestions_email_wrap_alter')]
  public function themeSuggestionsEmailWrapAlter(
    array &$suggestions,
    array $variables,
  ): void {
    if ($variables['is_html']) {
      $suggestions[] = "{$variables['theme_hook_original']}__html";
    }
  }

}
