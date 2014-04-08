<?php
/**
 * Created with IntelliJ IDEA.
 * Project: SmartHome PHP
 * User: Bubelbub <bubelbub@gmail.com>
 * Date: 07.12.13
 * Time: 13:36
 */

namespace Bubelbub\SmartHomePHP;

use Bubelbub\SmartHomePHP\Request\AcquireConfigurationLockRequest;
use Bubelbub\SmartHomePHP\Request\GetAllLogicalDeviceStatesRequest;
use Bubelbub\SmartHomePHP\Request\GetAllPhysicalDeviceStatesRequest;
use Bubelbub\SmartHomePHP\Request\GetApplicationTokenRequest;
use Bubelbub\SmartHomePHP\Request\GetEntitiesRequest;
use Bubelbub\SmartHomePHP\Request\GetMessageListRequest;
use Bubelbub\SmartHomePHP\Request\GetShcInformationRequest;
use Bubelbub\SmartHomePHP\Request\GetShcTypeRequest;
use Bubelbub\SmartHomePHP\Request\LoginRequest;
use Bubelbub\SmartHomePHP\Request\ReleaseConfigurationLockRequest;
use Bubelbub\SmartHomePHP\Request\SetActuatorStatesRequest;
use Bubelbub\SmartHomePHP\Entity\Location;
use Bubelbub\SmartHomePHP\Entity\SwitchActuator;
use Bubelbub\SmartHomePHP\Entity\LogicalDevice;
use Bubelbub\SmartHomePHP\Entity\WindowDoorSensor;

/**
 * Class SmartHome
 * @package Bubelbub\SmartHomePHP
 * @author Bubelbub <bubelbub@gmail.com>
 */
class SmartHome
{
	/**
	 * @var string the hostname/ip address of the shc
	 */
	private $host;

	/**
	 * @var string the username of shc user/owner
	 */
	private $username;

	/**
	 * @var string the password of shc user/owner
	 */
	private $password;

	/**
	 * @var bool is the password saved encrypted?
	 */
	private $isPasswordEncrypted = false;

	/**
	 * @var string the session id of the last requests
	 */
	private $sessionId;

	/**
	 * @var string the version of shc
	 */
	private $version;
	
	/**
	 * @var array array of all locations
	 */
	private $locations = null;
	
	/**
	 * @var array array of all logicalDevices
	 */
	private $logicalDevices = null;

	/**
	 * @var string the configuration version of entities etc.
	 */
	private $configVersion;
	
	/**
	 * @param string $host the hostname/ip address of the shc
	 * @param string $username the username of shc user/owner
	 * @param string $password the password of shc user/owner
	 * @param string|null $sessionId the session id of the last requests
	 * @param string|null $version the version of shc
	 * @param string|null $configVersion the configuration version of entities etc.
	 */
	public function __construct($host, $username, $password, $sessionId = null, $version = null, $configVersion = null)
	{
		if(!is_string($host))
		{
			throw new \Exception('Missing parameter 1 "host"', 100);
		}
		$this->host = gethostbyname($host);

		$online = @fsockopen($this->host, 443, $errorNumber, $errorString, 10);
		if($online === false)
		{
			throw new \Exception('Host "' . $this->host . '" (' . $host . ') is offline / Error [' . $errorNumber . '] ' . $errorString, 103);
		}
		@fclose($online);

		if(!is_string($username))
		{
			throw new \Exception('Missing parameter 2 "username"', 101);
		}
		$this->username = $username;

		if(!is_string($password))
		{
			throw new \Exception('Missing parameter 3 "password"', 102);
		}
		$this->password = $password;

		if(is_string($sessionId))
		{
			$this->sessionId = $sessionId;
		}

		if($version !== null)
		{
			$this->version = $version;
		}

		if($configVersion !== null)
		{
			$this->configVersion = $configVersion;
		}
	}

	/**
	 * @return \SimpleXMLElement
	 */
	public function login($try = 1)
	{
		$loginRequest = new LoginRequest($this);
		return $loginRequest->send('LoginResponse', false, $try);
	}

	/**
	 * @param string $entityType
	 * @return \SimpleXMLElement
	 */
	public function getEntities($entityType = 'Configuration')
	{
		$getEntitiesRequest = new GetEntitiesRequest($this);
		$getEntitiesRequest->setEntityType($entityType);
		$response =  $getEntitiesRequest->send();
		
		// create all Location objects
		if ($entityType == 'Configuration' or $entityType == 'Locations') {
			foreach ($response->LCs->LC as $location) {
				$this->locations[(String) $location->Id] = 
					new Location(
						(String) $location->Id, 
						(String) $location->Name, 
						(String) $location->Position
					);
			}
		}
		
		// create all LogicalDevice objects
		if($entityType == 'Configuration' or $entityType == 'LogicalDevices') {
			foreach ($response->LDs->LD as $logicalDevice) {
				switch ($logicalDevice->attributes('xsi', true)->type) {
					case LogicalDevice::DEVICE_TYPE_SWITCH_ACTUATOR:
						$device = new SwitchActuator();
						$device->setId((String) $logicalDevice->Id);
						$device->setName((String) $logicalDevice['Name']);
						$device->setLocationId((String) $logicalDevice['LCID']);
						$device->setBaseDeviceId((String) $logicalDevice->BDId);
						$device->setActuatorClass((String) $logicalDevice->ActCls);
						
						$this->logicalDevices[(String) $logicalDevice->Id] = $device;
						break;
					
					case LogicalDevice::DEVICE_TYPE_WINDOW_DOOR_SENSOR:
						$device = new WindowDoorSensor();
						$device->setId((String) $logicalDevice->Id);
						$device->setName((String) $logicalDevice['Name']);
						$device->setLocationId((String) $logicalDevice['LCID']);
						$device->setBaseDeviceId((String) $logicalDevice->BDId);
						$device->setInstallationType((String) $logicalDevice->Installation);
						
						$this->logicalDevices[(String) $logicalDevice->Id] = $device;
						break;
						
					case LogicalDevice::DEVICE_TYPE_PUSH_BUTTON_SENSOR:
					case LogicalDevice::DEVICE_TYPE_THERMOSTAT_ACTUATOR:
					case LogicalDevice::DEVICE_TYPE_VALVE_ACTUATOR:
					case LogicalDevice::DEVICE_TYPE_ROOM_TEMPERATURE_ACTUATOR:
					case LogicalDevice::DEVICE_TYPE_TEMPERATURE_SENSOR:
					case LogicalDevice::DEVICE_TYPE_ROOM_TEMPERATURE_SENSOR:
					case LogicalDevice::DEVICE_TYPE_HUMIDITY_SENSOR:
					case LogicalDevice::DEVICE_TYPE_ROOM_HUMIDITY_SENSOR:
					case LogicalDevice::DEVICE_TYPE_MOTION_DETECTION_SENSOR:
					case LogicalDevice::DEVICE_TYPE_LUMINANCE_SENSOR:
					case LogicalDevice::DEVICE_TYPE_ALARM_ACTUATOR:
					case LogicalDevice::DEVICE_TYPE_SMOKE_DETECTOR_SENSOR:
					case LogicalDevice::DEVICE_TYPE_GENERIC_ACTUATOR:
					case LogicalDevice::DEVICE_TYPE_GENERIC_SENSOR:
						// TO BE DONE...
						break;
						
					default:
						throw new \Exception('Unknown LogicalDevice type: '.$logicalDevice->attributes('xsi', true)->type);
				}
			}
		}
		
		return $response;
	}
	
	/**
	 * @return \SimpleXMLElement
	 */
	public function getShcInformation()
	{
		$getShcInformationRequest = new GetShcInformationRequest($this);
		return $getShcInformationRequest->send();
	}

	/**
	 * @return \SimpleXMLElement
	 */
	public function getAllLogicalDeviceStates()
	{
		$getAllLogicalDeviceStatesRequest = new GetAllLogicalDeviceStatesRequest($this);
		$response = $getAllLogicalDeviceStatesRequest->send();
		
		// update LogicalDevice states
		foreach ($response->States->LogicalDeviceState as $state) {
			$device = &$this->logicalDevices[(String) $state['LID']];

			if(is_object($device)) {
				switch ($device->getType()) {
					case LogicalDevice::DEVICE_TYPE_SWITCH_ACTUATOR:
						$device->setIsOn((String) $state['IsOn'] == 'False' ? false : true);
						debug($device, "SWITCH");
						break;
							
					case LogicalDevice::DEVICE_TYPE_WINDOW_DOOR_SENSOR:
						break;
				
					case LogicalDevice::DEVICE_TYPE_PUSH_BUTTON_SENSOR:
					case LogicalDevice::DEVICE_TYPE_THERMOSTAT_ACTUATOR:
					case LogicalDevice::DEVICE_TYPE_VALVE_ACTUATOR:
					case LogicalDevice::DEVICE_TYPE_ROOM_TEMPERATURE_ACTUATOR:
					case LogicalDevice::DEVICE_TYPE_TEMPERATURE_SENSOR:
					case LogicalDevice::DEVICE_TYPE_ROOM_TEMPERATURE_SENSOR:
					case LogicalDevice::DEVICE_TYPE_HUMIDITY_SENSOR:
					case LogicalDevice::DEVICE_TYPE_ROOM_HUMIDITY_SENSOR:
					case LogicalDevice::DEVICE_TYPE_MOTION_DETECTION_SENSOR:
					case LogicalDevice::DEVICE_TYPE_LUMINANCE_SENSOR:
					case LogicalDevice::DEVICE_TYPE_ALARM_ACTUATOR:
					case LogicalDevice::DEVICE_TYPE_SMOKE_DETECTOR_SENSOR:
					case LogicalDevice::DEVICE_TYPE_GENERIC_ACTUATOR:
					case LogicalDevice::DEVICE_TYPE_GENERIC_SENSOR:
						// TO BE DONE...
						break;
				
					default:
						throw new \Exception('Unknown LogicalDevice type: '.$logicalDevice->attributes('xsi', true)->type);
				}
			}
		}
		
		return $response;
	}

	/**
	 * @return \SimpleXMLElement
	 */
	public function getApplicationToken()
	{
		$getApplicationTokenRequest = new GetApplicationTokenRequest($this);
		return $getApplicationTokenRequest->send();
	}

	/**
	 * @param string $restriction
	 * @return \SimpleXMLElement
	 */
	public function getShcType($restriction = 'All')
	{
		$getShcTypeRequest = new GetShcTypeRequest($this);
		$getShcTypeRequest->setRestriction($restriction);
		return $getShcTypeRequest->send();
	}

	/**
	 * @return \SimpleXMLElement
	 */
	public function getAllPhysicalDeviceStates()
	{
		$getAllPhysicalDeviceStatesRequest = new GetAllPhysicalDeviceStatesRequest($this);
		return $getAllPhysicalDeviceStatesRequest->send();
	}

	/**
	 * @return \SimpleXMLElement
	 */
	public function getMessageList()
	{
		$getMessageListRequest = new GetMessageListRequest($this);
		return $getMessageListRequest->send();
	}

	/**
	 * Set the state of an logical device
	 *
	 * @param string $logicalDeviceId the logical device id
	 * @param boolean $on the new state of the device (true = on, false = off)
	 * @return \SimpleXMLElement
	 */
	function setLogicalDeviceState($logicalDeviceId, $on)
	{
		$setActuatorStatesRequest = new SetActuatorStatesRequest($this);
		$setActuatorStatesRequest->addLogicalDeviceState($logicalDeviceId, $on);
		return $setActuatorStatesRequest->send();
	}

	/**
	 * Set the state of an adapter for example
	 *
	 * @param string $logicalDeviceId the logical device id
	 * @param boolean $on the new state of the device (true = on, false = off)
	 * @return \SimpleXMLElement
	 */
	function setSwitchActuatorState($logicalDeviceId, $on)
	{
		$setActuatorStatesRequest = new SetActuatorStatesRequest($this);
		$setActuatorStatesRequest->addSwitchActuatorState($logicalDeviceId, $on);
		return $setActuatorStatesRequest->send();
	}

	/**
	 * @param bool $overrideLock
	 * @return \SimpleXMLElement
	 */
	public function lockConfiguration($overrideLock = false)
	{
		$acquireConfigurationLockRequest = new AcquireConfigurationLockRequest($this);
		$acquireConfigurationLockRequest->setOverrideLock($overrideLock);
		return $acquireConfigurationLockRequest->send();
	}

	/**
	 * @return \SimpleXMLElement
	 */
	public function unlockConfiguration()
	{
		$releaseConfigurationLockRequest = new ReleaseConfigurationLockRequest($this);
		return $releaseConfigurationLockRequest->send();
	}
	
	/**
	 * Returns an array of all locations
	 * 
	 * @return array
	 */
	function getLocations() {
		return $this->locations;
	}
	
	/**
	 * Returns the location with the given id
	 * 
	 * @param string $id
	 * @return array
	 */
	function getLocation($id) {
		return $this->locations[$id];
	}
	
	function setLocations($locations) {
		$this->locations = $locations;
	}

	/**
	 * @return string the host of shc
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 * @return string the username of shc
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * @return string the password of shc
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param boolean $isPasswordEncrypted
	 */
	public function setIsPasswordEncrypted($isPasswordEncrypted)
	{
		$this->isPasswordEncrypted = $isPasswordEncrypted;
	}

	/**
	 * @return boolean
	 */
	public function getIsPasswordEncrypted()
	{
		return $this->isPasswordEncrypted;
	}

	/**
	 * @return string the session id of shc
	 */
	public function getSessionId()
	{
		if($this->sessionId === null)
		{
			$this->login();
		}
		return $this->sessionId;
	}

	/**
	 * @param string $sessionId
	 */
	public function setSessionId($sessionId = null)
	{
		$this->sessionId = $sessionId;
	}

	/**
	 * @return string the version of shc
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 * @param null $version
	 */
	public function setVersion($version = null)
	{
		$this->version = $version;
	}

	/**
	 * @return null
	 */
	public function getConfigVersion()
	{
		return $this->configVersion;
	}

	/**
	 * @param null $configVersion
	 */
	public function setConfigVersion($configVersion = null)
	{
		$this->configVersion = $configVersion;
	}
	
	/**
	 * Returns an array of all LogicalDevices
	 * @return array
	 */
	public function getLogicalDevices() {
		return $this->logicalDevices;
	}
	
	/**
	 * @param array $logicalDevices
	 */
	public function setLogicalDevices($logicalDevices) {
		$this->logicalDevices = $logicalDevices;
	}
}
