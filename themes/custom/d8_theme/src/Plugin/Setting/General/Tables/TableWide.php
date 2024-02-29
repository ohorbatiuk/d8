<?php

namespace Drupal\d8_theme\Plugin\Setting\General\Tables;

use Drupal\bootstrap\Plugin\Setting\SettingBase;

/**
 * The "table_wide" theme setting.
 *
 * @ingroup plugins_setting
 *
 * @BootstrapSetting(
 *   id = "table_wide",
 *   type = "checkbox",
 *   title = @Translation("Wide"),
 *   description = @Translation("Delete left and right free spaces near a table which created by column."),
 *   defaultValue = 1,
 *   weight = 2,
 *   groups = {
 *     "general" = @Translation("General"),
 *     "tables" = @Translation("Tables"),
 *   },
 * )
 */
class TableWide extends SettingBase {}
