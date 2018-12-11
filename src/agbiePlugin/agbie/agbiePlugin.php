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

class agbiePlugin extends BaseApplicationPlugin {
	# -------------------------------------------------------
	/**
	 * Plugin config
	 * @var Configuration
	 */
	var $opo_plugin_config = null;
	# -------------------------------------------------------
	public function __construct($ps_plugin_path) {
                parent::__construct();
		$this->opo_plugin_config = Configuration::load($ps_plugin_path . DIRECTORY_SEPARATOR . 'conf' . DIRECTORY_SEPARATOR . 'agbie.conf');
		# TODO: add the ag-bie URL to the description
		$this->description = _t('This plugin allows prepopulating field values with values returned by the ag-bie REST API');
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
	public function hookSaveItem(&$pa_params) {
		Debug::msg("agbiePlugin hookSaveItem");
		return true;
	}
	# -------------------------------------------------------
	public function hookEditItem(&$pa_params) {
		Debug::msg("agbiePlugin hookEditItem");
		return true;
	}
}

