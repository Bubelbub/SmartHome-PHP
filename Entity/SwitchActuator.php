<?php

namespace Bubelbub\SmartHomePHP\Entity;

/** 
 * @author Ollie
 * 
 */
class SwitchActuator extends Actuator {
	
	/**
	 * @var boolean
	 */
	private $isOn;
	
	function __construct() {
		$this->setType(parent::DEVICE_TYPE_SWITCH_ACTUATOR);
	}
	
	/**
	 * @return boolean
	 */
	function getIsOn() {
		return $this->isOn;
	}
	
	/**
	 * @param boolean $isOn
	 */
	function setIsOn($isOn) {
		$this->isOn = $isOn;
	}
}

?>