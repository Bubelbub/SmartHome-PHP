<?php

namespace Bubelbub\SmartHomePHP\Entity;

use Bubelbub\SmartHomePHP\Entity\LogicalDevice;

/**
 * @todo implement details?
 * @author Ollie
 *        
 */
class GenericSensor extends LogicalDevice {
	/**
	 */
	function __construct() {
		$this->setType(parent::DEVICE_TYPE_GENERIC_SENSOR);
	}
}