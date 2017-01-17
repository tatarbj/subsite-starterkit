<?php

/**
 * @file
 * dt_core_info.features.filter.inc
 */

/**
 * Implements hook_filter_default_formats().
 */
function dt_core_info_filter_default_formats() {
  $formats = array();

  // Exported format: DT Editor.
  $formats['dt_editor'] = (object) array(
    'format' => 'dt_editor',
    'name' => 'DT Editor',
    'cache' => 1,
    'status' => 1,
    'weight' => -10,
    'filters' => array(
      'filter_autop' => array(
        'weight' => 0,
        'status' => 1,
        'settings' => array(),
      ),
      'filter_url' => array(
        'weight' => 0,
        'status' => 1,
        'settings' => array(
          'filter_url_length' => 72,
        ),
      ),
      'video_filter' => array(
        'weight' => 0,
        'status' => 1,
        'settings' => array(),
      ),
      'media_filter' => array(
        'weight' => 2,
        'status' => 1,
        'settings' => array(),
      ),
      'filter_htmlcorrector' => array(
        'weight' => 10,
        'status' => 1,
        'settings' => array(),
      ),
    ),
  );

  return $formats;
}
