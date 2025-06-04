<?php

namespace Drupal\d8_mail\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Url;

/**
 * Hook implementations for d8_mail.
 */
final class D8MailHooks {

  /**
   * Implements hook_help().
   */
  #[Hook('help')]
  public function help(
    string $route_name,
    RouteMatchInterface $route_match,
  ): string {
    $output = '';

    if ($route_name === 'help.page.d8_mail') {
      $output .= "<h3>{t('About')}</h3><p>";

      $url = Url::fromRoute('help.page', ['name' => 'symfony_mailer']);

      $output .= t(
        'Provides a wrapper for the <a href=":url">:name</a> module.',
        [
          ':url' => $url->toString(),
          ':name' => 'Drupal Symfony Mailer',
        ],
      );

      $output .= '</p>';
    }

    return $output;
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
