<?php
/**
 * Created with IntelliJ IDEA.
 * Project: SmartHome PHP
 * User: Bubelbub <bubelbub@gmail.com>
 * Date: 07.12.13
 * Time: 16:43
 */

namespace Bubelbub\SmartHomePHP\Request;

/**
 * Class SetActuatorStatesRequest
 * @package Bubelbub\SmartHomePHP\Request
 * @author Bubelbub <bubelbub@gmail.com>
 */
class SetActuatorStatesRequest extends BaseRequest
{
	/**
	 * @var array with actuator states
	 */
	private $actuatorStates = array();

	public function send($expectedResponse = 'ControlResultResponse', $useSession = true, $try = 1)
	{
		if($this->smartHome->getConfigVersion() === null)
		{
			$this->smartHome->login();
		}
		$request = $this->getRequest();
		$request->addAttribute('BasedOnConfigVersion', $this->smartHome->getConfigVersion());

		$actuatorStates = $request->addChild('ActuatorStates');
		foreach($this->actuatorStates as $actuatorState)
		{
			$logicalDeviceState = $actuatorStates->addChild('LogicalDeviceState');
			foreach($actuatorState as $key => $value)
			{
				$logicalDeviceState->addAttribute($key, $value);
			}
		}
		return parent::send($expectedResponse, $useSession, $try);
	}

	/**
	 * @param string $logicalDeviceId the logical device id
	 * @param string|float $pointTemperature the temperature to set
	 * @param string $mode the mode of temperature actuator (Auto|Manu)
	 */
	public function addRoomTemperatureActuatorState($logicalDeviceId, $pointTemperature, $mode)
	{
		$this->actuatorStates[] = array(
			'xmlns:xsi:type' => 'RoomTemperatureActuatorState',
			'LID' => $logicalDeviceId,
			'PtTmp' => $pointTemperature,
			'OpnMd' => $mode,
			'WRAc' => false
		);
	}
	
	/**
	 * @param string $logicalDeviceId the logical device id
	 * @param bool $value the state to set
	 */
	public function addSwitchActuatorState($logicalDeviceId, $value)
	{
		$this->actuatorStates[] = array(
			'xmlns:xsi:type' => 'SwitchActuatorState',
			'LID' => $logicalDeviceId,
			'IsOn' => $value
		);
	}
}