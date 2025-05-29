<?php

namespace Drupal\d8_theming\Service;

/**
 * Defines the helper service interface.
 */
interface D8ThemingHelperInterface {

  /**
   * Adds an icon.
   *
   * @param string $name
   *   The name without a prefix.
   * @param string|null $class
   *   (optional) The CSS class. Defaults to NULL.
   */
  public function icon(string $name, ?string $class = NULL): array;

}
