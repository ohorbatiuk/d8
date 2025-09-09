<?php

namespace Drupal\d8_captcha\Hook;

use Drupal\Core\Extension\ExtensionPathResolver;
use Drupal\Core\Extension\ModuleExtensionList;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\and\AndHooksBase;

/**
 * Hook implementations for d8_captcha.
 */
final class D8CaptchaHooks extends AndHooksBase {

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
      $path = $this->extensionPathResolver->getPath('module', 'd8_captcha');

      $libraries[$name]['js'] = ["/$path/js/$name.js" => []];
    }
  }

}
