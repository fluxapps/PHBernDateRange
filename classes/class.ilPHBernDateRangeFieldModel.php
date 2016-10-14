<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once "Modules/DataCollection/classes/Fields/Datetime/class.ilDclDatetimeFieldModel.php";
/**
 * Class ilPHBernDateRangeFieldModel
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ilPHBernDateRangeFieldModel extends ilDclDatetimeFieldModel {

	/**
	 * ilPHBernDateRangeFieldModel constructor.
	 *
	 * @param int $a_id
	 */
	public function __construct($a_id = 0) {
		parent::__construct($a_id);
		$this->setStorageLocationOverride(1);
	}



}