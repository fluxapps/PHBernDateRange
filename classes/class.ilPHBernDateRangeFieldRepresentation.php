<?php
/* Copyright (c) 1998-2009 ILIAS open source, Extended GPL, see docs/LICENSE */
require_once "Modules/DataCollection/classes/Fields/Datetime/class.ilDclDatetimeFieldRepresentation.php";
require_once "Services/Form/classes/class.ilCombinationInputGUI.php";
require_once "Services/Form/classes/class.ilDateTimeInputGUI.php";
require_once "Services/Form/classes/class.ilDateDurationInputGUI.php";
/**
 * Class ilPHBernDateRangeFieldRepresentation
 *
 * @author  Theodor Truffer <tt@studer-raimann.ch>
 */
class ilPHBernDateRangeFieldRepresentation extends ilDclDatetimeFieldRepresentation {

	/**
	 * @var ilPHBernDateRangePlugin
	 */
	protected $pl;


	/**
	 * ilPHBernDateRangeFieldRepresentation constructor.
	 *
	 * @param ilDclBaseFieldModel $field
	 */
	public function __construct(ilDclBaseFieldModel $field) {
		$this->pl = ilPHBernDateRangePlugin::getInstance();

		parent::__construct($field);
	}

	public function getInputField(ilPropertyFormGUI $form, $record_id = 0) {
		$input = new ilDateDurationInputGUI($this->getField()->getTitle(), 'field_' . $this->getField()->getId());
		$input->enableToggleFullTime($this->pl->txt('whole_day'), $_POST[$input->getPostVar()]['tgl']);
//		$input->setMinuteStepSize(1);
		$input->setShowTime(!$_POST[$input->getPostVar()]['tgl']);
		$this->setupInputField($input, $this->field);

		return $input;
	}



}