<?php

namespace Bubelbub\SmartHomePHP\Entity;

use Bubelbub\SmartHomePHP\Entity\LogicalDevice;

/**
 *
 * @author Ollie
 *        
 */
class SmokeDetectorSensor extends LogicalDevice {
	
	const SMOKE_DETECTOR_STATE_SMOKE_ALARM_ON = 'SMOKE-ALARM-ON';
	const SMOKE_DETECTOR_STATE_SMOKE_ALARM_OFF = 'SMOKE-ALARM-OFF';
	
	/**
	 * @var String
	 */
	private $state = NULL;
	
	/**
	 */
	function __construct() {
		$this->setType(parent::DEVICE_TYPE_SMOKE_DETECTOR_SENSOR);
	}
	
	/**
	 * Sets the state
	 *
	 * @param String $state
	 * @throws \Exception
	 */
	function setState($state) {
		if($state == (self::SMOKE_DETECTOR_STATE_SMOKE_ALARM_ON or self::SMOKE_DETECTOR_STATE_SMOKE_ALARM_OFF))
			$this->state = $state;
		else
			throw new \Exception('Invalid SmokeDetectorSensor state "'.$state.'"');
	}
	
	/**
	 * Returns the state
	 */
	function getState() {
		return $this->state;
	}
	
	
	/**
	 * Returns if the smoke alarm is on
	 * @return boolean
	 */
	function isAlarmOn() {
		if(is_null($this->state))
			throw new \Exception('Unknown SmokeDetectorSensor state. Did you call getLogicalDeviceStates?');
		return $this->state == self::SMOKE_DETECTOR_STATE_SMOKE_ALARM_ON;
	}
}