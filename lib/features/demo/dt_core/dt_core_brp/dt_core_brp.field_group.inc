<?php

/**
 * @file
 * dt_core_brp.field_group.inc
 */

/**
 * Implements hook_field_group_info().
 */
function dt_core_brp_field_group_info() {
  $field_groups = array();

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_page_content|node|page|form';
  $field_group->group_name = 'group_page_content';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'page';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Content',
    'weight' => 0,
    'children' => array(
      0 => 'title_field',
      1 => 'title',
      2 => 'body',
    ),
    'format_type' => 'tab',
    'format_settings' => array(
      'formatter' => 'closed',
      'instance_settings' => array(
        'classes' => 'group-page-content field-group-tab',
        'required_fields' => TRUE,
      ),
    ),
  );
  $field_groups['group_page_content|node|page|form'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_page_initiatives|node|basic_page|brp_home';
  $field_group->group_name = 'group_page_initiatives';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'basic_page';
  $field_group->mode = 'brp_home';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Latest initiatives',
    'weight' => '16',
    'children' => array(),
    'format_type' => 'section_group',
    'format_settings' => array(
      'formatter' => '',
      'instance_settings' => array(
        'modifier' => '',
        'layout' => '',
        'classes' => 'group-page-initiatives field-group-section_group',
        'show_label' => 0,
        'attributes' => '',
        'label_element' => 'h2',
        'required_fields' => 1,
      ),
    ),
  );
  $field_groups['group_page_initiatives|node|basic_page|brp_home'] = $field_group;

  $field_group = new stdClass();
  $field_group->disabled = FALSE; /* Edit this to true to make a default field_group disabled initially */
  $field_group->api_version = 1;
  $field_group->identifier = 'group_page_metadata|node|page|form';
  $field_group->group_name = 'group_page_metadata';
  $field_group->entity_type = 'node';
  $field_group->bundle = 'page';
  $field_group->mode = 'form';
  $field_group->parent_name = '';
  $field_group->data = array(
    'label' => 'Metadata',
    'weight' => 10,
    'children' => array(
      0 => 'field_tags',
      1 => 'language',
      2 => 'og_group_ref',
      3 => 'group_content_access',
      4 => 'scheduler_settings',
    ),
    'format_type' => 'tab',
    'format_settings' => array(
      'formatter' => 'closed',
      'instance_settings' => array(
        'classes' => 'group-page-metadata field-group-tab',
        'required_fields' => TRUE,
      ),
    ),
  );
  $field_groups['group_page_metadata|node|page|form'] = $field_group;

  // Translatables
  // Included for use with string extractors like potx.
  t('Content');
  t('Latest initiatives');
  t('Metadata');

  return $field_groups;
}
