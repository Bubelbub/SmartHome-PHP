<?php

namespace Bubelbub\SmartHomePHP\Entity;

use Bubelbub\SmartHomePHP\Entity\LogicalDevice;

/**
 *
 * @author Ollie
 *        
 */
class RoomTemperatureSensor extends LogicalDevice {
	
	/**
	 * @var array
	 */
	protected $underlyingDeviceIds = array();
	
	/**
	 * @var float
	 */
	protected $temperature = NULL;
	
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
	
	/**
	 *
	 * @return the float
	 */
	public function getTemperature() {
		return $this->temperature;
	}
	
	/**
	 *
	 * @param
	 *        	$temperature
	 */
	public function setTemperature($temperature) {
		$this->temperature = $temperature;
		return $this;
	}
	
	
}