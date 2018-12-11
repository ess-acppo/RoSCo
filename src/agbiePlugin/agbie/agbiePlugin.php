<?php
/* ----------------------------------------------------------------------
 * agbiePlugin.php
 * ----------------------------------------------------------------------
 * Department of Agriculture and Water Resources
 * ----------------------------------------------------------------------
 *
 * Software by Department of Agriculture and Water Resources
 * Copyright 2018 Martin Bohun Hormann; martin.bohun@gmail.com
 * This file originally contributed 2018 by Martin Bohun Hormann
 *
 * TODO: add licensing info
 * ----------------------------------------------------------------------
 */

require_once(__CA_LIB_DIR__.'/core/Logging/KLogger/KLogger.php');

class agbiePlugin extends BaseApplicationPlugin {
	# -------------------------------------------------------
	/**
	 * Plugin config
	 * @var Configuration
	 */
	var $opo_plugin_config = null;
	var $log = null;

	# TODO: verify the use of this logger, for multithreading issues, etc.
	#var $o_log = new KLogger(__CA_BASE_DIR__ . '/app/log', KLogger::DEBUG);

	# TODO: use/honor the CA global configuration
	#$vs_log_dir = caGetOption('log', $pa_options, __CA_APP_DIR__."/log");
        #$vs_log_level = caGetOption('logLevel', $pa_options, "INFO");

	# -------------------------------------------------------
	public function __construct($ps_plugin_path) {
                parent::__construct();
		$this->opo_plugin_config = Configuration::load($ps_plugin_path . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR . 'agbie.conf');

		$this->log = new KLogger(__CA_BASE_DIR__ . '/app/log', KLogger::DEBUG);
		$this->log->logInfo(_t('agbiePlugin, using ag-bie REST API search endpoint: %1', $this->opo_plugin_config->get('agbie_url_rest_api_search')));

		# TODO: add the ag-bie URL to the description
		$this->description = _t('This plugin allows prepopulating field values with values returned by the ag-bie REST API. Using ag-bie REST API search endpoint: <a href="%1">%1</a>', $this->opo_plugin_config->get('agbie_url_rest_api_search'));
	}
	# -------------------------------------------------------
	/**
	 * Override checkStatus() to return true - the MMS plugin always initializes ok
	 */
	public function checkStatus() {
		return array(
			'description' => $this->getDescription(),
			'errors' => array(),
			'warnings' => array(),
			'available' => (bool) $this->opo_plugin_config->get('enabled')
		);
	}
        # -------------------------------------------------------
        /**
         * Get plugin user actions
         */
        static public function getRoleActionList() {
                return array();
        }
	# -------------------------------------------------------
	public function hookSaveItem(&$pa_params) {
		$this->log->logInfo(_t('agbiePlugin hookSaveItem START: pa_params=%1', json_encode($pa_params)));

		$obj_id = $pa_params['id'];
		# NOTE: the documentation is very uncelar, some examples do you ca_objects, while others use ca_entities
		$t_object = new ca_objects($obj_id);
		$this->log->logInfo(_t('agbiePlugin hookSaveItem ca_objects(%1) loaded: %2', $obj_id, json_encode($t_object)));

		$species_name = $t_object->get('ca_objects.preferred_labels.name'); 
		$this->log->logInfo(_t('agbiePlugin hookSaveItem: id=%1; "%2"', $obj_id, $species_name));

                $ch = curl_init();
		$species_name_escaped = curl_escape($ch, _t('"%1"', $species_name));

		$species_name_search_url = "{$this->opo_plugin_config->get('agbie_url_rest_api_search')}?q={$species_name_escaped}";
                $this->log->logInfo(_t('agbiePlugin hookSaveItem requesting: %1', $species_name_search_url));

		# NOTE: CURLOPT_FOLLOWLOCATION ag-bie REST API *DOES* USE HTTP redirect
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $species_name_search_url);

		# TODO: add proper error handling
		$output = curl_exec($ch); 
                curl_close($ch);

                $this->log->logInfo(_t('agbiePlugin hookSaveItem REST API returned: %1', $output));

		# NOTE: extract JSON into an associative array
		$agbie_obj = json_decode($output, true);
		$this->log->logInfo(_t('agbiePlugin hookSaveItem received: .searchResults.totalRecords=%1', $agbie_obj['searchResults']['totalRecords']));

		# TODO: separate copy values function

		# NOTE: When reading the attributes section they (CA API examples) do *NOT* use the handle 'attributes'.
		#       - source: https://docs.collectiveaccess.org/wiki/API:Accessing_Data#Attributes
		#       - example:
		#            jq/JSON: '.attributes.r_collector_name'
		#         CA API get: 'ca_objects.r_collector_name'
		#         CA API set: ? $t_object->removeAttributes('r_collector_name'); $t_object->AddAttribute(array('r_collector_name' => 'r_collector_name NEW VALUE'), 'r_collector_name');
		#
               	$test_old_collector_name = $t_object->get('ca_objects.r_collector_name');
                $this->log->logInfo(_t('agbiePlugin hookSaveItem TEST old value: %1', $test_old_collector_name));

		# NOTE: In this first implementation we simply take/use: .searchResults.result[0]
		$agbie_obj_result = $agbie_obj['searchResults']['results'][0];
		$this->log->logInfo(_t('agbiePlugin hookSaveItem TEST genus: %1', $agbie_obj_result['genus']));

		$t_object->setMode(ACCESS_WRITE);
		
		# NOTE: This is ONLY a test to write into the 'header' fields; verified: WORKS OK
		# $t_object->set(array('access' => 2, 'status' => 3));

                $t_object->removeAttributes('r_genus');
                $t_object->AddAttribute(array('r_genus' => $agbie_obj_result['genus']), 'r_genus');

		#$t_object->set('ca_objects.r_collector_name', 'TEST r_collector_name set from agbiePlugin');
		$t_object->removeAttributes('r_collector_name');
		$t_object->AddAttribute(array('r_collector_name' => 'TEST r_collector_name set from agbiePlugin'), 'r_collector_name');

		$t_object->update();

                $this->log->logInfo(_t('agbiePlugin hookSaveItem END'));
		return true;
	}
	# -------------------------------------------------------
	public function hookEditItem(&$pa_params) {
		$this->log->logInfo(_t('agbiePlugin hookEditItem: %1', $pa_params['id']));
		return true;
	}
}

