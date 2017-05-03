<?php

// Directories to create.
$directories = array(
  variable_get('file_temporary_path', file_directory_temp()),
  variable_get('file_public_path', conf_path() . '/files'),
  variable_get('file_private_path', conf_path() . '/files/private_files'),
);

foreach ($directories as $directory) {
  if (!$directory) {
    continue;
  }
  file_prepare_directory($directory, FILE_CREATE_DIRECTORY);
}
