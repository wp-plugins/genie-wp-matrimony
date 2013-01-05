<?php


/** Check if environment is development and display errors **/

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

/** Autoload any classes that are required **/

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
		throw new GwpmCommonException("Upload of profile photo failed. Invalid photo type (" . $pType . ") or Size greater than " . GWPM_IMAGE_MAX_SIZE . " kb");
	}
	return $photo;
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

function getEmployementStatusOptions($id = 'all') {
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

function getStrippedUserId($id) {
	$id = explode(GWPM_USER_PREFIX, $id ) ;
	return ($id[1]) ;
}

//setReporting();