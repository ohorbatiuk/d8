<?php

namespace Drupal\d8_theme\Hook;

use Drupal\Core\Extension\ThemeSettingsProvider;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Template\Attribute;
use Drupal\d8_theming\Service\D8ThemingHelperInterface;

/**
 * Implements hook_preprocess_HOOK().
 */
#[Hook('preprocess_form_element')]
final readonly class D8ThemePreprocessFormElementHook {

  /**
   * D8ThemePreprocessFormElementHook constructor.
   *
   * @param \Drupal\Core\Extension\ThemeSettingsProvider $themeSettingsProvider
   *   The theme settings helper.
   * @param \Drupal\d8_theming\Service\D8ThemingHelperInterface $helper
   *   The D8+ Theming helper.
   */
  public function __construct(
    private ThemeSettingsProvider $themeSettingsProvider,
    private D8ThemingHelperInterface $helper,
  ) {}

  /**
   * Implements hook_preprocess_HOOK().
   *
   * @see d8_theme_preprocess_form_element()
   */
  public function __invoke(array &$variables): void {
    if (
      !in_array($type = $variables['type'], ['checkbox', 'radio']) ||
      !empty($this->themeSettingsProvider->getSetting('bootstrap_checkbox'))
    ) {
      return;
    }

    $variables['#attached']['library'][] = 'd8_theme/checkbox-or-radio';

    $icons = $type === 'checkbox' ? ['check', 'square'] : ['record', 'circle'];

    $variables['#attached']['drupalSettings']['d8Theme'][$type] = array_map(
      fn(string $icon): string => "bi-$icon",
      $icons = [$icons[1], "$icons[0]-$icons[1]"],
    );

    $variables['attributes']['class'][] = 'ps-0';

    $variables['input_attributes']->addClass('d-none');

    $variables['label_attributes'] = (new Attribute(
      $variables['label_attributes'] ?? [],
    ))->addClass('d-flex', 'align-items-center');

    $element = $variables['element'];

    $selected = $type === 'checkbox'
      ? $element['#checked']
      : $element['#return_value'] === $element['#value'];

    $variables['input_title'] = [
      'icon' => $this->helper->icon($icons[(int) $selected], 'fs-3 me-2'),
      'text' => ['#plain_text' => $variables['input_title']],
    ];
  }

}
