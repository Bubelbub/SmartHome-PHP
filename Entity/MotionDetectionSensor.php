<?php

namespace Bubelbub\SmartHomePHP\Entity;

use Bubelbub\SmartHomePHP\Entity\LogicalDevice;

/**
 *
 * @author Ollie
 *        
 */
class MotionDetectionSensor extends LogicalDevice {
	
	/**
	 */
	function __construct() {
		$this->setType(parent::DEVICE_TYPE_MOTION_DETECTION_SENSOR);
	}
}