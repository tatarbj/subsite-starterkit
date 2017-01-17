<?php

/**
 * @file
 * Script used by drush to re-generate settings.php.
 */

// Include the install.inc to use the function drupal_rewrite_settings().
include 'includes/install.inc';

// Setup the database settings array.
$settings['databases'] = array(
  'value' => array(
    'default' => array(
      'default' => array(
        'driver' => '%%drupal.db.type%%',
        'database' => '%%drupal.db.name%%',
        'username' => '%%drupal.db.user%%',
        'password' => '%%drupal.db.password%%',
        'host' => '%%drupal.db.host%%',
        'port' => '%%drupal.db.port%%',
        'prefix' => '',
      ),
    ),
  ),
);
// Setup individual development variables.
$settings['conf[\'error_level\']'] = array(
  'required' => TRUE,
  'value' => '%%development.variables.error_level%%',
);
$settings['conf[\'views_ui_show_sql_query\']'] = array(
  'required' => TRUE,
  'value' => '%%development.variables.views_ui_show_sql_query%%',
);
$settings['conf[\'views_ui_show_performance_statistics\']'] = array(
  'required' => TRUE,
  'value' => '%%development.variables.views_ui_show_performance_statistics%%',
);
$settings['conf[\'views_show_additional_queries\']'] = array(
  'required' => TRUE,
  'value' => '%%development.variables.views_show_additional_queries%%',
);
$settings['conf[\'stage_file_proxy_origin\']'] = array(
  'required' => TRUE,
  'value' => '%%development.variables.stage_file_proxy_origin%%',
);
$settings['conf[\'stage_file_proxy_origin_dir\']'] = array(
  'required' => TRUE,
  'value' => '%%development.variables.stage_file_proxy_origin_dir%%',
);
$settings['conf[\'stage_file_proxy_hotlink\']'] = array(
  'required' => TRUE,
  'value' => '%%development.variables.stage_file_proxy_hotlink%%',
);
$settings['conf[\'theme_default\']'] = array(
  'required' => TRUE,
  'value' => '%%development.variables.theme_default%%',
);

// Rewrite the settings.php file with our array.
drupal_rewrite_settings($settings);
