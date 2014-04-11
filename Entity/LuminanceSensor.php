<?php

namespace Bubelbub\SmartHomePHP\Entity;

use Bubelbub\SmartHomePHP\Entity\LogicalDevice;

/**
 *
 * @author Ollie
 *        
 */
class LuminanceSensor extends LogicalDevice {
	
	/**
	 */
	function __construct() {
		$this->setType(parent::DEVICE_TYPE_LUMINANCE_SENSOR);
	}
}