<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once "Modules/DataCollection/classes/Fields/Datetime/class.ilDclDatetimeRecordRepresentation.php";
/**
 * Class ilPHBernDateRangeRecordRepresentation
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ilPHBernDateRangeRecordRepresentation extends ilDclDatetimeRecordRepresentation {


	public function getHTML($link = true) {
		global $DIC;
		$tpl = $DIC['tpl'];
		$value = $this->getRecordField()->getValue();
		$template = new ilTemplate("tpl.daterange_record_field.html", true, true, ilPHBernDateRangePlugin::getInstance()->getDirectory());
		$tpl->addCss(ilPHBernDateRangePlugin::getInstance()->getDirectory()."/templates/css/daterange_record_field.css");
		if ($value === null) {
			return '';
		}
		$dates = $this->formatDateTimes($value);
		foreach ($dates as $key => $value) {
			$template->setVariable($key, $value);
		}
		return $template->get();
	}





	/**
	 * @param $value
	 * @param $format
	 *
	 * @return false|string
	 */
	protected function formatDateTimes(array $values) {
		global $DIC;
		$ilUser = $DIC['ilUser'];

		if (count($values) != 2) {
			throw new ilDclException('Wrong number of dates given for fieldtype daterange: should be 2');
		}

		$user_dateformat = $ilUser->getDateFormat();
		$user_timeformat = $ilUser->getTimeFormat();
		$timestamp_from = strtotime($values['start']);
		$timestamp_to = strtotime($values['end']);

		switch($user_dateformat)
		{
			case ilCalendarSettings::DATE_FORMAT_DMY:
				$dateformat = "d.m.Y";
				break;
			case ilCalendarSettings::DATE_FORMAT_YMD:
				$dateformat = "Y-m-d";
				break;
			case ilCalendarSettings::DATE_FORMAT_MDY:
				$dateformat = "m/d/Y";
				break;
		}

		$date_from = date($dateformat, $timestamp_from);
		$date_to = date($dateformat, $timestamp_to);

		// no time
		if (strlen($values['start']) < 11) {
			return array('DATE_FROM' => $date_from, 'TIME_FROM' => '-', 'DATE_TO' => $date_to, 'TIME_TO' => '-');
		}

		switch($user_timeformat)
		{
			case ilCalendarSettings::TIME_FORMAT_24:
				$timeformat = " H:i";
				break;
			case ilCalendarSettings::TIME_FORMAT_12:
				$timeformat = " g:ia";
				break;
		}

		$time_from = date($timeformat, $timestamp_from);
		$time_to = date($timeformat, $timestamp_to);
		if ($date_from == $date_to) {
			$date_to = "&nbsp";
		}

		return array('DATE_FROM' => $date_from, 'TIME_FROM' => $time_from, 'DATE_TO' => $date_to, 'TIME_TO' => $time_to);
	}


	public function parseFormInput($value) {
		if (!$value || $value == "-") {
			return NULL;
		}
		return $value;
	}

	/**
	 * Fills the form with the value of a record
	 * @param $form
	 */
	public function fillFormInput($form) {
		/** @var ilDateDurationInputGUI $input_field */
		$input_field = $form->getItemByPostVar('field_'.$this->getRecordField()->getField()->getId());
		if($input_field) {
			$value = $this->getFormInput();
			// without time
			$has_time = (strlen($value['start']) > 10);
			$input_field->enableToggleFullTime(ilPHBernDateRangePlugin::getInstance()->txt('whole_day'), !$has_time);
			$input_field->setShowTime($has_time);
			$input_field->setValueByArray(array("field_".$this->getRecordField()->getField()->getId() => $value));
		}
	}
}