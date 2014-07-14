<?php
namespace Bubelbub\SmartHomePHP\Entity;

/**
 *
 * @author Ollie
 *        
 */
class ThermostatActuator extends Actuator {
	
	/**
	 */
	function __construct() {
		$this->setType(parent::DEVICE_TYPE_THERMOSTAT_ACTUATOR);
	}
}

?>