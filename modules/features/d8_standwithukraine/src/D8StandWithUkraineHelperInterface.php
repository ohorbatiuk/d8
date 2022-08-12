<?php

namespace Drupal\d8_standwithukraine;

/**
 * Defines the helper service interface.
 */
interface D8StandWithUkraineHelperInterface {

  /**
   * Check if the active theme is the admin theme.
   *
   * @return bool
   *   TRUE if so.
   */
  public function theme();

}
