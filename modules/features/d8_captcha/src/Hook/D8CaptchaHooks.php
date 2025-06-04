<?php

namespace Drupal\d8_captcha\Hook;

use Drupal\Core\Extension\ExtensionPathResolver;
use Drupal\Core\Hook\Attribute\Hook;

/**
 * Hook implementations for d8_captcha.
 */
final readonly class D8CaptchaHooks {

  /**
   * D8CaptchaHooks constructor.
   *
   * @param \Drupal\Core\Extension\ExtensionPathResolver $extensionPathResolver
   *   The extension path resolver.
   */
  public function __construct(
    private ExtensionPathResolver $extensionPathResolver,
  ) {}

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
