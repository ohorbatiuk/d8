<?php

namespace Drupal\d8_theme\Plugin\Setting\General\Tables;

use Drupal\bootstrap\Plugin\Setting\SettingBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * The "table_wide" theme setting.
 *
 * @ingroup plugins_setting
 */
#[BootstrapSetting(
  id: 'table_wide',
  type: 'checkbox',
  title: new TranslatableMarkup('Wide'),
  description: new TranslatableMarkup('Delete left and right free spaces near a table which created by column.'),
  defaultValue: 1,
  weight: 2,
  groups: [
    'general' => new TranslatableMarkup('General'),
    'tables' => new TranslatableMarkup('Tables'),
  ]
)]
class TableWide extends SettingBase {}
