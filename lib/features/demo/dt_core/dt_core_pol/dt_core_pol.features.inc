<?php

/**
 * @file
 * dt_core_pol.features.inc
 */

/**
 * Implements hook_ctools_plugin_api().
 */
function dt_core_pol_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "context" && $api == "context") {
    return array("version" => "3");
  }
  if ($module == "ds" && $api == "ds") {
    return array("version" => "1");
  }
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}
