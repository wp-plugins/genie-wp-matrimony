<?php

/**
 * 
 * gwpm_shared.php is a shared repo of functions to access from anywhere inside the framework.
 * Just add a function and access by the name anywhere inside the framework from Controllers to Value objects.
 * Needs a Common Model to make Database operation, till then this files takes care of all the DB operations.
 * 
 * 
 */

/* Check if environment is development and display errors */

function setReporting() {
	error_reporting(E_ALL);
	if (DEVELOPMENT_ENVIRONMENT == true) {
		if(ini_set('display_errors', 'On') === false) {
			 // check incase if ini_set is not supported.
		}
	} else {
		if(ini_set('display_errors', 'Off') === false) {
			 // check incase if ini_set is not supported.
		} else {
			ini_set('log_errors', 'On');
			ini_set('error_log', ROOT . DS . 'tmp' . DS . 'logs' . DS . 'gwpm_error.log');
		}
	}
}

/* Autoload any classes that are required */

function __autoload_($className) {
	if (file_exists(GWPM_APPLICATION_URL . DS . 'controllers' . DS . $className . '.php')) {
		require_once (GWPM_APPLICATION_URL . DS . 'controllers' . DS . $className . '.php');
	} else
		if (file_exists(GWPM_APPLICATION_URL . DS . 'models' . DS . $className . '.php')) {
			require_once (GWPM_APPLICATION_URL . DS . 'models' . DS . $className . '.php');
		} else
			if (file_exists(GWPM_LIBRARY_URL . DS . $className . '.php')) {
				require_once (GWPM_LIBRARY_URL . DS . $className . '.php');
			} else {
				parent :: __autoload($className);
				throw new GwpmCommonException("Unable to load classname " . $className);
			}
}

function gwpm_echo($value) {
	if (is_array($value)) {
		$value = implode($value);
	}
	_e($value, 'genie_wp_matrimony');
}

function gwpm_get_display_name($pid = null) {
	$user = null ;
	if($pid != null) 
			$user = get_userdata( $pid );
		else 
			$user = wp_get_current_user();
	if( isset($user) && $user!= null)
		return $user->display_name;
	else
		return 'Not a valid user' ;
}

function validateOptionsId($opts, $id) {
	if (isset ($id) && is_array($id)) {
		$id = implode($id);
	}
	if ($id == 'all') {
		return $opts;
	} elseif (isset ($id) && $opts != '') {
		$idx = $id - 1 ;
		if($idx == -1) return '' ;
		else return $opts[$idx];
	}
}

function savePhotoToUploadFolder($photo, $userId, $photoId=null) {

	$thumb_width_size = 128;
	if($photoId == null) {
		$photoId = md5($userId) ;
	} else {
		$photoId = md5($userId) . "_" . $photoId ;
	}
	if ($photo["type"] == "image/gif") {
		$photoEXT = ".gif";
		$pType = 0;
	}
	elseif ($photo["type"] == "image/jpeg" || $photo["type"] == "image/pjpeg") {
		$photoEXT = ".jpg";
		$pType = 1;
	}
	elseif ($photo["type"] == "image/png") {
		$photoEXT = ".png";
		$pType = 2;
	}
	elseif ($photo["type"] == "image/x-png") {
		$photoEXT = ".png";
		$pType = 2;
	}
	
	if (isset ($pType) && ($photo["size"] < (GWPM_IMAGE_MAX_SIZE * 1024))) {
		if ($photo["error"] > 0) {
			throw GwpmCommonException("Upload of profile photo failed. Error Code: " . $photo["error"]);
		} else {
			$uploadURL = GWPM_GALLERY_DIR . URL_S . $userId . URL_S;
			$photo["name"] = $photoId . $photoEXT;
			$photo["thumb_name"] = $photoId . '_thumb' . $photoEXT;

			if(!is_dir(WP_CONTENT_DIR . URL_S . 'uploads')) {
				mkdir(WP_CONTENT_DIR . URL_S . 'uploads', 0755);
			}

			if (!is_dir(GWPM_GALLERY_DIR)) {
				mkdir(GWPM_GALLERY_DIR, 0755);
			}

			if (!is_dir($uploadURL)) {
				mkdir($uploadURL, 0755);
			}

			if (file_exists($uploadURL . $photoId . ".gif")) {
				unlink($uploadURL . $photoId . ".gif");
				unlink($uploadURL . $photoId . '_thumb' . ".gif");
			}
			if (file_exists($uploadURL . $photoId . ".jpg")) {
				unlink($uploadURL . $photoId . ".jpg");
				unlink($uploadURL . $photoId . '_thumb' . ".jpg");
			}
			if (file_exists($uploadURL . $photoId . ".png")) {
				unlink($uploadURL . $photoId . ".png");
				unlink($uploadURL . $photoId . '_thumb' . ".png");
			}

			switch ($pType) {
				case 0 :
					$src = imagecreatefromgif($photo["tmp_name"]);
					break;
				case 1 :
					$src = imagecreatefromjpeg($photo["tmp_name"]);
					break;
				case 2 :
					$src = imagecreatefrompng($photo["tmp_name"]);
					break;
				default :
					$src = $photo["tmp_name"];
			}

			list ($width, $height) = getimagesize($photo["tmp_name"]);
			$thumb_height_size = ($height / $width) * $thumb_width_size;
			$tmp = imagecreatetruecolor($thumb_width_size, $thumb_height_size);
			imagecopyresampled($tmp, $src, 0, 0, 0, 0, $thumb_width_size, $thumb_height_size, $width, $height);
			imagejpeg($tmp, $uploadURL . $photo["thumb_name"], 100);
			imagedestroy($src);
			imagedestroy($tmp);
			move_uploaded_file($photo["tmp_name"], $uploadURL . $photo["name"]);
			unset ($photo["tmp_name"]);
		}
	} else {
		throw new GwpmCommonException("Upload of profile photo failed. Invalid photo type (" . $pType . " - " . $photo["type"] . ") or Size greater than " . GWPM_IMAGE_MAX_SIZE . " kb");
	}
	return $photo;
}

function getDynamicFieldOptions($opts, $id = 'all') {
	return validateOptionsId($opts, $id);
}

function getDynamicFieldKeys() {
	
	global $wpdb ;
	$preparedSql = $wpdb->prepare("SELECT option_name FROM $wpdb->options WHERE option_name LIKE %s AND option_name <> 'gwpm_dyna_field_count'" , "gwpm_dyna_field_%") ;
	$keys_values = array() ;
	
	$result = $wpdb->get_results($preparedSql);
	foreach($result as $obj) {
		$keyVal = $obj->option_name ;
		if (isset ($keyVal) && $keyVal != null)
			array_push($keys_values, $keyVal);
	}
	return $keys_values ;
}

function getDynamicFieldData() {
	$dynamicFieldData = array() ;
	$totalDynamicFields = get_option(GWPM_DYNA_FIELD_COUNT);
	if (!isset ($totalDynamicFields) || $totalDynamicFields == null) {
		$totalDynamicFields = 0;
	}
	$dynamicFieldData['gwpm_dynamic_field_count'] = $totalDynamicFields;
	$dyna_field_item = null ;
	if( $totalDynamicFields > 0 ) {
		for($itr = 1; $itr <= $totalDynamicFields; $itr++) {
			$field_key = "gwpm_dyna_field_" . $itr ;
			$dyna_field_obj = get_option($field_key) ;
			if(isset($dyna_field_obj) && $dyna_field_obj != null) {
				$dyna_field_item[$field_key]['label'] = $dyna_field_obj['gwpm_dyna_field_label'] ;
				$dyna_field_item[$field_key]['type'] = $dyna_field_obj['gwpm_dyna_field_type'] ;
				if(isset($dyna_field_obj['gwpm_dyna_field_values']))
					$dyna_field_item[$field_key]['value'] = $dyna_field_obj['gwpm_dyna_field_values'] ; 
			}
		}
	}
	$dynamicFieldData['dyna_field_item'] = $dyna_field_item;
	return $dynamicFieldData ;
}

function getGenderOptions($id = 'all') {
	$opts = array (
		'Male',
		'Female',

		
	);
	return validateOptionsId($opts, $id);
}

function getPhysicalType($id = 'all') {
	$opts = array (
		'Slim',
		'Slender',
		'Average',
		'Fit',
		'Smart',
		'Athletic',
		'Muscular',
		'Thick',
		'Fatty',
		'Voluptuous',
		'Large',

		
	);
	return validateOptionsId($opts, $id);
}

function getStateOptions($id = 'all') {
	$opts = array (
		'Andhra Pradesh',
		'Arunachal Pradesh',
		'Assam',
		'Bihar',
		'Chhattisgarh',
		'Goa',
		'Gujarat',
		'Haryana',
		'Himachal Pradesh',
		'Jammu and Kashmir',
		'Jharkhand',
		'Karnataka',
		'Kerala',
		'Madhya Pradesh',
		'Maharashtra',
		'Manipur',
		'Meghalaya',
		'Mizoram',
		'Nagaland',
		'Orissa',
		'Punjab',
		'Rajasthan',
		'Sikkim',
		'Tamil Nadu',
		'Tripura',
		'Uttar Pradesh',
		'Uttarakhand',
		'West Bengal',
		'Andaman and Nicobar Islands',
		'Chandigarh',
		'Dadra and Nagar Haveli',
		'Daman and Diu',
		'Lakshadweep',
		'National Capital Territory of Delhi',
		'Puducherry',

		
	);
	return validateOptionsId($opts, $id);
}

function getMaritalOptions($id = 'all') {
	$opts = array (
		'Single',
		'Married',
		'Divorsed',

		
	);
	return validateOptionsId($opts, $id);
}

function getYesNoOptions($id = 'all') {
	$opts = array (
		'Yes',
		'No',

		
	);
	return validateOptionsId($opts, $id);
}

function getQualificationOptions($id = 'all') {
	$opts = array (
		'10th - SSLC/CBSE/ICSE',
		'12th - SSLC/CBSE/ICSE',
		'Diploma degree',
		'Bachelors degree',
		'Masters degree',
		'PhD / Post Doctoral',
		'Others',

		
	);
	return validateOptionsId($opts, $id);
}

function getEmploymentStatusOptions($id = 'all') {
	$opts = array (
		'Full-time',
		'Part-time',
		'Homemaker',
		'Retired',
		'Self-employed',
		'Student',
		'Work at home',
		'Unemployed',

		
	);
	return validateOptionsId($opts, $id);
}

function getZodiacOptions($id = 'all') {
	$opts = array (
		'Aries',
		'Taurus',
		'Gemini',
		'Cancer',
		'Leo',
		'Virgo',
		'Libra',
		'Scorpio',
		'Sagittarius',
		'Capricorn',
		'Aquarius',
		'Pisces',

		
	);
	return validateOptionsId($opts, $id);
}

function getStarSignOptions($id = 'all') {
	$opts = array (
		'Aswini',
		'Bharani',
		'Karthigai',
		'Rohini',
		'Mrigasheersham',
		'Thiruvaathirai',
		'Punarpoosam',
		'Poosam',
		'Aayilyam',
		'Makam',
		'Pooram',
		'Uthiram',
		'Hastham',
		'Chithirai',
		'Swaathi',
		'Visaakam',
		'Anusham',
		'Kettai',
		'Moolam',
		'Pooraadam',
		'Uthiraadam',
		'Thiruvonam',
		'Avittam',
		'Chathayam/Sadayam',
		'Poorattathi',
		'Uthirattathi',
		'Revathi',

		
	);
	return validateOptionsId($opts, $id);
}

function isNull($value) {
	if($value == null) {
		return true ;
	}
	$value = trim($value);
	if ($value == '') {
		unset ($value);
	}
	if (!isset ($value)) {
		return true;
	}
	return false;
}

function getGravatarImageForUser($userid, $isExplicit = null) {
	$profileImgName = get_user_meta($userid, "gwpm_profile_photo", true);
	appendLog("profileImgName: " . print_r($profileImgName, true)) ;
	
	if(isset($profileImgName['thumb_name'])) {
		$imageName = $profileImgName['thumb_name'] ;
		$imageURL = GWPM_GALLERY_URL . URL_S . $userid . URL_S . $imageName ; 
		appendLog("image url : " . $imageURL) ;
	} else {
		$imageURL = GWPM_PUBLIC_IMG_URL . URL_S . 'gwpm_icon.png' ;
	}
	
	if(isset($isExplicit)) {
		return '<img width="48" height="48" class="avatar avatar-48 photo" src="' . $imageURL . '" alt="Profile PIC">' ;
	} else {
		return $imageURL ;
	}
}

function getStrippedUserId($id) {
	$id = explode(GWPM_USER_PREFIX, $id ) ;
	return ($id[1]) ;
}


function appendLog($message) {
	if(GWPM_ENABLE_DEBUGGING == true) {
		$trace = debug_backtrace();
	    $line   = $trace[0]['line'];
	    $callerName = $trace[1]['object'];
	    $functionName = $trace[1]['function'];
	    if (is_object($callerName)) { $callerName = get_class($callerName); }
	    else { $callerName = "ANON"; }
	    $logDir = ini_get('upload_tmp_dir');
	    $logDir = $logDir ? $logDir : sys_get_temp_dir(); 
		$file =  $logDir . DS . 'gwpm_error.log' ;
		
		if(is_array($message) || is_object($message)) {
			$message = print_r($message, true) ;
		}
		// file_put_contents($file, "\r\n" . date("Y-m-d H:i:s") . ": " . print_r($trace, true) , FILE_APPEND );
		file_put_contents($file, "\r\n" . date("Y-m-d H:i:s") . ": " . $callerName . "." . $functionName . "():" . $line . ": " . $message , FILE_APPEND );
	}
}

//setReporting();