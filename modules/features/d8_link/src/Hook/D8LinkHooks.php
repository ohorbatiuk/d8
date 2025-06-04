<?php

namespace Drupal\d8_link\Hook;

use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Hook\Attribute\Hook;

/**
 * Hook implementations for d8_link.
 */
final class D8LinkHooks {

  /**
   * Implements hook_page_attachments().
   */
  #[Hook('page_attachments')]
  public function pageAttachments(array &$attachments): void {
    $attachments['#attached']['library'][] = 'd8_link/base';
  }

  /**
   * Implements hook_page_attachments_alter().
   */
  #[Hook('page_attachments_alter')]
  public function pageAttachmentsAlter(&$attachments): void {
    $parents = ['#attached', 'drupalSettings', 'data', 'extlink'];

    if (NestedArray::keyExists($attachments, $parents)) {
      $parents[] = 'extTargetAppendNewWindowLabel';

      NestedArray::setValue($attachments, $parents, '');
    }
  }

}
