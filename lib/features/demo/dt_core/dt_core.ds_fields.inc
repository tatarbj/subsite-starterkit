<?php

/**
 * @file
 * Ds fields defined for dt.
 *
 * This file contains all custom ds fields and related functions.
 */

/**
 * Implements hook_ds_fields_info().
 */
function dt_core_ds_fields_info($entity_type) {
  $fields = [];

  if ($entity_type == 'node') {
    $fields['node']['sticky_label'] = [
      'title' => t('Sticky label'),
      'field_type' => DS_FIELD_TYPE_FUNCTION,
      'function' => '_dt_core_ds_fields_stick_label',
    ];
  }

  if (isset($fields[$entity_type])) {
    return [$entity_type => $fields[$entity_type]];
  }
}

/**
 * Implements hook_ds_field_settings_form().
 */
function dt_core_ds_field_settings_form($field) {
  return ds_ds_field_settings_form($field);
}

/**
 * Implements hook_ds_field_format_summary().
 */
function dt_core_ds_field_format_summary($field) {
  return ds_ds_field_format_summary($field);
}

/**
 * Custom function for single token replacement in a field.
 *
 * @param array $field
 *   Array containing field related information.
 */
function _dt_core_ds_fields_stick_label($field) {
  if (isset($field['entity']->sticky) && $field['entity']->sticky) {
    return '<span class="label label--highlight label--meta">' . t('Highlight') . '</span>';
  }
}
