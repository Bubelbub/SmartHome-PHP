<?php

use Bubelbub\SmartHomePHP\Entity\LogicalDevice;
/** 
 * Class WindowDoorSensor
 * 
 */
class WindowDoorSensor extends LogicalDevice {
	
	private $installationType;
	
	const INSTALLATION_TYPE_WINDOW = "Window";
	const INSTALLATION_TYPE_DOOR = "Door";
	
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
}

?>