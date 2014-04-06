<?php
namespace Bubelbub\SmartHomePHP\Entity;

/**
 * Class LogicalDevice
 * 
 * @author Ollie
 *
 */
class LogicalDevice {
	
	/**
	 * @var string the ID
	 */
	private $id;
	
	/**
	 * @var string Name
	 */
	private $name;
	
	/**
	 * @var string the location ID
	 */
	private $locationId;
	
	/**
	 * @var string ID of the baseDevice
	 */
	private $baseDeviceId;
	
	/**
	 * Returns the ID
	 * @return string
	 */
	function getId() {
		return $this->id;
	}
	
	/**
	 * Sets the ID
	 * @param string $id
	 */
	function setId($id) {
		$this->id = $id;
	}
	
	/**
	 * Returns the name
	 * @return string
	 */
	function getName() {
		return $this->name;
	}
	
	/**
	 * Sets the name
	 * @param string $name
	 */
	function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * Returns the location ID
	 * 
	 * Identifies the location, where the device is placed.
	 * 
	 * @return string
	 */
	function getLocationId() {
		return $this->locationId;
	}
	
	/**
	 * Sets the location ID
	 * @param string $locationId
	 */
	function setLocationId($locationId) {
		$this->locationId = $locationId;
	}
	
	/**
	 * Returns the ID of the BaseDevice
	 * @return string
	 */
	function getBaseDeviceId() {
		return $this->baseDeviceId;
	}
	
	/**
	 * Sets the ID of the BaseDevice
	 * @param string $baseDeviceId
	 */
	function setBaseDeviceId($baseDeviceId) {
		$this->baseDeviceId = $baseDeviceId;
	}
}

?>