<?php
namespace Bubelbub\SmartHomePHP\Entity;

/**
 * Class LogicalDevice
 * 
 * @author Ollie
 *
 */
class LogicalDevice {
	
	const DEVICE_TYPE_SWITCH_ACTUATOR = 'SwitchActuator';
	const DEVICE_TYPE_WINDOW_DOOR_SENSOR = 'WindowDoorSensor';
	const DEVICE_TYPE_PUSH_BUTTON_SENSOR = 'PushButtonSensor';
	const DEVICE_TYPE_THERMOSTAT_ACTUATOR = 'ThermostatActuator';
	const DEVICE_TYPE_VALVE_ACTUATOR = 'ValveActuator';
	const DEVICE_TYPE_ROOM_TEMPERATURE_ACTUATOR = 'RoomTemperatureActuator';
	const DEVICE_TYPE_TEMPERATURE_SENSOR = 'TemperatureSensor';
	const DEVICE_TYPE_ROOM_TEMPERATURE_SENSOR = 'RoomTemperatureSensor';
	const DEVICE_TYPE_HUMIDITY_SENSOR = 'HumiditySensor';
	const DEVICE_TYPE_ROOM_HUMIDITY_SENSOR = 'RoomHumiditySensor';
	const DEVICE_TYPE_MOTION_DETECTION_SENSOR = 'MotionDetectionSensor';
	const DEVICE_TYPE_LUMINANCE_SENSOR = 'LuminanceSensor';
	const DEVICE_TYPE_ALARM_ACTUATOR = 'AlarmActuator';
	const DEVICE_TYPE_SMOKE_DETECTOR_SENSOR = 'SmokeDetectorSensor';
	const DEVICE_TYPE_GENERIC_ACTUATOR = 'GenericActuator';
	const DEVICE_TYPE_GENERIC_SENSOR = 'GenericSensor';
	
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
	 * @var Location the location object
	 */
	private $location = NULL;
	
	/**
	 * @var string ID of the baseDevice
	 */
	private $baseDeviceId;
	
	/**
	 * @var string devicetype
	 */
	private $type;
	
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
	
	/**
	 * Sets the type of the logical device
	 * @param unknown $type
	 */
	function setType($type) {
		$this->type = $type;
	}
	
	/**
	 * Returns the type of the logical device
	 * @return string
	 */
	function getType() {
		return $this->type;
	}
	
	/**
	 * Sets the location object
	 * @param unknown $location
	 */
	function setLocation($location) {
		$this->location = $location;
	}
	
	/**
	 * Returns the Location object
	 * @return \Bubelbub\SmartHomePHP\Entity\Location
	 */
	function getLocation() {
		return $this->location;
	}
}

?>