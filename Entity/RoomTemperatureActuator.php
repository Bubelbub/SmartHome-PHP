<?php
namespace Bubelbub\SmartHomePHP\Entity;

/**
 *
 * @author oliverkuhl
 *        
 */
class RoomTemperatureActuator extends Actuator {
	
	/**
	 * @var float maximal temperature
	 */
	private $maxTemperature = NULL;
	
	/**
	 * @var float minimal temperature
	 */
	private $minTemperature = NULL;
	
	/**
	 * @var float
	 */
	private $preheatFactor = NULL;
	
	/**
	 * @var boolean
	 */
	private $isLocked = NULL;
	
	/**
	 * @var boolean
	 */
	private $isFreezeProtectionActivated = NULL;
	
	/**
	 * @var float
	 */
	private $freezeProtection = NULL;
	
	/**
	 * @var boolean
	 */
	private $isMoldProtectionActivated = NULL;
	
	/**
	 * @var float
	 */
	private $humidityMoldProtection = NULL;
	
	/**
	 * @var float
	 */
	private $windowOpenTemperature = NULL;
	
	
	
	/**
	 */
	function __construct() {
		$this->setType(parent::DEVICE_TYPE_ROOM_TEMPERATURE_ACTUATOR);
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getMaxTemperature() {
		return $this->maxTemperature;
	}
	
	/**
	 *
	 * @param float $maxTemperature        	
	 */
	public function setMaxTemperature($maxTemperature) {
		$this->maxTemperature = $maxTemperature;
		return $this;
	}
	
	/**
	 *
	 * @return float
	 */
	public function getMinTemperature() {
		return $this->minTemperature;
	}
	
	/**
	 *
	 * @param float $minTemperature        	
	 */
	public function setMinTemperature($minTemperature) {
		$this->minTemperature = $minTemperature;
		return $this;
	}
	
	/**
	 *
	 * @return float
	 */
	public function getPreheatFactor() {
		return $this->preheatFactor;
	}
	
	/**
	 *
	 * @param float $preheatFactor        	
	 */
	public function setPreheatFactor($preheatFactor) {
		$this->preheatFactor = $preheatFactor;
		return $this;
	}
	
	/**
	 *
	 * @return boolean
	 */
	public function getIsLocked() {
		return $this->isLocked;
	}
	
	/**
	 *
	 * @param boolean $isLocked        	
	 */
	public function setIsLocked($isLocked) {
		$this->isLocked = $isLocked;
		return $this;
	}
	
	/**
	 *
	 * @return float
	 */
	public function getIsFreezeProtectionActivated() {
		return $this->isFreezeProtectionActivated;
	}
	
	/**
	 *
	 * @param boolean $isFreezeProtectionActivated        	
	 */
	public function setIsFreezeProtectionActivated($isFreezeProtectionActivated) {
		$this->isFreezeProtectionActivated = $isFreezeProtectionActivated;
		return $this;
	}
	
	/**
	 *
	 * @return float
	 */
	public function getFreezeProtection() {
		return $this->freezeProtection;
	}
	
	/**
	 *
	 * @param float $freezeProtection        	
	 */
	public function setFreezeProtection($freezeProtection) {
		$this->freezeProtection = $freezeProtection;
		return $this;
	}
	
	/**
	 *
	 * @return boolean
	 */
	public function getIsMoldProtectionActivated() {
		return $this->isMoldProtectionActivated;
	}
	
	/**
	 *
	 * @param boolean $isMoldProtectionActivated        	
	 */
	public function setIsMoldProtectionActivated($isMoldProtectionActivated) {
		$this->isMoldProtectionActivated = $isMoldProtectionActivated;
		return $this;
	}
	
	/**
	 *
	 * @return float
	 */
	public function getHumidityMoldProtection() {
		return $this->humidityMoldProtection;
	}
	
	/**
	 *
	 * @param float $humidityMoldProtection        	
	 */
	public function setHumidityMoldProtection($humidityMoldProtection) {
		$this->humidityMoldProtection = $humidityMoldProtection;
		return $this;
	}
	
	/**
	 *
	 * @return float
	 */
	public function getWindowOpenTemperature() {
		return $this->windowOpenTemperature;
	}
	
	/**
	 *
	 * @param float $windowOpenTemperature        	
	 */
	public function setWindowOpenTemperature($windowOpenTemperature) {
		$this->windowOpenTemperature = $windowOpenTemperature;
		return $this;
	}
	
}

?>