<?php

namespace Bubelbub\SmartHomePHP\Entity;

/**
 * Class Location
 * 
 * @package Bubelbub\SmartHomePHP\Entity
 *        
 */
class Location {
	
	/**
	 * @var string $id ID of the location
	 */
	private $id;
	
	
	/**
	 * @var string $name Name of the location
	 */
	private $name;
	
	/**
	 * @var integer position The position of the location
	 */
	private $position;
	
	/**
	 * Constructs a location object with the given parameters.
	 * 
	 * @param string $id ID of the location
	 * @param unknown $name Name of the location
	 * @param unknown $position Position of the location
	 */
	function __construct($id, $name, $position) {
		$this->id = $id;
		$this->name = $name;
		$this->position = $position;
	}
	
	/**
	 * Returns the ID of the location.
	 * 
	 * @return string
	 */
	function getId() {
		return $this->id;
	}
	
	/**
	 * Sets the location ID.
	 * 
	 * @param string $id
	 */
	function setId($id) {
		$this->id = $id;
	}
	
	/**
	 * Returns the name of the location.
	 * 
	 * @return string
	 */
	function getName() {
		return $this->name;
	}
	
	/**
	 * Sets the location name.
	 * 
	 * @param string $name
	 */
	function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * Returns the position
	 * 
	 * @return number
	 */
	function getPosition() {
		return $this->position;
	}
	
	/**
	 * Sets the position
	 * 
	 * @param integer $position
	 */
	function setPosition($position) {
		$this->position = $position;
	}
	
	function __toString() {
		return $this->name;
	}
	
}

?>