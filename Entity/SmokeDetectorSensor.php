<?php

namespace Bubelbub\SmartHomePHP\Entity;

use Bubelbub\SmartHomePHP\Entity\LogicalDevice;

/**
 *
 * @author Ollie
 *        
 */
class SmokeDetectorSensor extends LogicalDevice {
	
	/**
	 */
	function __construct() {
		$this->setType(parent::DEVICE_TYPE_SMOKE_DETECTOR_SENSOR);
	}
}