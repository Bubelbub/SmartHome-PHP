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
	 * @var integer
	 */
	protected $luminance = NULL;
	
	/**
	 */
	function __construct() {
		$this->setType(parent::DEVICE_TYPE_LUMINANCE_SENSOR);
	}
	
	/**
	 *
	 * @return the integer
	 */
	public function getLuminance() {
		return $this->luminance;
	}
	
	/**
	 *
	 * @param
	 *        	$luminance
	 */
	public function setLuminance($luminance) {
		$this->luminance = $luminance;
		return $this;
	}
	
}