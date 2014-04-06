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