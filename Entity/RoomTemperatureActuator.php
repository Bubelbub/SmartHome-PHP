<?php
namespace Bubelbub\SmartHomePHP\Entity;

/**
 *
 * @author Ollie
 *        
 */
class RoomTemperatureActuator extends Actuator {
	
	const ROOM_TEMPERATURE_ACTUATOR_MODE_MANUAL = 'MANUAL';
	const ROOM_TEMPERATURE_ACTUATOR_MODE_AUTO = 'AUTOMATIC';
	const ROOM_TEMPERATURE_ACTUATOR_WINDOW_REDUCTION_ACTIVE = 'ACTIVE';
	const ROOM_TEMPERATURE_ACTUATOR_WINDOW_REDUCTION_INACTIVE = 'INACTIVE';

	
	/**
	 * @var float
	 */
	protected $pointTemperature = NULL;
	
	/**
	 * @var string
	 */
	protected $operationMode = NULL;
	
	/**
	 * @var boolean
	 */
	protected $windowReductionMode = NULL;
	
	/**
	 * @var array
	 */
	protected $underlyingDeviceIds = array();
	
	/**
	 * @var float maximal temperature
	 */
	protected $maxTemperature = NULL;
	
	/**
	 * @var float minimal temperature
	 */
	protected $minTemperature = NULL;
	
	/**
	 * @var float
	 */
	protected $preheatFactor = NULL;
	
	/**
	 * @var boolean
	 */
	protected $isLocked = NULL;
	
	/**
	 * @var boolean
	 */
	protected $isFreezeProtectionActivated = NULL;
	
	/**
	 * @var float
	 */
	protected $freezeProtection = NULL;
	
	/**
	 * @var boolean
	 */
	protected $isMoldProtectionActivated = NULL;
	
	/**
	 * @var float
	 */
	protected $humidityMoldProtection = NULL;
	
	/**
	 * @var float
	 */
	protected $windowOpenTemperature = NULL;
	
	
	
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
	
	/**
	 * @return array:
	 */
	public function getUnderlyingDeviceIds() {
		return $this->underlyingDeviceIds;
	}
	
	/**
	 * @param array $underlyingDeviceIds
	 * @return \Bubelbub\SmartHomePHP\Entity\RoomTemperatureActuator
	 */
	public function setUnderlyingDeviceIds($underlyingDeviceIds) {
		$this->underlyingDeviceIds = $underlyingDeviceIds;
		return $this;
	}
	
	/**
	 *
	 * @return the float
	 */
	public function getPointTemperature() {
		return $this->pointTemperature;
	}
	
	/**
	 *
	 * @param $pointTemperature
	 */
	public function setPointTemperature($pointTemperature) {
		$this->pointTemperature = $pointTemperature;
		return $this;
	}
	
	/**
	 *
	 * @return the string
	 */
	public function getOperationMode() {
		return $this->operationMode;
	}
	
	/**
	 *
	 * @param
	 *        	$operationMode
	 */
	public function setOperationMode($operationMode) {
		$this->operationMode = $operationMode;
		return $this;
	}
	
	/**
	 *
	 * @return the boolean
	 */
	public function getWindowReductionMode() {
		return $this->windowReductionMode;
	}
	
	/**
	 *
	 * @param
	 *        	$windowReductionMode
	 */
	public function setWindowReductionMode($windowReductionMode) {
		if(!$windowReductionMode == (self::ROOM_TEMPERATURE_ACTUATOR_WINDOW_REDUCTION_ACTIVE or self::ROOM_TEMPERATURE_ACTUATOR_WINDOW_REDUCTION_INACTIVE))
			throw new \Exception('Invalid windowReductionMode "'.$windowReductionMode.'"');
		$this->windowReductionMode = $windowReductionMode;
		return $this;
	}
	
	

	
	
	
}

?>