<?php

/**
 * Base Command Object for all the value object
 * Contains the methods to directly map the values in $_POST or $_GET object into the Value objects,
 * Automates the validation of the objects with the annotations 
 * and returns the error messages based on the annotations
 * 
 */
abstract class GwpmCommand {

	var $containsDynamicField = false;
	var $dynamicFieldsValidation = null ;
	var $dynamicFields = null ;
	
	/*
	 * Constructor for the command object
	 */
	function GwpmCommand($records, $dynamicFields = null, $dynamicFieldsValidation = null) {
		/*
		 * Fetch values from $_POST or $_GET and set in value object
		 */
		if (is_array($records)) {
			foreach (array_keys(get_class_vars(get_class($this))) as $key) {
				if (isset ($records[$key])) {
					$this-> $key = $records[$key];
				}
			}
		}
		/*
		 * Sets the value for the Dynamic fields
		 */
		if ($dynamicFields != null && is_array($dynamicFields) ) {
			foreach ($dynamicFields as $key) {
				$this -> $key = $records[$key] ;
			}
			$this-> containsDynamicField = true ;
			$this-> dynamicFields = $dynamicFields ;
			$this-> dynamicFieldsValidation = $dynamicFieldsValidation ;
		} else {
			$this-> containsDynamicField = false;
		}
		print_r ($this-> containsDynamicField) ;	
	}

	/**
	 * Method to validate the value object with the madatory fields
	 * Uses the reflections to read the annotated objects
	 * and set error message based on the text
	 */
	function validate() {
		$invalidProperties = array ();
		foreach (array_keys(get_class_vars(get_class($this))) as $key) {
			$value = $this-> $key;
			$ref = new ReflectionProperty(get_class($this), $key);
			$docTagValue = $ref->getDocComment();
			if (strpos($docTagValue, 'mandatory') != false) {
				if(!$this->checkIfNull($value, $key)) {
					array_push($invalidProperties, $this->getMessage($docTagValue, $key));
				}
			}
		}
		print_r($this->containsDynamicField) ;
		if($this->containsDynamicField) {
			foreach ($this->dynamicFields as $key) {
				$value = $this-> $key;
				if(!$this->checkIfNull($value, $key)) {
					$messageValue = $this->dynamicFieldsValidation[$key] ;
					if(!isset($messageValue) || $messageValue == null) {
						 $messageValue = "Enter Value for " . $key;
					}
					array_push($invalidProperties, $messageValue);
				}
			}
		}
		return $invalidProperties;
	}

	/**
	 * Reads the message for the errored object
	 * @param $message
	 * @param $key
	 */
	function getMessage($message, $key) {
		$strAry = explode('@message="', $message);
		if (isset ($strAry[1])) {
			$strAry = explode('"', $strAry[1]);
			if (isset ($strAry[0])) {
				return $strAry[0];
			}
		}
		return "Enter Value for " . $key;
	}

	/**
	 * Reads the message for the errored object
	 * if it is an array type
	 * @param $message
	 * @param $key
	 */
	function getArrayMessage($message, $key) {
		$message = $this->getMessage($message, $key);
		$strAry = explode('##', $message);
		if (sizeof($strAry) > 1) {
			foreach ($strAry as $msgItrs) {
				$messagePairs = explode(':', $msgItrs);
				foreach ($messagePairs as $mkey => $mval) {
					if (strpos($mkey, $key) != false) {
						return trim($mval);
					}
				}
			}
		} else {
			if (!isNull($strAry)) {
				return implode($strAry);
			}
		}
		return "Enter Value for " . $key;
	}
	
	/*
	 * Checks the value for the key, if array or primitive,
	 * If null or empty returns false, else true 
	 */
	function checkIfNull($value, $key) {
		if (is_array($value) && sizeof($value) > 1) {
			foreach ($value as $key => $itrVal) {
				if (isNull($itrVal)) {
					return false ;
				}
			}
		} else {
			if (is_array($value) ) {
				$value = implode($value) ;
			}
			$value = trim($value);
			if ($value == '') {
				unset ($value);
			}
			if (!isset ($value)) {
				return false;
			}
		}
		return true ;
	}

}