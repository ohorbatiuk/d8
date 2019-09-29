<?php

namespace Drupal\d8_theme\Plugin\Update;

use Drupal\bootstrap\Plugin\Update\UpdateBase;
use Drupal\bootstrap\Theme;

/**
 * @BootstrapUpdate(
 *   id = 8001,
 *   label = @Translation("Tooltip default position"),
 *   description = @Translation("Show tooltips above field by default."),
 *   private = TRUE,
 * )
 */
class Tooltip extends UpdateBase {

  /**
   * {@inheritdoc}
   */
  public function update(Theme $theme, array &$context) {
    $theme->setSetting('tooltip_placement', 'auto top');

    return TRUE;
  }

}
