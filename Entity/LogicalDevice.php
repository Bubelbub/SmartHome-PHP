<?php
namespace Bubelbub\SmartHomePHP\Entity;

class LogicalDevice {
	
	private $id;
	private $name;
	private $locationId;
	private $baseDeviceId;
	
	function getId() {
		return $this->id;
	}
	
	function setId($id) {
		$this->id = $id;
	}
	
	function getName() {
		return $this->name;
	}
	
	function setName($name) {
		$this->name = $name;
	}
	
	function getLocationId() {
		return $this->locationId;
	}
	
	function setLocationId($locationId) {
		$this->locationId = $locationId;
	}
	
	function getBaseDeviceId() {
		return $this->baseDeviceId;
	}
	
	function setBaseDeviceId($baseDeviceId) {
		$this->baseDeviceId = $baseDeviceId;
	}
}

?>