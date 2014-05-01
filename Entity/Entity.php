<?php

namespace Bubelbub\SmartHomePHP\Entity;

class Entity {
	function getJsonData(){
		$var = get_object_vars($this);
		foreach($var as &$value){
			if(is_object($value) && method_exists($value,'getJsonData')){
				$value = $value->getJsonData();
			}
		}
		return $var;
	}
}