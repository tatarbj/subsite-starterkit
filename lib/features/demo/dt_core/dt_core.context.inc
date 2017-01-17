<?php

/**
 * @file
 * dt_core.context.inc
 */

/**
 * Implements hook_context_default_contexts().
 */
function dt_core_context_default_contexts() {
  $export = array();

  $context = new stdClass();
  $context->disabled = FALSE; /* Edit this to true to make a default context disabled initially */
  $context->api_version = 3;
  $context->name = 'core-sitewide';
  $context->description = '';
  $context->tag = 'Sitewide';
  $context->conditions = array(
    'sitewide' => array(
      'values' => array(
        1 => 1,
      ),
    ),
  );
  $context->reactions = array(
    'block' => array(
      'blocks' => array(
        'dt_shared_functions-dt_shared_functions_user_menu' => array(
          'module' => 'dt_shared_functions',
          'delta' => 'dt_shared_functions_user_menu',
          'region' => 'utility',
          'weight' => '-10',
        ),
      ),
    ),
  );
  $context->condition_mode = 0;

  // Translatables
  // Included for use with string extractors like potx.
  t('Sitewide');
  $export['core-sitewide'] = $context;

  $context = new stdClass();
  $context->disabled = FALSE; /* Edit this to true to make a default context disabled initially */
  $context->api_version = 3;
  $context->name = 'dt_core_non_homepage_pages';
  $context->description = '';
  $context->tag = 'Digital Transformation';
  $context->conditions = array(
    'path' => array(
      'values' => array(
        '~<front>' => '~<front>',
      ),
    ),
    'sitewide' => array(
      'values' => array(
        1 => 1,
      ),
    ),
  );
  $context->reactions = array(
    'block' => array(
      'blocks' => array(
        'bean-information-site-menu' => array(
          'module' => 'bean',
          'delta' => 'information-site-menu',
          'region' => 'header_bottom',
          'weight' => '-10',
        ),
        'easy_breadcrumb-easy_breadcrumb' => array(
          'module' => 'easy_breadcrumb',
          'delta' => 'easy_breadcrumb',
          'region' => 'header_bottom',
          'weight' => '-9',
        ),
        'language_selector_page-language_selector_page' => array(
          'module' => 'language_selector_page',
          'delta' => 'language_selector_page',
          'region' => 'header_bottom',
          'weight' => '-8',
        ),
      ),
    ),
  );
  $context->condition_mode = 1;

  // Translatables
  // Included for use with string extractors like potx.
  t('Digital Transformation');
  $export['dt_core_non_homepage_pages'] = $context;

  return $export;
}
