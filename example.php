<?php
/**
 * Created with IntelliJ IDEA.
 * Project: SmartHome PHP
 * User: Bubelbub <bubelbub@gmail.com>
 * Date: 07.12.13
 * Time: 15:44
 */

use Bubelbub\SmartHomePHP\SmartHome;
use Bubelbub\SmartHomePHP\Entity\LogicalDevice;

require_once 'SmartHome.php';
require_once 'Request/BaseRequest.php';
require_once 'Request/LoginRequest.php';
require_once 'Request/GetAllLogicalDeviceStatesRequest.php';
require_once 'Request/GetMessageListRequest.php';
require_once 'Request/GetEntitiesRequest.php';
require_once 'Request/GetAllPhysicalDeviceStatesRequest.php';
require_once 'Request/GetShcInformationRequest.php';
require_once 'Request/GetShcTypeRequest.php';
require_once 'Request/GetUpdatesRequest.php';
require_once 'Request/GetApplicationTokenRequest.php';
require_once 'Entity/Entity.php';
require_once 'Entity/Location.php';
require_once 'Entity/LogicalDevice.php';
require_once 'Entity/Actuator.php';
require_once 'Entity/SwitchActuator.php';
require_once 'Entity/WindowDoorSensor.php';
require_once 'Entity/PushButtonSensor.php';
require_once 'Entity/ThermostatActuator.php';
require_once 'Entity/ValveActuator.php';
require_once 'Entity/RoomTemperatureActuator.php';
require_once 'Request/SetActuatorStatesRequest.php';
require_once 'Entity/TemperatureSensor.php';
require_once 'Entity/RoomTemperatureSensor.php';
require_once 'Entity/HumiditySensor.php';
require_once 'Entity/RoomHumiditySensor.php';
require_once 'Entity/MotionDetectionSensor.php';
require_once 'Entity/LuminanceSensor.php';
require_once 'Entity/AlarmActuator.php';
require_once 'Entity/SmokeDetectorSensor.php';
require_once 'Entity/GenericActuator.php';
require_once 'Entity/GenericSensor.php';

$newLine = php_sapi_name() == 'cli' ? PHP_EOL : '<br />';

$config = new SimpleXMLElement('<SmartHomeConfiguration />');
$configFile = __FILE__ . '.config';
if(file_exists($configFile))
{
	try{$config = new SimpleXMLElement($configFile, 0, true);}catch(Exception $ex){}
}

$sh = new \Bubelbub\SmartHomePHP\SmartHome('Hostname or IP address', 'Username', 'Password');

if(!file_exists($configFile))
{
	$config->addChild('SessionId', $sh->getSessionId());
	$config->addChild('ClientId', $sh->getClientId());
	$config->addChild('Version', $sh->getVersion());
	$config->addChild('ConfigurationVersion', $sh->getConfigVersion());
	$config->saveXML($configFile);
}

$sh->setSessionId((string) $config->SessionId);
$sh->setClientId((string) $config->ClientId);
$sh->setVersion((string) $config->Version);
$sh->setConfigVersion((string) $config->ConfigurationVersion);

// get your current session id
echo 'Your session id is ' . $sh->getSessionId() . $newLine;

// get your current session id
echo 'Your current client id is ' . $sh->getClientId() . $newLine;

// get your current version
echo 'Your current version is ' . $sh->getVersion() . $newLine;

// get your current configuration version
echo 'Your current configuration version is ' . $sh->getConfigVersion() . $newLine;

// Load all entities (full configuration: LogicalDevices, Locations etc.)
echo 'Loading full configuration...' . $newLine;
$sh->getEntities();

// Load only locations
// $sh->getEntities('Locations');

// Get all logical devices states
echo 'Loading logical device states' . $newLine;
$sh->getAllLogicalDeviceStates();

// Now get a list of all LogicalDevices and print their names and room
foreach ($sh->getLogicalDevices() as $ld) {
	printf("Device '%s' is a '%s' in room '%s'.", $ld->getName(), $ld->getType(), $ld->getLocation()->getName());
	if($ld->getType() == LogicalDevice::DEVICE_TYPE_SWITCH_ACTUATOR) {
		printf(" Switch state is '%s'.", $ld->getState());
	}
	if($ld->getType() == LogicalDevice::DEVICE_TYPE_WINDOW_DOOR_SENSOR) {
		printf(" %s is %s.", $ld->getInstallationType(), $ld->getState());
	}
	echo $newLine;
}

// Get updates
//print_r($sh->getUpdates());

// Get all messages
//print_r($sh->getMessageList());

// Set room temperature of heater to 18°C
/*
	$setActuatorStatesRequest = new SetActuatorStatesRequest($sh);
	$setActuatorStatesRequest->addRoomTemperatureActuatorState('id of heater (LID)', 12, 'auto');
	$setActuatorStatesRequest->send();
*/

// Switch on your adapter for computer
//$sh->setSwitchActuatorState('id of computer adapter (LID)', 'on'); // 'on' could be true, too

// Now you could wake your computer up! (@since 30.03.2014)
