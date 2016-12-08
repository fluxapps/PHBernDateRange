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
	 * @var ilDclFieldTypePlugin
	 */
	protected $pl;

	/**
	 * ilPHBernDateRangeFieldModel constructor.
	 *
	 * @param int $a_id
	 */
	public function __construct($a_id = 0) {
		parent::__construct($a_id);
		$this->pl = ilPHBernDateRangePlugin::getInstance();
		$this->setStorageLocationOverride(1);
	}


	/**
	 * @param string                   $filter_value
	 * @param ilDclBaseFieldModel|NULL $sort_field
	 *
	 * @return ilDclRecordQueryObject
	 */
	public function getRecordQueryFilterObject($filter_value = "", ilDclBaseFieldModel $sort_field = NULL) {
		global $DIC;
		$ilDB = $DIC['ilDB'];

		$date_from = (isset($filter_value['from']) && is_object($filter_value['from'])) ? $filter_value['from'] : NULL;
		$date_to = (isset($filter_value['to']) && is_object($filter_value['to'])) ? $filter_value['to'] : NULL;

		$join_str = "INNER JOIN il_dcl_record_field AS filter_record_field_{$this->getId()} ON (filter_record_field_{$this->getId()}.record_id = record.id AND filter_record_field_{$this->getId()}.field_id = "
			. $ilDB->quote($this->getId(), 'integer') . ") ";
		$join_str .= "INNER JOIN il_dcl_stloc{$this->getStorageLocation()}_value AS filter_stloc_{$this->getId()} ON (filter_stloc_{$this->getId()}.record_field_id = filter_record_field_{$this->getId()}.id ";
		if ($date_from) {
			$join_str .= "AND UNIX_TIMESTAMP(SUBSTRING(filter_stloc_{$this->getId()}.value,11,10)) >= " . $ilDB->quote($date_from->getUnixTime(), 'integer') . " ";
		}
		if ($date_to) {
			$join_str .= "AND UNIX_TIMESTAMP(SUBSTRING(filter_stloc_{$this->getId()}.value,11,10)) <= " . $ilDB->quote($date_to->getUnixTime(), 'integer') . " ";
		}
		$join_str .= ") ";

		$sql_obj = new ilDclRecordQueryObject();
		$sql_obj->setJoinStatement($join_str);

		return $sql_obj;
	}


	/**
	 * @param ilExcel $worksheet
	 * @param         $row
	 * @param         $col
	 */
	public function fillHeaderExcel(ilExcel $worksheet, &$row, &$col) {
		$worksheet->setCell($row, $col, $this->getTitle() . ' ' . $this->pl->txt('from'));
		$col++;
		$worksheet->setCell($row, $col, $this->getTitle() . ' ' . $this->pl->txt('to'));
		$col++;
	}


	/**
	 * @param array $titles
	 * @param array $import_fields
	 */
	public function checkTitlesForImport(array &$titles, array &$import_fields) {
		foreach ($titles as $k => $title) {
			if (($this->getTitle() . ' ' . $this->pl->txt('from')) == $title) {
				$import_fields[$k] = $this;
				if ($titles[$k+1] == ($this->getTitle() . ' ' . $this->pl->txt('to'))) {
					unset($titles[$k+1]);
				}
			}
		}
	}
}