<?php

namespace Bubelbub\SmartHomePHP\Entity;

use Bubelbub\SmartHomePHP\Entity\LogicalDevice;

/**
 *
 * @author Ollie
 *        
 */
class RoomHumiditySensor extends LogicalDevice {
	
	protected $underlyingDeviceIds = array();
	
	/**
	 * @var float
	 */
	protected $humidity = NULL;
	
	/**
	 */
	function __construct() {
		$this->setType(parent::DEVICE_TYPE_ROOM_HUMIDITY_SENSOR);
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
	public function getHumidity() {
		return $this->humidity;
	}
	
	/**
	 *
	 * @param
	 *        	$humidity
	 */
	public function setHumidity($humidity) {
		$this->humidity = $humidity;
		return $this;
	}
	
	
}