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
	
}