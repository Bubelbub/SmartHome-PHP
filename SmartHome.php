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
use Bubelbub\SmartHomePHP\Request\GetUpdatesRequest;
use Bubelbub\SmartHomePHP\Request\LoginRequest;
use Bubelbub\SmartHomePHP\Request\ReleaseConfigurationLockRequest;
use Bubelbub\SmartHomePHP\Request\SetActuatorStatesRequest;
use Bubelbub\SmartHomePHP\Entity\Location;
use Bubelbub\SmartHomePHP\Entity\SwitchActuator;
use Bubelbub\SmartHomePHP\Entity\LogicalDevice;
use Bubelbub\SmartHomePHP\Entity\WindowDoorSensor;
use Bubelbub\SmartHomePHP\Entity\PushButtonSensor;
use Bubelbub\SmartHomePHP\Entity\ThermostatActuator;
use Bubelbub\SmartHomePHP\Entity\ValveActuator;
use Bubelbub\SmartHomePHP\Entity\RoomTemperatureActuator;
use Bubelbub\SmartHomePHP\Entity\TemperatureSensor;
use Bubelbub\SmartHomePHP\Entity\RoomTemperatureSensor;
use Bubelbub\SmartHomePHP\Entity\HumiditySensor;
use Bubelbub\SmartHomePHP\Entity\RoomHumiditySensor;
use Bubelbub\SmartHomePHP\Entity\MotionDetectionSensor;
use Bubelbub\SmartHomePHP\Entity\LuminanceSensor;
use Bubelbub\SmartHomePHP\Entity\AlarmActuator;
use Bubelbub\SmartHomePHP\Entity\SmokeDetectorSensor;
use Bubelbub\SmartHomePHP\Entity\GenericActuator;
use Bubelbub\SmartHomePHP\Entity\GenericSensor;

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
	 * @var string the client id of the session
	 */
	private $clientId;

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
	 * @param string|null $clientId the client id of the session
	 * @param string|null $version the version of shc
	 * @param string|null $configVersion the configuration version of entities etc.
	 */
	public function __construct($host, $username, $password, $sessionId = null, $clientId = null, $version = null, $configVersion = null)
	{
		if (!is_string($host))
		{
			throw new \Exception('Missing parameter 1 "host"', 100);
		}
		$this->host = gethostbyname($host);

		$online = @fsockopen($this->host, 443, $errorNumber, $errorString, 10);
		if ($online === false)
		{
			throw new \Exception('Host "' . $this->host . '" (' . $host . ') is offline / Error [' . $errorNumber . '] ' . $errorString, 103);
		}
		@fclose($online);

		if (!is_string($username))
		{
			throw new \Exception('Missing parameter 2 "username"', 101);
		}
		$this->username = $username;

		if (!is_string($password))
		{
			throw new \Exception('Missing parameter 3 "password"', 102);
		}
		$this->password = $password;

		if (is_string($sessionId))
		{
			$this->sessionId = $sessionId;
		}

		if (is_string($clientId))
		{
			$this->clientId = $clientId;
		}

		if ($version !== null)
		{
			$this->version = $version;
		}

		if ($configVersion !== null)
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
		$response = $getEntitiesRequest->send();

		// create all Location objects
		if ($entityType == 'Configuration' || $entityType == 'Locations')
		{
			foreach ($response->LCs->LC as $location)
			{
				$this->locations[(String)$location->Id] = new Location((String)$location->Id, (String)$location->Name, (String)$location->Position);
			}
		}

		// create all LogicalDevice objects
		if ($entityType == 'Configuration' || $entityType == 'LogicalDevices')
		{
			foreach ($response->LDs->LD as $logicalDevice)
			{
				switch ($logicalDevice->attributes('xsi', true)->type)
				{
					case LogicalDevice::DEVICE_TYPE_SWITCH_ACTUATOR:
						$device = new SwitchActuator();
						$device->setId((String)$logicalDevice->Id);
						$device->setName((String)$logicalDevice['Name']);
						$device->setLocationId((String)$logicalDevice['LCID']);
						if (array_key_exists($device->getLocationId(), $this->locations))
							$device->setLocation($this->locations[$device->getLocationId()]);
						$device->setBaseDeviceId((String)$logicalDevice->BDId);
						$device->setActuatorClass((String)$logicalDevice->ActCls);

						$this->logicalDevices[(String)$logicalDevice->Id] = $device;
						break;

					case LogicalDevice::DEVICE_TYPE_WINDOW_DOOR_SENSOR:
						$device = new WindowDoorSensor();
						$device->setId((String)$logicalDevice->Id);
						$device->setName((String)$logicalDevice['Name']);
						$device->setLocationId((String)$logicalDevice['LCID']);
						if (array_key_exists($device->getLocationId(), $this->locations))
							$device->setLocation($this->locations[$device->getLocationId()]);
						$device->setBaseDeviceId((String)$logicalDevice->BDId);
						$device->setInstallationType((String)$logicalDevice->Installation);

						$this->logicalDevices[(String)$logicalDevice->Id] = $device;
						break;

					case LogicalDevice::DEVICE_TYPE_PUSH_BUTTON_SENSOR:
						$device = new PushButtonSensor();
						$device->setId((String)$logicalDevice->Id);
						$device->setName((String)$logicalDevice['Name']);
						$device->setLocationId((String)$logicalDevice['LCID']);
						if (array_key_exists($device->getLocationId(), $this->locations))
							$device->setLocation($this->locations[$device->getLocationId()]);
						$device->setBaseDeviceId((String)$logicalDevice->BDId);
						$device->setButtonCount((Integer)$logicalDevice->ButtonCount);

						$this->logicalDevices[(String)$logicalDevice->Id] = $device;
						break;

					case LogicalDevice::DEVICE_TYPE_THERMOSTAT_ACTUATOR:
						$device = new ThermostatActuator();
						$device->setId((String)$logicalDevice->Id);
						$device->setName((String)$logicalDevice['Name']);
						$device->setLocationId((String)$logicalDevice['LCID']);
						if (array_key_exists($device->getLocationId(), $this->locations))
							$device->setLocation($this->locations[$device->getLocationId()]);
						$device->setBaseDeviceId((String)$logicalDevice->BDId);
						$device->setActuatorClass((String)$logicalDevice->ActCls);

						$this->logicalDevices[(String)$logicalDevice->Id] = $device;
						break;

					case LogicalDevice::DEVICE_TYPE_VALVE_ACTUATOR:
						$device = new ValveActuator();
						$device->setId((String)$logicalDevice->Id);
						$device->setName((String)$logicalDevice['Name']);
						$device->setLocationId((String)$logicalDevice['LCID']);
						if (array_key_exists($device->getLocationId(), $this->locations))
							$device->setLocation($this->locations[$device->getLocationId()]);
						$device->setBaseDeviceId((String)$logicalDevice->BDId);
						$device->setActuatorClass((String)$logicalDevice->ActCls);
						$device->setValveIndex((Integer)$logicalDevice->ValveIndex);

						$this->logicalDevices[(String)$logicalDevice->Id] = $device;
						break;

					case LogicalDevice::DEVICE_TYPE_ROOM_TEMPERATURE_ACTUATOR:
						$device = new RoomTemperatureActuator();
						$device->setId((String)$logicalDevice->Id);
						$device->setName((String)$logicalDevice['Name']);
						$device->setLocationId((String)$logicalDevice['LCID']);
						if (array_key_exists($device->getLocationId(), $this->locations))
							$device->setLocation($this->locations[$device->getLocationId()]);
						$guids = array();
						foreach ($logicalDevice->UDvIds->guid as $guid)
						{
							$guids[] = (String)$guid[0];
						}
						$device->setUnderlyingDeviceIds($guids);
						$device->setBaseDeviceId((String)$logicalDevice->BDId);
						$device->setActuatorClass((String)$logicalDevice->ActCls);
						$device->setMaxTemperature((Float)$logicalDevice->MxTp);
						$device->setMinTemperature((Float)$logicalDevice->MnTp);
						$device->setPreheatFactor((Float)$logicalDevice->PhFct);
						$device->setIsLocked((String)$logicalDevice->Lckd == 'false' ? false : true);
						$device->setIsFreezeProtectionActivated((String)$logicalDevice->FPrA == 'false' ? false : true);
						$device->setFreezeProtection((Float)$logicalDevice->FPr);
						$device->setIsMoldProtectionActivated((String)$logicalDevice->MPrA == 'false' ? false : true);
						$device->setHumidityMoldProtection((Float)$logicalDevice->HMPr);
						$device->setWindowOpenTemperature((Float)$logicalDevice->WOpTp);

						$this->logicalDevices[(String)$logicalDevice->Id] = $device;
						break;

					case LogicalDevice::DEVICE_TYPE_TEMPERATURE_SENSOR:
						$device = new TemperatureSensor();
						$device->setId((String)$logicalDevice->Id);
						$device->setName((String)$logicalDevice['Name']);
						$device->setLocationId((String)$logicalDevice['LCID']);
						if (array_key_exists($device->getLocationId(), $this->locations))
							$device->setLocation($this->locations[$device->getLocationId()]);
						$device->setBaseDeviceId((String)$logicalDevice->BDId);

						$this->logicalDevices[(String)$logicalDevice->Id] = $device;
						break;

					case LogicalDevice::DEVICE_TYPE_ROOM_TEMPERATURE_SENSOR:
						$device = new RoomTemperatureSensor();
						$device->setId((String)$logicalDevice->Id);
						$device->setName((String)$logicalDevice['Name']);
						$device->setLocationId((String)$logicalDevice['LCID']);
						if (array_key_exists($device->getLocationId(), $this->locations))
							$device->setLocation($this->locations[$device->getLocationId()]);
						$guids = array();
						foreach ($logicalDevice->UDvIds->guid as $guid)
						{
							$guids[] = (String)$guid[0];
						}
						$device->setUnderlyingDeviceIds($guids);

						$this->logicalDevices[(String)$logicalDevice->Id] = $device;
						break;

					case LogicalDevice::DEVICE_TYPE_HUMIDITY_SENSOR:
						$device = new HumiditySensor();
						$device->setId((String)$logicalDevice->Id);
						$device->setName((String)$logicalDevice['Name']);
						$device->setLocationId((String)$logicalDevice['LCID']);
						if (array_key_exists($device->getLocationId(), $this->locations))
							$device->setLocation($this->locations[$device->getLocationId()]);
						$device->setBaseDeviceId((String)$logicalDevice->BDId);

						$this->logicalDevices[(String)$logicalDevice->Id] = $device;
						break;

					case LogicalDevice::DEVICE_TYPE_ROOM_HUMIDITY_SENSOR:
						$device = new RoomHumiditySensor();
						$device->setId((String)$logicalDevice->Id);
						$device->setName((String)$logicalDevice['Name']);
						$device->setLocationId((String)$logicalDevice['LCID']);
						if (array_key_exists($device->getLocationId(), $this->locations))
							$device->setLocation($this->locations[$device->getLocationId()]);
						$guids = array();
						foreach ($logicalDevice->UDvIds->guid as $guid)
						{
							$guids[] = (String)$guid[0];
						}
						$device->setUnderlyingDeviceIds($guids);

						$this->logicalDevices[(String)$logicalDevice->Id] = $device;
						break;

					case LogicalDevice::DEVICE_TYPE_MOTION_DETECTION_SENSOR:
						$device = new MotionDetectionSensor();
						$device->setId((String)$logicalDevice->Id);
						$device->setName((String)$logicalDevice['Name']);
						$device->setLocationId((String)$logicalDevice['LCID']);
						if (array_key_exists($device->getLocationId(), $this->locations))
							$device->setLocation($this->locations[$device->getLocationId()]);
						$device->setBaseDeviceId((String)$logicalDevice->BDId);

						$this->logicalDevices[(String)$logicalDevice->Id] = $device;
						break;

					case LogicalDevice::DEVICE_TYPE_LUMINANCE_SENSOR:
						$device = new LuminanceSensor();
						$device->setId((String)$logicalDevice->Id);
						$device->setName((String)$logicalDevice['Name']);
						$device->setLocationId((String)$logicalDevice['LCID']);
						if (array_key_exists($device->getLocationId(), $this->locations))
							$device->setLocation($this->locations[$device->getLocationId()]);
						$device->setBaseDeviceId((String)$logicalDevice->BDId);

						$this->logicalDevices[(String)$logicalDevice->Id] = $device;
						break;

					case LogicalDevice::DEVICE_TYPE_ALARM_ACTUATOR:
						$device = new AlarmActuator();
						$device->setId((String)$logicalDevice->Id);
						$device->setName((String)$logicalDevice['Name']);
						$device->setLocationId((String)$logicalDevice['LCID']);
						if (array_key_exists($device->getLocationId(), $this->locations))
							$device->setLocation($this->locations[$device->getLocationId()]);
						$device->setBaseDeviceId((String)$logicalDevice->BDId);
						$device->setActuatorClass((String)$logicalDevice->ActCls);
						$device->setAlarmDuration((Integer)$logicalDevice->DOfStgs->AlarmDuration);

						$this->logicalDevices[(String)$logicalDevice->Id] = $device;
						break;

					case LogicalDevice::DEVICE_TYPE_SMOKE_DETECTOR_SENSOR:
						$device = new SmokeDetectorSensor();
						$device->setId((String)$logicalDevice->Id);
						$device->setName((String)$logicalDevice['Name']);
						$device->setLocationId((String)$logicalDevice['LCID']);
						if (array_key_exists($device->getLocationId(), $this->locations))
							$device->setLocation($this->locations[$device->getLocationId()]);
						$device->setBaseDeviceId((String)$logicalDevice->BDId);

						$this->logicalDevices[(String)$logicalDevice->Id] = $device;
						break;

					case LogicalDevice::DEVICE_TYPE_GENERIC_ACTUATOR:
						$device = new GenericActuator();
						$device->setId((String)$logicalDevice->Id);
						$device->setName((String)$logicalDevice['Name']);
						$device->setLocationId((String)$logicalDevice['LCID']);
						if (array_key_exists($device->getLocationId(), $this->locations))
							$device->setLocation($this->locations[$device->getLocationId()]);
						$device->setBaseDeviceId((String)$logicalDevice->BDId);
						$device->setActuatorClass((String)$logicalDevice->ActCls);

						$this->logicalDevices[(String)$logicalDevice->Id] = $device;
						break;

					case LogicalDevice::DEVICE_TYPE_GENERIC_SENSOR:
						$device = new GenericSensor();
						$device->setId((String)$logicalDevice->Id);
						$device->setName((String)$logicalDevice['Name']);
						$device->setLocationId((String)$logicalDevice['LCID']);
						if (array_key_exists($device->getLocationId(), $this->locations))
							$device->setLocation($this->locations[$device->getLocationId()]);
						$device->setBaseDeviceId((String)$logicalDevice->BDId);

						$this->logicalDevices[(String)$logicalDevice->Id] = $device;
						break;

					case LogicalDevice::DEVICE_TYPE_DIMMER_ACTUATOR:
					case LogicalDevice::DEVICE_TYPE_ROLLER_SHUTTER_ACTUATOR:
						break;

					default:
						throw new \Exception('Unknown LogicalDevice type: ' . $logicalDevice->attributes('xsi', true)->type, 103);
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
		foreach ($response->States->LogicalDeviceState as $state)
		{
			$device = & $this->logicalDevices[(String)$state['LID']];

			if (is_object($device))
			{
				switch ($device->getType())
				{
					case LogicalDevice::DEVICE_TYPE_SWITCH_ACTUATOR:
						if ((String)$state['IsOn'] == 'True')
							$device->setState(SwitchActuator::SWITCH_ACTUATOR_STATE_ON);
						elseif ((String)$state['IsOn'] == 'False')
							$device->setState(SwitchActuator::SWITCH_ACTUATOR_STATE_OFF);
						else
							throw new \Exception('Unknown SwitchActuator state "' . (String)$state['IsOn'] . '"', 104);
						break;

					case LogicalDevice::DEVICE_TYPE_ALARM_ACTUATOR:
						if ((String)$state->IsOn == 'true')
							$device->setState(AlarmActuator::ALARM_ACTUATOR_STATE_ON);
						elseif ((String)$state->IsOn == 'false')
							$device->setState(AlarmActuator::ALARM_ACTUATOR_STATE_OFF);
						else
							throw new \Exception('Unknown AlarmActuator state "' . (String)$state->IsOn . '"', 104);
						break;

					case LogicalDevice::DEVICE_TYPE_WINDOW_DOOR_SENSOR:
						if ((String)$state->IsOpen == 'true')
							$device->setState(WindowDoorSensor::WINDOW_DOOR_SENSOR_STATE_OPEN);
						elseif ((String)$state->IsOpen == 'false')
							$device->setState(WindowDoorSensor::WINDOW_DOOR_SENSOR_STATE_CLOSED);
						else
							throw new \Exception('Unknown WindowDoorSensor state "' . (String)$state->IsOpen . '"', 104);
						break;

					case LogicalDevice::DEVICE_TYPE_SMOKE_DETECTOR_SENSOR:
						if ((String)$state->IsSmokeAlarm == 'true')
							$device->setState(SmokeDetectorSensor::SMOKE_DETECTOR_STATE_SMOKE_ALARM_ON);
						elseif ((String)$state->IsSmokeAlarm == 'false')
							$device->setState(SmokeDetectorSensor::SMOKE_DETECTOR_STATE_SMOKE_ALARM_OFF);
						else
							throw new \Exception('Unknown SmokeDetectorSensor state "' . (String)$state->IsSmokeAlarm . '"', 104);
						break;

					case LogicalDevice::DEVICE_TYPE_ROOM_TEMPERATURE_ACTUATOR:
						$device->setPointTemperature((float)$state['PtTmp']);
						if ((String)$state['OpnMd'] == 'Auto')
							$device->setOperationMode(RoomTemperatureActuator::ROOM_TEMPERATURE_ACTUATOR_MODE_AUTO);
						elseif ((String)$state['OpnMd'] == 'Manu')
							$device->setOperationMode(RoomTemperatureActuator::ROOM_TEMPERATURE_ACTUATOR_MODE_MANUAL);
						else
							throw new \Exception('Unknown RoomTemperatureActuator state "' . (String)$state['OpnMd'] . '"', 104);

						if ((String)$state['WRAc'] == 'False')
							$device->setWindowReductionMode(RoomTemperatureActuator::ROOM_TEMPERATURE_ACTUATOR_WINDOW_REDUCTION_INACTIVE);
						elseif ((String)$state['WRAc'] == 'True')
							$device->setWindowReductionMode(RoomTemperatureActuator::ROOM_TEMPERATURE_ACTUATOR_WINDOW_REDUCTION_ACTIVE);
						else
							throw new \Exception('Unknown RoomTemperatureActuator mode "' . (String)$state['WRAc'] . '"', 106);
						break;

					case LogicalDevice::DEVICE_TYPE_ROOM_TEMPERATURE_SENSOR:
						$device->setTemperature((float)$state['Temperature']);
						break;

					case LogicalDevice::DEVICE_TYPE_ROOM_HUMIDITY_SENSOR:
						$device->setHumidity((float)$state['Humidity']);
						break;

					case LogicalDevice::DEVICE_TYPE_LUMINANCE_SENSOR:
						$device->setLuminance((integer)$state['Luminance']);
						break;

					case LogicalDevice::DEVICE_TYPE_PUSH_BUTTON_SENSOR:
						// has no state
						break;

					case LogicalDevice::DEVICE_TYPE_GENERIC_ACTUATOR:
						// TODO Generic actuator state
						break;

					case LogicalDevice::DEVICE_TYPE_GENERIC_SENSOR:
						// TODO Generic sensor state
						break;

					case LogicalDevice::DEVICE_TYPE_DIMMER_ACTUATOR:
						// TODO Dimmer actuator state
						break;

					case LogicalDevice::DEVICE_TYPE_ROLLER_SHUTTER_ACTUATOR:
						// TODO Roller shutter actuator state
						break;

					case LogicalDevice::DEVICE_TYPE_THERMOSTAT_ACTUATOR:
					case LogicalDevice::DEVICE_TYPE_VALVE_ACTUATOR:
					case LogicalDevice::DEVICE_TYPE_TEMPERATURE_SENSOR:
					case LogicalDevice::DEVICE_TYPE_HUMIDITY_SENSOR:
					case LogicalDevice::DEVICE_TYPE_MOTION_DETECTION_SENSOR:

					default:
						throw new \Exception('Unknown LogicalDevice type: ' . $device->getType(), 105);
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
	public function setLogicalDeviceState($logicalDeviceId, $on)
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
	public function setSwitchActuatorState($logicalDeviceId, $on)
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
	 * Get the notification list with all notifications since the last request
	 *
	 * @return \SimpleXMLElement
	 */
	public function getUpdates()
	{
		$getUpdatesRequest = new GetUpdatesRequest($this);
		return $getUpdatesRequest->send();
	}

	/**
	 * Returns an array of all locations
	 *
	 * @return array
	 */
	public function getLocations()
	{
		if(!is_array($this->locations) || count($this->locations) < 1)
		{
			$this->getEntities();
		}
		return $this->locations;
	}

	/**
	 * Returns the location with the given id
	 *
	 * @param string $id
	 * @return array
	 */
	public function getLocation($id)
	{
		return $this->locations[$id];
	}

	/**
	 * @param array $locations
	 */
	public function setLocations($locations)
	{
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
		if ($this->sessionId === null)
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
	 * @param string $clientId
	 */
	public function setClientId($clientId)
	{
		$this->clientId = $clientId;
	}

	/**
	 * @return string
	 */
	public function getClientId()
	{
		return $this->clientId;
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
	 *
	 * @return LogicalDevice[]
	 */
	public function getLogicalDevices()
	{
		return $this->logicalDevices;
	}

	/**
	 * @param LogicalDevice[] $logicalDevices
	 */
	public function setLogicalDevices($logicalDevices)
	{
		$this->logicalDevices = $logicalDevices;
	}
}
