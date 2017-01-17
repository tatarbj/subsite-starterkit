<?php

/**
 * @file
 * Custom path alias generation.
 */

/**
 * Implements hook_node_update().
 */
function dt_core_node_update($node) {
  _dt_core_node_reference_aliases_regenerate($node->nid, TRUE);
}

/**
 * Implements function hook_path_insert().
 */
function dt_core_path_insert($path) {
  _dt_core_node_reference_aliases_regenerate($path, FALSE);
}

/**
 * Implements function hook_path_update().
 */
function dt_core_path_update($path) {
  _dt_core_node_reference_aliases_regenerate($path, FALSE);
}

/**
 * Helper to get the possible parent fields.
 *
 * @return array
 *   Array of parent fields.
 */
function _dt_core_get_alias_fields() {
  return [
    'field_core_parent',
    'field_core_parents',
    'field_core_policy_area',
    'field_core_policy_areas',
  ];
}

/**
 * Regenerates the aliases for cer siblings.
 *
 * @param string $start_data
 *   The data to start with, node id or path.
 * @param bool $is_node
 *   TRUE if it is a node, FALSE if path.
 */
function _dt_core_node_reference_aliases_regenerate($start_data, $is_node = TRUE) {
  $start_id = NULL;
  $data = [];

  // Checks what type of data we are using.
  if (!$is_node) {
    $source = explode('/', $start_data['source']);
    if ($source[0] == 'node') {
      $start_id = $source[1];
    }
  }
  else {
    $start_id = $start_data;
  }

  // Gets the reference tree.
  foreach (_dt_core_get_alias_fields() as $field) {
    dt_core_build_node_reference_tree($start_id, $data, $field, 'parent', 0);
    dt_core_build_node_reference_tree($start_id, $data, $field, 'child', 0);
  }

  // The checks and their sorting.
  $checks = [
    'parent' => 'arsort',
    'child' => 'asort',
  ];

  // Loop over and execute the checks.
  foreach ($checks as $index => $sort) {
    if (isset($data[$index]) && is_array($data[$index])) {
      $sort($data[$index]);
      foreach ($data[$index] as $nid => $depth) {
        pathauto_node_update_alias_multiple([$nid], 'update', ['force' => TRUE]);
      }
    }
  }
}

/**
 * Helper to generate a recursive list of parent/children relations.
 *
 * @param string $nid
 *   The node id to check for.
 * @param array $data
 *   The data passed by reference.
 * @param string $field
 *   The field.
 * @param string $type
 *   The type (parent or child).
 * @param int $level
 *   The current level.
 * @param bool $circular_check
 *   If it is for the circular check.
 */
function dt_core_build_node_reference_tree($nid, &$data, $field, $type, $level = 0, $circular_check = FALSE) {
  if (!field_info_field($field)) {
    return;
  }
  if ($type == 'parent') {
    $query = db_select('field_data_' . $field, 'parent');
    $query->addField('parent', $field . '_target_id', 'target_id');
    $query->condition('entity_id', $nid, '=');
    $query->addExpression($level, 'ParentLevel');
    if (!$circular_check) {
      $query->condition('delta', '0', '=');
    }
    $queryResult = $query->execute()->fetchAll();
  }
  else {
    $query = db_select('field_data_' . $field, 'parent');
    $query->addField('parent', 'entity_id', 'target_id');
    $query->condition($field . '_target_id', $nid, '=');
    $query->addExpression($level, 'ParentLevel');
    $queryResult = $query->execute()->fetchAll();
  }

  if (is_array($queryResult)) {
    foreach ($queryResult as $result) {
      $data[$type][$result->target_id] = $result->ParentLevel;
      dt_core_build_node_reference_tree($result->target_id, $data, $field, $type, $level + 1);
    }
  }
}

/**
 * Implements hook_pathauto_alias_alter().
 */
function dt_core_pathauto_alias_alter(&$alias, array &$context) {
  // Remove duplicates that are a result of a prefix defined in th URL pattern.
  // before the parent fields slug.
  //
  // For example:
  // The pattern "prefix/[parent:url]/[title]" might result in an alias like
  // "prefix/prefix/parent-title/title" if parent is of the same type since the
  // prefix is added twice. We will remove the duplicate prefix now.
  // We consider everything before the first token a prefix.
  $pattern_prefix = explode('[', $context['pattern']);
  $prefix = $pattern_prefix[0];

  if (!empty($prefix)) {
    // Check if it is subject of parent field.
    foreach (_dt_core_get_alias_fields() as $field_name) {
      // If the URL pattern does rely on this field remove duplicate prefix.
      if (_dt_core_parent_field_in_pattern($field_name, $context['pattern'])) {
        $alias = str_replace($prefix . $prefix, $prefix, $alias);
      }
    }
  }
}

/**
 * Implements hook_node_validate().
 */
function dt_core_node_validate($node, $form, &$form_state) {
  foreach (_dt_core_get_alias_fields() as $field_name) {
    if (isset($form_state['values'][$field_name][LANGUAGE_NONE][0]['target_id']) && !empty($form_state['values'][$field_name][LANGUAGE_NONE][0]['target_id'])) {
      $parent = $form_state['values'][$field_name][LANGUAGE_NONE][0]['target_id'];

      // Detect if node has been set as its own parent.
      if ($parent == $node->nid) {
        form_set_error($field_name, t('The page cannot be its own parent!'));
      }
      // Detect circular reference between two nodes.
      if (_dt_core_detect_circular_reference($node->nid, $parent)) {
        form_set_error($field_name, t('There is a circular reference between this page and one of its parent!'));
      }
    }
  }
}

/**
 * Checks if a URL pattern is relying on a parent field.
 *
 * @param int $nid
 *   Node ID.
 * @param int $parent_nid
 *   Parent node ID.
 *
 * @return bool
 *   There is a circular reference between the two nodes.
 */
function _dt_core_detect_circular_reference($nid, $parent_nid) {
  $relatives = [];
  foreach (_dt_core_get_alias_fields() as $field_name) {
    // Look for relatives of the future parent.
    dt_core_build_node_reference_tree($parent_nid, $relatives, $field_name, 'parent', TRUE);
  }

  // Check if our node is among its future relatives.
  if (isset($relatives['parent']) && is_array($relatives['parent'])) {
    return array_key_exists($nid, $relatives['parent']);
  }
  return FALSE;
}

/**
 * Checks if a URL pattern is relying on a parent field.
 *
 * @param string $field_name
 *   Field name.
 *
 * @return mixed
 *   string pattern token segment | bool FALSE
 */
function _dt_core_parent_field_in_pattern($field_name, $pattern) {
  // Check if URL pattern uses parent's URL.
  $pattern_segment = 'node:' . str_replace('_', '-', $field_name) . '(:0)?:url:path';
  if (preg_match('/' . $pattern_segment . '/', $pattern)) {

    return $pattern_segment;
  }

  return FALSE;
}
