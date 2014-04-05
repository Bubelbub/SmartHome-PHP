<?php
/**
 * Created with IntelliJ IDEA.
 * Project: SmartHome PHP
 * User: Bubelbub <bubelbub@gmail.com>
 * Date: 07.12.13
 * Time: 16:43
 */

namespace Bubelbub\SmartHomePHP\Request;
use Bubelbub\SmartHomePHP\SmartHome;

/**
 * Class SetActuatorStatesRequest
 * @package Bubelbub\SmartHomePHP\Request
 * @author Bubelbub <bubelbub@gmail.com>
 */
class SetActuatorStatesRequest extends BaseRequest
{
	/**
	 * @var \SimpleXMLElement with actuator states
	 */
	private $actuatorStates;

	/**
	 * {@inheritdoc}
	 */
	public function __construct(SmartHome $smartHome)
	{
		parent::__construct($smartHome);

		/** Prepare the SetActuatorStates request */
		$request = $this->getRequest();
		$request->addAttribute('BasedOnConfigVersion', $this->smartHome->getConfigVersion());
		$this->actuatorStates = $request->addChild('ActuatorStates');
	}

	/**
	 * {@inheritdoc}
	 */
	public function send($expectedResponse = 'ControlResultResponse', $useSession = true, $try = 1)
	{
		if($this->smartHome->getConfigVersion() === null)
		{
			$this->smartHome->login();
		}

		return parent::send($expectedResponse, $useSession, $try);
	}

	/**
	 * Sets the temperature and mode for heaters
	 *
	 * @param string $logicalDeviceId the logical device id
	 * @param string|float $pointTemperature the temperature to set
	 * @param string $mode the mode of temperature actuator (Auto|Manu)
	 */
	public function addRoomTemperatureActuatorState($logicalDeviceId, $pointTemperature, $mode)
	{
		if((int) $pointTemperature < 6) { $pointTemperature = 6; }
		if((int) $pointTemperature > 30) { $pointTemperature = 30; }
		if(!preg_match('#^[0-9]+(\.[05]+)?$#i', $pointTemperature))
		{
			throw new \Exception('The parameter "PointTemperature" should be a value like "6.0" "6.5" "12" "12.5" ..."');
		}

		$logicalDeviceState = $this->actuatorStates->addChild('LogicalDeviceState');
		$logicalDeviceState->addAttribute('xmlns:xsi:type', 'RoomTemperatureActuatorState');
		$logicalDeviceState->addAttribute('LID', $logicalDeviceId);
		$logicalDeviceState->addAttribute('PtTmp', $pointTemperature);
		$logicalDeviceState->addAttribute('OpnMd', ucfirst(strtolower($mode)));
		$logicalDeviceState->addAttribute('WRAc', 'false');
	}
	
	/**
	 * Sets the on/off state for adapters
	 *
	 * @param string $logicalDeviceId the logical device id
	 * @param bool $value the state to set (on=true/off=false)
	 */
	public function addSwitchActuatorState($logicalDeviceId, $value)
	{
		$logicalDeviceState = $this->actuatorStates->addChild('LogicalDeviceState');
		$logicalDeviceState->addAttribute('xmlns:xsi:type', 'SwitchActuatorState');
		$logicalDeviceState->addAttribute('LID', $logicalDeviceId);
		$logicalDeviceState->addAttribute('IsOn', preg_match('#^(on|1|true)$#i', (string) $value) ? 'true' : 'false');
	}

	/**
	 * Currently unknown!?
	 *
	 * @param string $logicalDeviceId the logical device id
	 * @param bool $value the new state of the device (true = on, false = off)
	 */
	public function addLogicalDeviceState($logicalDeviceId, $value)
	{
		$logicalDeviceState = $this->actuatorStates->addChild('LogicalDeviceState');
		$logicalDeviceState->addAttribute('xmlns:xsi:type', 'GenericDeviceState');
		$logicalDeviceState->addAttribute('LID', $logicalDeviceId);

		$ppts = $logicalDeviceState->addChild('Ppts');
		$ppt = $ppts->addChild('Ppt');
		$ppt->addAttribute('xmlns:xsi:type', 'BooleanProperty');
		$ppt->addAttribute('Name', 'Value');
		$ppt->addAttribute('Value', $value ? 'true' : 'false'); // text!
	}

	/**
	 * Set the shutter level of shutters
	 *
	 * @param string $logicalDeviceId the logical device id
	 * @param integer $shutterLevel the new shutter level of the device in percent (0 - 100)
	 */
	public function addRollerShutter($logicalDeviceId, $shutterLevel)
	{
		$logicalDeviceState = $this->actuatorStates->addChild('LogicalDeviceState');
		$logicalDeviceState->addAttribute('xmlns:xsi:type', 'RollerShutterActuatorState');
		$logicalDeviceState->addAttribute('LID', $logicalDeviceId);
		$logicalDeviceState->addChild('ShutterLevel', $shutterLevel);
	}
}
