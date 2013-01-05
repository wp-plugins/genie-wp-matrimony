<?php
abstract class Command {

	function Command($records) {
		if (is_array($records)) {
			foreach (array_keys(get_class_vars(get_class($this))) as $key) {
				if (isset ($records[$key])) {
					$this-> $key = $records[$key];
				}
			}
		}
	}

	function validate() {
		$invalidProperties = array ();
		foreach (array_keys(get_class_vars(get_class($this))) as $key) {
			$value = $this-> $key;
			$ref = new ReflectionProperty(get_class($this), $key);
			$docTagValue = $ref->getDocComment();
			if (strpos($docTagValue, 'mandatory') != false) {
				if (is_array($value) && sizeof($value) > 1) {
					foreach ($value as $key => $itrVal) {
						if (isNull($itrVal)) {
							array_push($invalidProperties, $this->getArrayMessage($docTagValue, $key) );
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
						array_push($invalidProperties, $this->getMessage($docTagValue, $key));
					}
				}
			}
		}
		return $invalidProperties;
	}

	function getMessage($message, $key) {
		$strAry = spliti('@message="', $message);
		if (isset ($strAry[1])) {
			$strAry = spliti('"', $strAry[1]);
			if (isset ($strAry[0])) {
				return $strAry[0];
			}
		}
		return "Enter Value for " . $key;
	}

	function getArrayMessage($message, $key) {
		$message = $this->getMessage($message, $key);
		$strAry = explode('##', $message);
		if (sizeof($strAry) > 1) {
			foreach ($strAry as $msgItrs) {
				$messagePairs = spliti(':', $msgItrs);
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

}