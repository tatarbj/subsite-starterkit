<?php

/**
 * @file
 * dt_core_brp.features.inc
 */

/**
 * Implements hook_clients_default_connections().
 */
function dt_core_brp_clients_default_connections() {
  $items = array();
  $items['brp'] = entity_import('clients_connection', '{
    "fields" : null,
    "name" : "brp",
    "endpoint" : "https:\/\/www.cc.cec\/brp\/api\/",
    "configuration" : { "debug" : 0, "credentials_storage" : null },
    "label" : "brp",
    "type" : "brp_ws",
    "rdf_mapping" : []
  }');
  $items['brp_acceptance_dev'] = entity_import('clients_connection', '{
    "fields" : null,
    "name" : "brp_acceptance_dev",
    "endpoint" : "https:\\/\\/brpapi:ec2016@webgate.ec.europa.eu\\/fpfis\\/brp-api\\/api\\/",
    "configuration" : { "debug" : 0, "credentials_storage" : null },
    "label" : "brp",
    "type" : "brp_ws",
    "rdf_mapping" : []
  }');
  $items['brp_acceptance'] = entity_import('clients_connection', '{
    "fields" : null,
    "name" : "brp_acceptance",
    "endpoint" : "https:\/\/www.acceptance.cc.cec\/brp\/api\/",
    "configuration" : { "debug" : 0, "credentials_storage" : null },
    "label" : "brp",
    "type" : "brp_ws",
    "rdf_mapping" : []
  }');

  return $items;
}

/**
 * Implements hook_ctools_plugin_api().
 */
function dt_core_brp_ctools_plugin_api($module = NULL, $api = NULL) {
  if ($module == "context" && $api == "context") {
    return array("version" => "3");
  }
  if ($module == "ds" && $api == "ds") {
    return array("version" => "1");
  }
  if ($module == "picture" && $api == "default_picture_mapping") {
    return array("version" => "2");
  }
  if ($module == "strongarm" && $api == "strongarm") {
    return array("version" => "1");
  }
}
