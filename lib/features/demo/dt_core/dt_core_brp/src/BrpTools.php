<?php

namespace Drupal\dt_core_brp;

/**
 * Class BrpTools.
 *
 * Helper class with generic static methods which could be reused in future
 * modules and projects.
 */
class BrpTools {

  /**
   * Log data into given file name inside private folder in JSON format.
   *
   * @param array $data
   *   Array that will be transformed in to JSON format.
   *
   * @return bool
   *   TRUE - if file is created, FALSE if not.
   */
  public static function logToFile($data) {
    // Set path to private directory.
    $private_path = drupal_realpath('private://');
    $logfile = $private_path . '/' . $data['error_filename'];

    // TODO transformation to JSON.
    $output = drupal_json_encode($data);

    // Open the log file, or create it if it doesn't exist.
    try {
      if (is_writable($private_path)) {
        $handle = fopen($logfile, 'a');

        if (!$handle) {
          throw new \Exception("Cannot open log file: $logfile");
        }

        if (fwrite($handle, $output) === FALSE) {
          watchdog('BrpTools', 'Cannot write log file %file',
            ['%file' => $logfile], WATCHDOG_ERROR);
          return FALSE;
        }

        fclose($handle);

        return TRUE;
      }
      throw new \Exception("Private file path is not writeable: $private_path");
    }
    catch (\Exception $e) {
      watchdog('BrpTools', $e, [], WATCHDOG_ERROR);
      return FALSE;
    }
  }

  /**
   * Helper method for printing HTTP Responses.
   *
   * @param array $response
   *   HTTP response array.
   * @param string $label
   *   Label for printing purposes.
   */
  public static function printHttpResponse($response, $label = '') {
    if (module_exists('devel')) {
      // @codingStandardsIgnoreStart
      dpm($response, $label);
      // @codingStandardsIgnoreEnd
    }
    else {
      drupal_set_message($label . '<pre>' . print_r($response, TRUE) . '</pre>');
    }
  }

  /**
   * Provides connections instances for given connection type.
   *
   * @param string $connection_type
   *    Clients connection type.
   *
   * @return array
   *    Used as a source for a select form field.
   */
  public static function getConnectionList($connection_type) {
    $options = ['' => t('- Select -')];
    // Load all connections.
    $connections_object = clients_connection_load_all();
    foreach ($connections_object as $connection) {
      // Create a list of options for given connection type.
      if ($connection->type == $connection_type) {
        $options[$connection->name] = drupal_strtoupper($connection->label);
      }
    }

    return $options;
  }

  /**
   * Creates JSON dump file for given data and file name.
   *
   * @param array $data
   *    Data array which suppose to be dumped.
   * @param string $file_name
   *    Filename for the dump.
   * @param string $destination
   *    Place where to create dumps.
   *
   * @return bool|\stdClass
   *    FALSE if there is problem with saving dump in to file | File object.
   */
  public static function createJsonDumpFile($data, $file_name, $destination = 'public://') {
    $dump_date = date('Y_m_d_T_H_i_s');
    $json_data = drupal_json_encode($data);
    $file_path_and_name = $destination . $file_name . '_' . $dump_date;
    if ($json_data) {
      $file = file_save_data($json_data, $file_path_and_name);

      return $file;
    }

    watchdog('BrpTools', 'Cannot create JSON dump %file',
      ['%file' => $file_path_and_name], WATCHDOG_ERROR);

    return FALSE;
  }

  /**
   * Searches for $needle in the multidimensional array $haystack.
   *
   * @param string $needle
   *    The item to search for.
   * @param array $haystack
   *    The array to search.
   *
   * @return array|null
   *    The indices of $needle in $haystack across the various dimensions.
   *    null if $needle was not found.
   */
  public static function recursiveArraySearch($needle, $haystack) {
    foreach ($haystack as $key => $value) {
      if ($needle == $value) {
        return [$key];
      }
      elseif (is_array($value) && $subkey = self::recursiveArraySearch($needle, $value)) {
        array_unshift($subkey, $key);
        return $subkey;
      }
    }
  }

  /**
   * Provides connection name for the given bundle.
   *
   * @param string $bundle_name
   *    Entity bundle name.
   *
   * @return string|bool
   *    Returns connection name string or FALSE if it is not set.
   */
  public static function getConnectionNameFromBundle($bundle_name) {
    return variable_get('brp_ws_client_node_connection_' . $bundle_name, FALSE);
  }

  /**
   * Provides the BRP WS connection name for the given entityform type.
   *
   * In entityform module they are using "entityform type" label for a bundle.
   *
   * @param string $entityform_type
   *    Name of entityform type.
   *
   * @return bool|mixed
   *    Returns connection name string or FALSE if it is not set.
   */
  public static function getConnectionNameFromEntityform($entityform_type) {
    $entityform_type_info = entityform_type_load($entityform_type);
    if (isset($entityform_type_info->data['brp_client'])
      && $entityform_type_info->data['brp_client']['connection']) {

      return $entityform_type_info->data['brp_client']['connection'];
    }

    return FALSE;
  }

  /**
   * Check if the current entityform is integrated with BRP WS Client.
   *
   * @param array $form
   *    Entityform form array.
   *
   * @return bool
   *    TRUE if yes / FALSE if not.
   */
  public static function checkEntityformBrpIntegration($form) {
    if (isset($form['#bundle']) && $form['#entity_type'] == 'entityform') {
      $entityform_meta = entityform_get_types($form['#bundle']);

      if ($entityform_meta
        && isset($entityform_meta->data['brp_client']['connection'])
        && $entityform_meta->data['brp_client']['connection']) {

        return TRUE;
      }
    }

    return FALSE;
  }

  /**
   * Builds a list of key value data based on fields in a vocabulary.
   *
   * @param mixed $voc
   *    Vocabulary id or machine name.
   * @param string $field_key
   *    Field name for the key of the list.
   * @param string $field_value
   *    Field name of the value of the list.
   *
   * @return array|bool
   *    The list if yes / FALSE if wrong vocabulary information.
   */
  public static function buildOptionsFromVocabulary($voc, $field_key, $field_value = 'name') {
    // Load the vocabulary based on correct type of argument passed.
    if (is_string($voc) && $voc != '') {
      $vocabulary = taxonomy_vocabulary_machine_name_load($voc);
    }
    elseif (is_numeric($voc)) {
      $vocabulary = taxonomy_vocabulary_load($voc);
    }

    if (!isset($vocabulary)) {
      watchdog('BRP', 'Bad vocabulary query: %q',
        ['%q' => $voc], WATCHDOG_INFO);
      return FALSE;
    }

    if ($vocabulary && $terms = taxonomy_get_tree($vocabulary->vid, 0, NULL, TRUE)) {
      $list = [];
      // If user-preferred fields exist, list will aggregate information.
      // Empty list will be returned if field for key does not exist.
      foreach (i18n_taxonomy_localize_terms($terms) as $term) {
        // Pass it to the entity metadata wrapper to have access to the fields.
        $emw = entity_metadata_wrapper('taxonomy_term', $term);
        if (isset($emw->$field_key) && isset($emw->$field_value)) {
          $key = $emw->$field_key->value();
          $value = $emw->$field_value->value();
          $list[$key] = $value;
        }
      }
      return $list;
    }

    watchdog('BRP', 'No terms found for the desired query for vocabulary %vid',
      ['%vid' => $vocabulary->vid], WATCHDOG_INFO);

    return FALSE;
  }

  /**
   * Returns the name of a term selected by vocabulary and its field.
   *
   * @param mixed $voc
   *    Vocabulary id or machine name.
   * @param string $field_key
   *    Field name: used to query the key of the term.
   * @param string $field_value
   *    Field value: the value to be overridden when query success.
   *
   * @return mixed
   *    Term name on successful query, initial value of $field_value otherwise.
   */
  public static function convertValueVocabulary($voc, $field_key, $field_value) {
    // Load the vocabulary based on correct type of argument passed.
    if (is_string($voc) && $voc != '') {
      $vocabulary = taxonomy_vocabulary_machine_name_load($voc);
    }
    elseif (is_numeric($voc)) {
      $vocabulary = taxonomy_vocabulary_load($voc);
    }
    // Vocabulary is found, and it's not falsy.
    if (isset($vocabulary) && $vocabulary) {
      $voc_name = $vocabulary->machine_name;
      // Check if the field exists before making the query.
      $field = field_info_instance('taxonomy_term', $field_key, $voc_name);
      if ($field != NULL) {
        // Query for a term that has the required value for the required field.
        $query = new \EntityFieldQuery();
        $query
          ->entityCondition('entity_type', 'taxonomy_term')
          ->entityCondition('bundle', $voc_name)
          ->fieldCondition($field_key, 'value', $field_value, '=');

        $results = $query->execute();
        if (!empty($results['taxonomy_term'])) {
          foreach ($results['taxonomy_term'] as $tid) {
            $term = i18n_taxonomy_localize_terms(taxonomy_term_load($tid->tid));
            return $term->name;
          }
        }
      }
    }
    // Fallback: initial value.
    return $field_value;
  }

  /**
   * Helper function to get node ID for given BRP initiative ID.
   *
   * @param int $initiative_id
   *    BRP initiative ID.
   *
   * @return bool|int
   *    FALSE when there is no initiative for given ID or node ID if there is.
   */
  public static function getInitiativeNodeId($initiative_id) {
    if (is_numeric($initiative_id)) {
      $efq = new \EntityFieldQuery();
      $efq->entityCondition('entity_type', BrpProps::INITIATIVE_CT_ENTITY_TYPE);
      $efq->entityCondition('bundle', BrpProps::INITIATIVE_CT_BUNDLE);
      $efq->fieldCondition(BrpProps::INITIATIVE_FIELD_BRP_ID, 'value', $initiative_id, '=');
      $result = $efq->execute();

      if (isset($result['node'])) {
        return key(reset($result));
      }
    }

    return FALSE;
  }

  /**
   * Streams remote file under given name.
   *
   * @param string $remote_file_path
   *    Full remote path to file that is going to be streamed.
   * @param string $file_name
   *    File name which should be used for streamed file.
   */
  public static function streamRemoteFile($remote_file_path, $file_name) {
    // IE workaround for downloads.
    $type = (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE')) ? 'force-download' : 'octet-stream';

    ob_clean();

    header('Content-Description: File Transfer');
    header('Content-Type: application/' . $type);
    header('Content-Disposition: attachment; filename=' . $file_name);

    // Echo file_get_contents($remote_file_path, FALSE, $context);.
    $file = drupal_http_request($remote_file_path);
    echo $file->data;

    drupal_exit();
  }

}
