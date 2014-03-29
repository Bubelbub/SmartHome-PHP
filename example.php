<?php
/**
 * Created with IntelliJ IDEA.
 * Project: SmartHome PHP
 * User: Bubelbub <bubelbub@gmail.com>
 * Date: 07.12.13
 * Time: 15:44
 */

require_once 'SmartHome.php';
require_once 'Request/BaseRequest.php';
require_once 'Request/LoginRequest.php';
require_once 'Request/GetEntitiesRequest.php';
require_once 'Request/GetShcInformationRequest.php';
require_once 'Request/GetAllLogicalDeviceStatesRequest.php';
require_once 'Request/GetApplicationTokenRequest.php';
require_once 'Request/GetShcTypeRequest.php';
require_once 'Request/GetAllPhysicalDeviceStatesRequest.php';
require_once 'Request/GetMessageListRequest.php';

use \Bubelbub\SmartHomePHP\Request\SetActuatorStatesRequest;

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
	$config->addChild('Version', $sh->getVersion());
	$config->addChild('ConfigurationVersion', $sh->getConfigVersion());
	$config->saveXML($configFile);
}

$sh->setSessionId((string) $config->SessionId);
$sh->setVersion((string) $config->Version);
$sh->setConfigVersion((string) $config->ConfigurationVersion);

// get your current session id
echo 'Your session id is ' . $sh->getSessionId() . $newLine;

// get your current version
echo 'Your current version is ' . $sh->getVersion() . $newLine;

// get your current configuration version
echo 'Your current configuration version is ' . $sh->getConfigVersion() . $newLine;

// Get all logical devices states
print_r($sh->getAllLogicalDeviceStates());

// Get all messages
print_r($sh->getMessageList());

// Set room temperature of heater to 18°C
/*
	$setActuatorStatesRequest = new SetActuatorStatesRequest($sh);
	$setActuatorStatesRequest->addRoomTemperatureActuatorState('id of heater (LID)', 12, 'auto');
	$setActuatorStatesRequest->send();
*/

// Switch on your adapter for computer
//$sh->setSwitchActuatorState('id of computer adapter (LID)', 'on'); // 'on' could be true, too

// Now you could wake your computer up! (@since 30.03.2014)
