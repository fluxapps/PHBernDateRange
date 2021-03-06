<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once "Modules/DataCollection/classes/Fields/Datetime/class.ilDclDatetimeRecordFieldModel.php";
/**
 * Class ilPHBernDateRangeRecordFieldModel
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ilPHBernDateRangeRecordFieldModel extends ilDclDatetimeRecordFieldModel {

	/**
	 * @param ilExcel $worksheet
	 * @param         $row
	 * @param         $col
	 */
	public function fillExcelExport(ilExcel $worksheet, &$row, &$col) {
		$value = $this->getValue();
		$worksheet->setCell($row, $col, $value['start']);
		$col ++;
		$worksheet->setCell($row, $col, $value['end']);
		$col ++;
	}


	/**
	 * @param mixed $value
	 *
	 * @return string
	 */
	public function parseExportValue($value) {
		return ilPHBernDateRangePlugin::formatDateTime($value['start']) . ' - ' . ilPHBernDateRangePlugin::formatDateTime($value['end']);
	}



	/**
	 * @param $excel
	 * @param $row
	 * @param $col
	 *
	 * @return array
	 */
	public function getValueFromExcel($excel, $row, $col) {
		$value_from = $excel->getCell($row, $col);
		$value_to  = $excel->getCell($row, $col+1);
		return array('start' => $value_from, 'end' => $value_to);
	}
}