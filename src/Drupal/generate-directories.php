<?php

/**
 * @file
 * Script used by drush to create files directories.
 */

// Directories to create.
$directories = array(
  variable_get('file_temporary_path', conf_path() . '/tmp'),
  variable_get('file_private_path', conf_path() . '/files/private_files'),
  variable_get('file_public_path', conf_path() . '/files') . '/css_injector',
  variable_get('file_public_path', conf_path() . '/files') . '/js_injector',
  variable_get('file_public_path', conf_path() . '/files'),
);

foreach ($directories as $directory) {
  if ($directory) {
    file_prepare_directory($directory, FILE_CREATE_DIRECTORY);
    drupal_chmod($directory);
  }
}
