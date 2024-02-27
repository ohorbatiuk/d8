<?php

namespace Drupal\d8_theme\Plugin\Preprocess;

use Drupal\bootstrap\Plugin\Preprocess\PreprocessBase;
use Drupal\bootstrap\Plugin\Preprocess\PreprocessInterface;
use Drupal\bootstrap\Utility\Variables;

/**
 * Pre-processes variables for the "maintenance_page" theme hook.
 *
 * @ingroup plugins_preprocess
 */
#[BootstrapPreprocess('maintenance_page')]
class MaintenancePage extends PreprocessBase implements PreprocessInterface {

  /**
   * {@inheritdoc}
   */
  public function preprocessVariables(Variables $variables): void {
    $variables->rows = [
      'top' => [
        'lightgray',
        'yellow',
        'skyblue',
        'lightgreen',
        'purple',
        'red',
        'blue',
      ],
      'middle' => [
        'blue',
        'black',
        'purple',
        'black',
        'blue',
        'black',
        'white',
      ],
      'bottom' => [
        'darkblue',
        'white',
        'blue',
        'black',
        'black',
        'darkblue',
      ],
    ];
  }

}
