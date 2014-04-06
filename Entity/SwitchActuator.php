<?php

namespace Bubelbub\SmartHomePHP\Entity;

/** 
 * @author Ollie
 * 
 */
class SwitchActuator extends Actuator {
	
	private $isOn;
	
	function getIsOn() {
		return $this->isOn;
	}
	
	function setIsOn($isOn) {
		$this->isOn = $isOn;
	}
}

?>