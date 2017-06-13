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
		$tpl->addCss(ilPHBernDateRangePlugin::getInstance()->getDirectory()."/templates/css/daterange_record_field.css");
		if ($value === null) {
			return '';
		}
		$dates = $this->formatDateTimes($value);
		if ($dates['BOTTOM_LEFT'] || $dates['BOTTOM_RIGHT']) {
			$template = new ilTemplate("tpl.daterange_record_field.html", true, true, ilPHBernDateRangePlugin::getInstance()->getDirectory());
		} else {
			$template = new ilTemplate("tpl.daterange_record_field_single_day.html", true, true, ilPHBernDateRangePlugin::getInstance()->getDirectory());
		}

		foreach ($dates as $key => $value) {
			$template->setVariable($key, $value ? $value : '&nbsp');
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
		if (count($values) != 2) {
			throw new ilDclException('Wrong number of dates given for fieldtype daterange: should be 2');
		}

		$date_from = ilPHBernDateRangePlugin::formatDate($values['start']);
		$date_to = ilPHBernDateRangePlugin::formatDate($values['end']);

		// no time
		if (strlen($values['start']) < 11) {
			return array(
				'TOP_LEFT' => $date_from,
				'TOP_RIGHT' => ilPHBernDateRangePlugin::getInstance()->txt('whole_day'),
				'BOTTOM_LEFT' => ($date_from == $date_to ? false : $date_to),
				'BOTTOM_RIGHT' => ($date_from == $date_to ? false : ilPHBernDateRangePlugin::getInstance()->txt('whole_day')));
		}

		$time_from = ilPHBernDateRangePlugin::formatTime($values['start']);
		$time_to = ilPHBernDateRangePlugin::formatTime($values['end']);

		if ($date_from == $date_to) {
			return array(
				'TOP_LEFT' => $date_from,
				'TOP_RIGHT' => $time_from . ' - ' . $time_to,
				'BOTTOM_LEFT' => false,
				'BOTTOM_RIGHT' => false);
		}

		return array(
			'TOP_LEFT' => $date_from,
			'TOP_RIGHT' => $time_from,
			'BOTTOM_LEFT' => $date_to,
			'BOTTOM_RIGHT' => $time_to);
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