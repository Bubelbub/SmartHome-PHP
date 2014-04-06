<?php

namespace Bubelbub\SmartHomePHP\Entity;

/**
 * Class Location
 * 
 * @package Bubelbub\SmartHomePHP\Entity
 * @author Ollie
 *        
 */
class Location {
	
	private $id;
	private $name;
	private $position;
	
	function __construct($id, $name, $position) {
		$this->id = $id;
		$this->name = $name;
		$this->position = $position;
	}
	
	function getId() {
		return $this->id;
	}
	
	function setId($id) {
		$this->id = $id;
	}
	
	function getName() {
		return $this->name;
	}
	
	function setName($name) {
		$this->name = $name;
	}
	
	function getPosition() {
		return $this->position;
	}
	
	function setPosition($position) {
		$this->position = $position;
	}
	
	function __toString() {
		return $this->name;
	}
}

?>