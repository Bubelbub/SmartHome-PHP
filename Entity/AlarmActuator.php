<?php

namespace Bubelbub\SmartHomePHP\Entity;

use Bubelbub\SmartHomePHP\Entity\Actuator;

/**
 * @todo implement isOn/status
 *
 * @author Ollie
 *        
 */
class AlarmActuator extends Actuator {
	
	const ALARM_ACTUATOR_STATE_ON = 'ON';
	const ALARM_ACTUATOR_STATE_OFF = 'OFF';

	/**
	 * @var String
	 */
	private $state = NULL;
		
	/**
	 * @var integer
	 */
	private $alarmDuration = NULL;
	
	/**
	 * constructor
	 */
	function __construct() {
		$this->setType(parent::DEVICE_TYPE_ALARM_ACTUATOR);
	}
	
	/**
	 * Returns the alarm duration
	 * @return the integer
	 */
	public function getAlarmDuration() {
		return $this->alarmDuration;
	}
	
	/**
	 * Sets the alarm duration
	 * @param integer $alarmDuration
	 */
	public function setAlarmDuration($alarmDuration) {
		$this->alarmDuration = $alarmDuration;
		return $this;
	}
	
	/**
	 * Sets the state
	 *
	 * @param String $state
	 * @throws \Exception
	 */
	function setState($state) {
		if($state == (self::ALARM_ACTUATOR_STATE_ON or self::ALARM_ACTUATOR_STATE_OFF))
			$this->state = $state;
		else
			throw new \Exception('Invalid AlarmActuator state "'.$state.'"');
	}
	
	/**
	 * Returns the state
	 */
	function getState() {
		return $this->state;
	}
	
	/**
	 * Returns if the alarm is on
	 * @return boolean
	 */
	function isOn() {
		if(is_null($this->state))
			throw new \Exception('Unknown SwitchActuator state. Did you call getLogicalDeviceStates?');
		return $this->state == self::ALARM_ACTUATOR_STATE_ON;
	}
}