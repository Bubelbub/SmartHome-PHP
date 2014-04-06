<?php

namespace Bubelbub\SmartHomePHP\Entity;

/** 
 * @author Ollie
 * 
 */
class Actuator extends LogicalDevice {
	
	private $actuatorClass;
	
	function getActuatorClass() {
		return $this->actuatorClass;
	}
	
	function setActuatorClass($actuatorClass) {
		$this->actuatorClass = $actuatorClass;
	}
}

?>