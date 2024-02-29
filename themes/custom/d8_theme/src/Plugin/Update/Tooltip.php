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
   * The batch process callback for the update.
   *
   * This is the bulk of the update plugin. Be careful to no fill it will a
   * lot of heavily intensive processing. If you need to do a lot of things,
   * split it up into multiple updates so the Batch API can handle it.
   *
   * You can throw an exception from this method in case your processing fails.
   * Its message will be conveyed to the user to indicate what went wrong. If
   * the update has failed, but do not wish to throw an exception, simply
   * return FALSE and a generic "Update failed" message will appear.
   *
   * @param \Drupal\bootstrap\Theme $theme
   *   The theme that the update is being applied to.
   * @param array $context
   *   The Batch API context array, passed by reference. Note: be very careful
   *   to not store any instances created from a theme. The Batch API stores
   *   this in the DB between each "request" and it may not be able to fully
   *   reconstitute the object upon un-serialization. If you need to pass a
   *   theme object between instances, you should instead use an identifier
   *   (string) that can be used to reconstitute the object when needed.
   *
   * @return bool
   *   FALSE if the update failed, otherwise any other return will be
   *   interpreted as TRUE.
   */
  public function update(Theme $theme, array &$context): bool {
    $theme->setSetting('tooltip_placement', 'auto top');

    return TRUE;
  }

}
