<?php

namespace Bubelbub\SmartHomePHP\Entity;
use Bubelbub\SmartHomePHP\Entity\LogicalDevice;
/** 
 * Class WindowDoorSensor
 * 
 */
class WindowDoorSensor extends LogicalDevice {
	
	const INSTALLATION_TYPE_WINDOW = "Window";
	const INSTALLATION_TYPE_DOOR = "Door";
	
	const WINDOW_DOOR_SENSOR_STATE_OPEN = 'OPEN';
	const WINDOW_DOOR_SENSOR_STATE_CLOSED = 'CLOSED';
	
	/**
	 * @var String
	 */
	private $installationType;
	
	/**
	 * @var String
	 */
	private $state = null;
	
	/**
	 * Constructor
	 */
	function __construct() {
		$this->setType(parent::DEVICE_TYPE_WINDOW_DOOR_SENSOR);
	}
	
	/**
	 * Sets the installation Type
	 * 
	 * Valid types are "Window" or "Door".
	 * 
	 * @param string $installationType
	 */
	function setInstallationType($installationType) {
		if ($installationType == (self::INSTALLATION_TYPE_WINDOW or self::INSTALLATION_TYPE_DOOR)) {
			$this->installationType = $installationType;
		} else {
			throw new Exception(sprintf("Invalid installationtype. Allowed values: '%s', '%s'",self::INSTALLATION_TYPE_WINDOW, self::INSTALLATION_TYPE_DOOR));
		}
		
	}
	
	/**
	 * Returns the installation type.
	 * @return string
	 */
	function getInstallationType() {
		return $this->installationType;
	}
	
	/**
	 * Sets the state
	 * 
	 * @param unknown $state
	 * @throws \Exception
	 */
	function setState($state) {
		if($state == (self::WINDOW_DOOR_SENSOR_STATE_OPEN or self::WINDOW_DOOR_SENSOR_STATE_CLOSED))
			$this->state = $state;
		else
			throw new \Exception('Invalid WindowDoorSensor state "'.$state.'"');
	}
	
	/**
	 * Returns the state 
	 */
	function getState() {
		return $this->state;
	}
	
	
	/**
	 * Returns if the window/door is open
	 * @return boolean
	 */
	function isOpen() {
		if(is_null($this->state))
			throw new \Exception('Unknown WindowDoorSensor state. Did you call getLogicalDeviceStates?'); 
		return $this->state == self::WINDOW_DOOR_SENSOR_STATE_OPEN;
	}
}

?>