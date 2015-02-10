<?php

namespace Bubelbub\SmartHomePHP\Entity;

/** 
 * @author Ollie
 * 
 */
class GenericActuator extends Actuator {
	
	const GENERIC_ACTUATOR_STATE_ON = 'ON';
	const GENERIC_ACTUATOR_STATE_OFF = 'OFF';

	/**
	 * @var String
	 */
	protected $state = NULL;
	
	/**
	 * constructor 
	 */
	function __construct() {
		$this->setType(parent::DEVICE_TYPE_GENERIC_ACTUATOR);
	}
	
	/**
	 * Sets the state
	 * 
	 * @param String $state
	 * @throws \Exception
	 */
	function setState($state) {
		if($state == (self::GENERIC_ACTUATOR_STATE_ON or self::GENERIC_ACTUATOR_STATE_OFF))
			$this->state = $state;
		else
			throw new \Exception('Invalid GenericActuator state "'.$state.'"');
	}
	
	/**
	 * Returns the state
	 */
	function getState() {
		return $this->state;
	}
	
	
	/**
	 * Returns if the switch is on
	 * @return boolean
	 */
	function isOn() {
		if(is_null($this->state))
			throw new \Exception('Unknown GenericActuator state. Did you call getLogicalDeviceStates?');
		return $this->state == self::GENERIC_ACTUATOR_STATE_ON;
	}
}

?>
