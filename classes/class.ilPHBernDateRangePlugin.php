<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once('./Modules/DataCollection/classes/Fields/Plugin/class.ilDclFieldTypePlugin.php');

/**
 * Class ilPHBernDateRangePlugin
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ilPHBernDateRangePlugin extends ilDclFieldTypePlugin {

	/**
	 * @inheritdoc
	 */
	function getPluginName() {
		return "PHBernDateRange";
	}
}