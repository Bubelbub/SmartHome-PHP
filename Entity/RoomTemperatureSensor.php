<?php

namespace Bubelbub\SmartHomePHP\Entity;

use Bubelbub\SmartHomePHP\Entity\LogicalDevice;

/**
 *
 * @author Ollie
 *        
 */
class RoomTemperatureSensor extends LogicalDevice {
	
	private $underlyingDeviceIds = array();
	
	/**
	 */
	function __construct() {
		$this->setType(parent::DEVICE_TYPE_ROOM_TEMPERATURE_SENSOR);
	}
	
	/**
	 *
	 * @return array
	 */
	public function getUnderlyingDeviceIds() {
		return $this->underlyingDeviceIds;
	}
	
	/**
	 *
	 * @param array $underlyingDeviceIds        	
	 */
	public function setUnderlyingDeviceIds($underlyingDeviceIds) {
		$this->underlyingDeviceIds = $underlyingDeviceIds;
		return $this;
	}
	
}