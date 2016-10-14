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
		$ilUser = $DIC['ilUser'];

		$value = $this->getRecordField()->getValue();
		if ($value == '0000-00-00 00:00:00' OR !$value) {
			return $this->lng->txt('no_date');
		}
		$html = $this->formatDateTimes($value);
		return $html;
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
			return $date_from . '<br>' . $date_to;
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
			return $date_from . '<br>' . $time_from . ' - ' . $time_to;
		}

		return $date_from . ' ' . $time_from . '<br>' . $date_to . ' ' . $time_to;

//		return $this->lng->txt('no_date');
	}


	public function parseFormInput($value) {
		if (!$value || $value == "-") {
			return NULL;
		}
		return $value;
	}
}