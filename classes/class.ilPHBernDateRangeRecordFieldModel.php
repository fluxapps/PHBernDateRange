<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once "Modules/DataCollection/classes/Fields/Datetime/class.ilDclDatetimeRecordFieldModel.php";
/**
 * Class ilPHBernDateRangeRecordFieldModel
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ilPHBernDateRangeRecordFieldModel extends ilDclDatetimeRecordFieldModel {

	public function fillExcelExport(ilExcel $worksheet, &$row, &$col) {
		$value = $this->getExportValue();
		$worksheet->setCell($row, $col, $value['start']);
		$col ++;
		$worksheet->setCell($row, $col, $value['end']);
		$col ++;
	}


	public function parseExportValue($value) {
		return $value;
	}


	public function getValueFromExcel($excel, $row, $col) {
		$value_from = $excel->getCell($row, $col);
		$value_to  = $excel->getCell($row, $col+1);
		return array('start' => $value_from, 'end' => $value_to);
	}
}