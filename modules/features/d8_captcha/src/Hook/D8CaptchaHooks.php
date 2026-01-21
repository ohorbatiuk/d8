<?php

namespace Drupal\d8_captcha\Hook;

use Drupal\Core\Extension\ExtensionPathResolver;
use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\d8\D8HooksBase;

/**
 * Hook implementations for d8_captcha.
 */
final class D8CaptchaHooks extends D8HooksBase {

  /**
   * The URL prefix for the Google reCAPTCHA API script.
   */
  private const string PREFIX = 'https://www.google.com/recaptcha/api.js?';

  /**
   * The triggered function name for the Google reCAPTCHA API script.
   */
  private const string SUFFIX = 'onload=onLoadReCaptcha';

  /**
   * D8CaptchaHooks constructor.
   *
   * @param \Drupal\Core\Extension\ModuleExtensionList $moduleExtensionList
   *   The module extension list.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $moduleHandler
   *   The module handler.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $translation
   *   The string translation.
   * @param \Drupal\Core\Extension\ExtensionPathResolver $extensionPathResolver
   *   The extension path resolver.
   */
  public function __construct(
    private readonly ModuleExtensionList $moduleExtensionList,
    private readonly ModuleHandlerInterface $moduleHandler,
    private readonly TranslationInterface $translation,
    private readonly ExtensionPathResolver $extensionPathResolver,
  ) {
    parent::__construct($moduleExtensionList, $moduleHandler, $translation);
  }

  /**
   * Implements hook_library_info_alter().
   */
  #[Hook('library_info_alter')]
  public function libraryInfoAlter(array &$libraries, string $extension): void {
    if (
      $extension === 'recaptcha' &&
      isset($libraries[$name = "$extension.invisible"])
    ) {
      $libraries[$name]['js'] = [$this->path($extension) => []];
    }
    elseif ($extension === 'recaptcha_preloader') {
      $items = &$libraries['connector']['js'];

      $replacements = [
        'js/base.js' => $this->path($extension),
        self::PREFIX . self::SUFFIX
          => self::PREFIX . 'render=explicit&' . self::SUFFIX,
      ];

      foreach ($replacements as $old => $new) {
        $items[$new] = $items[$old];

        unset($items[$old]);
      }
    }
  }

  /**
   * Generates a JavaScript file path for the specified file name.
   *
   * @param string $name
   *   The name of the JavaScript file (without extension).
   */
  private function path(string $name): string {
    return sprintf(
      '/%s/js/%s.js',
      $this->extensionPathResolver->getPath('module', 'd8_captcha'),
      $name,
    );
  }

}
