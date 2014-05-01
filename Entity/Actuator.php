<?php

namespace Bubelbub\SmartHomePHP\Entity;

/** 
 * Actuator
 * 
 * @author Ollie
 * 
 */
class Actuator extends LogicalDevice {
	
	/**
	 * @var string class of the actuator
	 */
	protected $actuatorClass;
	
	/**
	 * Returns the actuator class
	 * 
	 * @return string
	 */
	function getActuatorClass() {
		return $this->actuatorClass;
	}
	
	/**
	 * Sets the actuator class
	 * 
	 * Depends on the actuator type. Examples are "Light" or "ElectricalDevice" 
	 * for a SwitchActuator.
	 * 
	 * @param string $actuatorClass
	 */
	function setActuatorClass($actuatorClass) {
		$this->actuatorClass = $actuatorClass;
	}
}

?>