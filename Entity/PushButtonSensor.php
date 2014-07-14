<?php

namespace Bubelbub\SmartHomePHP\Entity;
use Bubelbub\SmartHomePHP\Entity\LogicalDevice;

/** 
 * @author Ollie
 * 
 */
class PushButtonSensor extends LogicalDevice {
	
	protected $buttonCount = NULL;
	
	/**
	 */
	function __construct() {
		$this->setType(parent::DEVICE_TYPE_PUSH_BUTTON_SENSOR);
	}
	
	/**
	 * Sets the button count.
	 * @param unknown $buttonCount
	 * @throws \Exception
	 */
	function setButtonCount($buttonCount) {
		if(!is_int($buttonCount))
			throw new \Exception('Buttoncount must be an integer!');
		$this->buttonCount = $buttonCount;
	}
	
	/**
	 * Returns the button count.
	 */
	function getButtonCount() {
		return $this->getButtonCount();
	}
}

?>