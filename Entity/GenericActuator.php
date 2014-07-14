<?php

namespace Bubelbub\SmartHomePHP\Entity;

use Bubelbub\SmartHomePHP\Entity\Actuator;

/**
 * @todo implement details?
 * @author Ollie
 *        
 */
class GenericActuator extends Actuator {
	/**
	 */
	function __construct() {
		$this->setType(parent::DEVICE_TYPE_GENERIC_ACTUATOR);
	}
}