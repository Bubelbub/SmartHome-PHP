<?php
namespace Bubelbub\SmartHomePHP\Entity;

/**
 *
 * @author oliverkuhl
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