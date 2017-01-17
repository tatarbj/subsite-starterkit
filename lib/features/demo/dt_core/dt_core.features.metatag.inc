<?php

/**
 * @file
 * dt_core.features.metatag.inc
 */

/**
 * Implements hook_metatag_export_default().
 */
function dt_core_metatag_export_default() {
  $config = array();

  // Exported Metatag config instance: node.
  $config['node'] = array(
    'instance' => 'node',
    'config' => array(
      'title' => array(
        'value' => '[node:title] | [site:name]',
      ),
      'description' => array(
        'value' => '[node:field_core_description]',
      ),
      'last-modified' => array(
        'value' => '[node:changed:custom:d/m/Y]',
      ),
      'last-modified' => array(
        'value' => '[node:changed:custom:d/m/Y]',
      ),
      'og:title' => array(
        'value' => '[node:title]',
      ),
      'og:description' => array(
        'value' => '[node:field_core_description]',
      ),
      'og:updated_time' => array(
        'value' => '[node:changed:custom:c]',
      ),
      'article:published_time' => array(
        'value' => '[node:created:custom:c]',
      ),
      'article:modified_time' => array(
        'value' => '[node:changed:custom:c]',
      ),
    ),
  );

  return $config;
}
