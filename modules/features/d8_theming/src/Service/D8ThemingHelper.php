<?php

namespace Drupal\d8_theming\Service;

/**
 * Defines the helper service.
 */
class D8ThemingHelper implements D8ThemingHelperInterface {

  /**
   * {@inheritdoc}
   */
  public function icon(string $name, ?string $class = NULL): array {
    return [
      '#type' => 'html_tag',
      '#tag' => 'i',
      '#attributes' => [
        'class' => ["bi-$name", ...((array) $class)],
      ],
      '#attached' => [
        'library' => [
          'd8_theming/icon',
        ],
      ],
    ];
  }

}
