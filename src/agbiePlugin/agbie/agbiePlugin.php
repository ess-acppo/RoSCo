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

	const AGBIE_TO_ROSCO_FIELD_MAPPING = array(
		'author'           => 'r_author',
		'class'            => 'r_class',
		#'commonName'       => 'r_common_name', # NOTE: agbie's commonName field is MULTI-valued, ie. it does contains a comma sep list of common names
		'commonNameSingle' => 'r_common_name', # NOTE: agbie's commonNameSingle field as it's names does suggest contains one (SINGLE) common name
		'family'           => 'r_family',
		'genus'            => 'r_genus',
		'kingdom'          => 'r_kingdom',
		'order'            => 'r_order',
		'phylum'           => 'r_phylum',
		'species'          => 'r_species',
		#'subclass'         => 'r_subclass', # TODO: missing in ROSCO?
		'subfamily'        => 'r_subfamily',
		'subgenus'         => 'r_subgenus',
		'suborder'         => 'r_suborder',
		'subspecies'       => 'r_subspecies',
		'superfamily'      => 'r_superfamily',
		#'superorder'       => 'r_superorder', # TODO: missing in ROSCO?
		'tribe'            => 'r_tribe'
	);

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
	* NOTE: https://docs.collectiveaccess.org/wiki/Application_plugins#The_Plugin_Class
	*       The return value for checkStatus() is an array with four keys:
	*       - description: a description of the plugin
	*       - errors: an array of text error messages relating to the initialization of the plugin. This should be an empty array if there are no errors.
    *         If the plugin is not available the reason why should be expressed in the errors array.
	*       - warnings: an array of text warning messages relating to the initialization of the plugin. Should be a list
    *         of warnings about anything that will limit the functionality of the plugin. Should be an empty array if there are no warnings.
	*       - available: set to true if plugin is loaded and available for use, false if it cannot load for some reason.
	*/
	public function checkStatus() {
		return array(
			'description' => $this->description, #getDescription(),
			'errors'      => array(),
			'warnings'    => array(),
			'available'   => (bool) $this->opo_plugin_config->get('enabled')
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
		$overwrite_mode_str = $this->opo_plugin_config->get('overwrite_mode');
		$overwrite_mode = filter_var($overwrite_mode_str, FILTER_VALIDATE_BOOLEAN);

		$this->log->logInfo(_t('agbiePlugin hookSaveItem START (overwrite_mode=%1)', $overwrite_mode_str)); #: pa_params=%1', json_encode($pa_params)));

		$obj_id = $pa_params['id'];

		$t_object = new ca_objects($obj_id);

		$species_name = $t_object->get('ca_objects.preferred_labels.name');
		$this->log->logInfo(_t('agbiePlugin hookSaveItem: id=%1; "%2"', $obj_id, $species_name));

		# NOTE: species and subspecies name normally (always?) has to contain AT LEAST TWO STRINGS, examples:
		#       - Pseudonaja textilis                        => 2
		#       - E. pauciflora subsp. pauciflora            => 4
		#       - E. pauciflora subsp. hedraia               => 4
		#       - Cortinarius vulpinus subsp. pseudovulpinus => 4
		#
		#       - ...and here is are some concrete examples (of subspecies names) returned by ag-bie:
		#         - Passiflora aurantia var. aurantia        => 4
		#         - Adenia heterophylla australis            => 3
		#         - Diplocyclos palmatus ssp. affinis        => 4
		#         - Cucumis melo melo cantalupensis          => 4
		#
		#       MORAL OF THE STORY: we could verify the provided (user supplied) species/subspecies name
		#       that it does contain AT LEAST TWO STRINGS before calling agbie.
		$species_name_str_array = explode(' ', $species_name);
		$this->log->logInfo(_t('agbiePlugin hookSaveItem species name: "%1" contains %2 strings.', $species_name, count($species_name_str_array)));

		$ch = curl_init();
		$species_name_escaped = curl_escape($ch, _t('"%1"', $species_name));

		# NOTE: TEST we narrow down our query here to only fq=rank:(species OR subspecies)
		$species_name_search_url = "{$this->opo_plugin_config->get('agbie_url_rest_api_search')}?q={$species_name_escaped}&fq=rank:(species%20OR%20subspecies)";
		$this->log->logInfo(_t('agbiePlugin hookSaveItem requesting: %1', $species_name_search_url));

		# NOTE: setup curl error trigger-ing on HTTP_CODE 4xx:
		#       "As per http://curl.haxx.se/libcurl/c/libcurl-errors.html
		#       CURLE_HTTP_RETURNED_ERROR (22)
		#       This is returned if CURLOPT_FAILONERROR is set TRUE and the HTTP server returns an error code that is >= 400.
		#       (This error code was formerly known as CURLE_HTTP_NOT_FOUND.)
		#       WITHOUT this option you still can check for errors, but it takes more effort (examining the details of array/properties
		#       returned by curl_getinfo()).
		#curl_setopt($ch, CURLOPT_FAILONERROR, true);

		# NOTE: CURLOPT_FOLLOWLOCATION ag-bie REST API *DOES* USE HTTP redirect
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $species_name_search_url);

		# NOTE: do the request; if (curl_errno($ch)) { curl_exec($ch); }
		$output = curl_exec($ch);

		# NOTE: this is to check curl errors, PHP is great at hiding errors
		$curl_rc_errno = curl_errno($ch);
		$curl_rc_error_msg = curl_error($ch);
		$curl_info_http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$this->log->logInfo(_t('agbiePlugin hookSaveItem curl request returned: curl_errno=%1; curl_error=%2; HTTP_CODE: %3', $curl_rc_errno, $curl_rc_error_msg, $curl_info_http_code));

		curl_close($ch);

		$this->log->logInfo(_t('agbiePlugin hookSaveItem curl request to the agbie REST API returned: %1', $output));

		# NOTE: extract JSON into an associative array
		$agbie_obj = json_decode($output, true);

		# NOTE: check json_decode error chack/handling
		$rc_json_decode = json_last_error();
		$this->log->logInfo(_t('agbiePlugin hookSaveItem json_decode returned: %1; (JSON_ERROR_NONE=%2)', $rc_json_decode, JSON_ERROR_NONE));
		if (JSON_ERROR_NONE != $rc_json_decode) {
			$this->log->logInfo(_t('agbiePlugin hookSaveItem json_decode returned: %1; error message:%2', $rc_json_decode, json_last_error_msg()));
			return false;
		}

		$agbie_obj_total_records = intval($agbie_obj['searchResults']['totalRecords']);
		$this->log->logInfo(_t('agbiePlugin hookSaveItem received: .searchResults.totalRecords=%1', $agbie_obj_total_records));

		# TODO: from the start of this method up to THIS POINT the functionality can be (ideally will be) shared with checkStatus() and separated into get_agbie_data()

		if ($agbie_obj_total_records < 1) {
			$this->log->logInfo(_t('agbiePlugin hookSaveItem received NO data; nothing to do...'));

		} else {
			# TODO: agbie input verification:
			#       1. we have more than 0 .searchResults.totalRecords
			#       2. selecting .searchResults.results[n]
			#       3. we could verify that .searchResults.results[n].rank == "species" OR "subspecies" (NOT class, genus, order as those usually do not represent biosecurity dangers :-) )
			#          NOTE: at the moment this is handled by the above SOLR fq: fq=rank:(species OR subspecies)

			# NOTE: Select agbie result you want to use to populate this ROSCO object with
			#       In this first implementation we simply take/use the first result: .searchResults.result[0]
			$agbie_obj_result = $agbie_obj['searchResults']['results'][0];

			global $g_ui_locale_id;
			$this->log->logInfo(_t('agbiePlugin hookSaveItem; g_ui_locale_id=%1', $g_ui_locale_id));

			# TODO: separate copy values function
			$t_object->setMode(ACCESS_WRITE);

			# TODO: set "locale" either before this foreach() loop or inside per attribute if required
			foreach (self::AGBIE_TO_ROSCO_FIELD_MAPPING as $agbie_field => $rosco_field ) {
				$agbie_field_val = $agbie_obj_result[$agbie_field];
				$rosco_field_val_old = $t_object->get("ca_objects.{$rosco_field}");

				$this->log->logInfo(_t('agbiePlugin hookSaveItem copy: %1="%2" => %3="%4"', $agbie_field, $agbie_field_val, $rosco_field, $rosco_field_val_old));

				# NOTE: this is an example if we decide to *NOT* over-write an existing ROSCO value
				if (strlen($rosco_field_val_old) > 0) {
					if ($overwrite_mode) {
						$this->log->logInfo(_t('agbiePlugin hookSaveItem copy:   %1 existing value "%2" will be overwritten!', $rosco_field, $rosco_field_val_old));
					} else {
						$this->log->logInfo(_t('agbiePlugin hookSaveItem copy:   %1 is already set to "%2" won\'t overwrite, SKIPPING...', $rosco_field, $rosco_field_val_old));
						continue;
					}
				}

				if (strlen($agbie_field_val) > 0) {
					# NOTE: what is the 'best' way to set an attribute value in our (rosco) case?
					#       a) $t_object->removeAttributes($rosco_field);
					#          $t_object->addAttribute(array($rosco_field => $agbie_obj_result[$agbie_field], 'locale_id' => $g_ui_locale_id), $rosco_field);
					#       b) $t_object->replaceAttribute(array($rosco_field => $agbie_obj_result[$agbie_field], 'locale_id' => $g_ui_locale_id), $rosco_field);
                    #       c) $t_object->replaceAttribute(); ?
					$t_object->replaceAttribute(array($rosco_field => $agbie_obj_result[$agbie_field], 'locale_id' => $g_ui_locale_id), $rosco_field);

				} else {
					$this->log->logInfo(_t('agbiePlugin hookSaveItem copy:   %1 is EMPTY or null => SKIPPING...', $agbie_field));
					# NOTE: WARNING the last continue (in foreach()) is obviously not required because there is no
					#       more processing bellow to be skipped, but just in case someone was extending this, adding
					#       more processing bellow, i will leave it here for the emphasis.
					continue;
				}
			}

			$t_object->update();
		}

		# NOTE: this END marker/message is here in case an exception was thrown (if an exception was thrown the message won't be in the log)
		$this->log->logInfo(_t('agbiePlugin hookSaveItem END'));
		return true;
	}
	# -------------------------------------------------------
	public function hookEditItem(&$pa_params) {
		$this->log->logInfo(_t('agbiePlugin hookEditItem: %1', $pa_params['id']));
		return true;
	}
}
