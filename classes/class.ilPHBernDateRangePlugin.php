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
	 * @param $DIC
	 *
	 * @return array
	 */
	protected static function getUserTimeFormat() {
		static $user_timeformat;
		if (!$user_timeformat) {
			global $DIC;
			$ilUser = $DIC['ilUser'];
			$user_timeformat = $ilUser->getTimeFormat();
		}

		return $user_timeformat;
	}

	/**
	 * @param $DIC
	 *
	 * @return array
	 */
	protected static function getUserDateFormat() {
		static $user_dateformat;
		if (!$user_dateformat) {
			global $DIC;
			$ilUser = $DIC['ilUser'];
			$user_dateformat = $ilUser->getDateFormat();
		}

		return $user_dateformat;
	}



	/**
	 * @inheritdoc
	 */
	function getPluginName() {
		return "PHBernDateRange";
	}

	/**
	 * @param $date
	 *
	 * @return false|string
	 */
	public static function formatDate($date) {
		$timestamp = strtotime($date);

		switch(self::getUserDateFormat())
		{
			case ilCalendarSettings::DATE_FORMAT_DMY:
				return date("d.m.Y", $timestamp);
			case ilCalendarSettings::DATE_FORMAT_YMD:
				return date("Y-m-d", $timestamp);
			case ilCalendarSettings::DATE_FORMAT_MDY:
				return date("m/d/Y", $timestamp);
		}
	}

	/**
	 * @param $date
	 *
	 * @return false|string
	 */
	public static function formatTime($time) {
		$timestamp = strtotime($time);

		switch(self::getUserTimeFormat())
		{
			case ilCalendarSettings::TIME_FORMAT_24:
				return date("H:i", $timestamp);
				break;
			case ilCalendarSettings::TIME_FORMAT_12:
				return date("g:ia", $timestamp);
				break;
		}
	}

	/**
	 * @param $date
	 *
	 * @return false|string
	 */
	public static function formatDateTime($datetime) {
		$date = self::formatDate($datetime);
		$time = self::formatTime($datetime);

		return $date . ($time ? " {$time}" : "");
	}


}