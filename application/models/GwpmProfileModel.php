<?php

/*
 * Created on May 4, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class GwpmProfileModel {
	function getUserObj($pid = null) {
		if($pid != null)
		$user = get_userdata( $pid );
		else
		$user = wp_get_current_user();
			
		appendLog("InGWPM User Model: " . get_option( GWPM_USER_LOGIN_PREF ) ) ;
			
		$userMeta = get_user_meta($user->ID);

		if ($userMeta != null ) {

			/*foreach ($userMeta as $key => $value) {
			 echo $key . ':' ;
			 print_r( $value ) ;
			 echo '<br />' ;
			 }
			 print_r($user) ;
			 foreach ($user as $key => $value) {
			 echo $key . ':' ;
			 print_r( $value ) ;
			 echo '</ br>' ;
			 }
			 $op =  unserialize( $userMeta['gwpm_physical'][0]  );
			 // get_user_option('gwpm_physical') ;
			 print_r($op) ;
			 **/

			$_keys = getDynamicFieldKeys() ;

			$userObj = new GwpmProfileVO($userMeta, $_keys);
			$userObj->userId = $user->ID;
			$userObj->user_email = $user->user_email;
			$userObj->gender = 'Male';
			$userObj->gwpm_physical = unserialize($userObj->gwpm_physical[0]);
			$userObj->gwpm_address = unserialize($userObj->gwpm_address[0]);
			$userObj->gwpm_education = unserialize($userObj->gwpm_education[0]);
			$userObj->gwpm_work = unserialize($userObj->gwpm_work[0]);
			$userObj->gwpm_tmasvs = unserialize($userObj->gwpm_tmasvs[0]);
			$userObj->gwpm_profile_photo = unserialize($userObj->gwpm_profile_photo[0]);

			foreach($_keys as $key) {
				$tempValObj = $userObj->$key ;
				$tempVal = $tempValObj[0] ;
				$userObj->$key = $tempVal;
			}

			/*foreach (array_keys(get_class_vars(get_class($userObj))) as $key) {
			 $value = $userObj-> $key;
			 echo $key . ' - ' ;
			 print_r($value) ;
			 echo '</br>' ;
			 }*/
			$totalCount = get_option(GWPM_DYNA_FIELD_COUNT);
			if (!isset ($totalCount) || $totalCount == null) {
				$totalCount = 0;
			}
			$userObj->gwpm_dynamic_field_count = $totalCount;
			$inval = $userObj->validate();
			//print_r($inval) ;
			return $userObj;
		} else {
			appendLog("Empty User Model obtained for PID: " . $pid) ;
		}
	}

	function updateUser($userObj) {
		global $gwpm_activity_model;
		$isGwpmUser = get_user_meta($userObj->userId, 'gwpm_user');
		if (isset ($isGwpmUser) && sizeof($isGwpmUser) > 0) {

		} else {
			appendLog (add_user_meta($userObj->userId, 'gwpm_user', true, true));
		}

		$processKeys = array_keys(get_class_vars(get_class($userObj))) ;
		$dynaKeys = getDynamicFieldKeys() ;
		foreach($dynaKeys as $__keys) {
			array_push($processKeys, $__keys) ;
		}

		foreach ($processKeys as $key) {
			appendLog( $key ) ;
			if ($key == "gwpm_profile_photo") {
				$photoObj = $userObj-> $key;
				if ($photoObj["size"] != 0) {
					$value = savePhotoToUploadFolder($userObj-> $key, $userObj->userId);
					$gwpm_activity_model->addActivityLog("profile", "Updated Profile Image", $userObj->userId);
				} else
				continue;
			}
			elseif (!is_array($userObj-> $key)) $value = trim($userObj-> $key);
			else
			$value = $userObj-> $key;
			if ($key != 'userId' && $key != 'dynamicFields' && $key != 'dynamicFieldsValidation') {
				update_user_meta($userObj->userId, $key, $value);
			}
		}
		$gwpm_activity_model->addActivityLog("profile", "Updated Profile", $userObj->userId);
	}

	/*
	 *
	 function saveProfilePhoto($photo, $userId) {
		if ((($photo["type"] == "image/gif") || ($photo["type"] == "image/jpeg") || ($photo["type"] == "image/pjpeg")) && ($photo["size"] < 1000000)) {
			if ($photo["error"] > 0) {
				throw GwpmCommonException("Upload of profile photo failed. Error Code: " . $photo["error"]) ;
			} else {
				$uploadURL = GWPM_GALLERY_DIR . URL_S . $userId . URL_S ;
				$photo["name"] = md5($userId) . substr( $photo["name"], strpos($photo["name"], '.'), strlen($photo["name"]) );
				if (!is_dir($uploadURL)) {
					mkdir(GWPM_GALLERY_DIR , 0777);
					mkdir($uploadURL , 0777);
				}
				if (file_exists($uploadURL . URL_S . $photo["name"])) {
					unlink( $uploadURL . $photo["name"]) ;
				}
				move_uploaded_file($photo["tmp_name"], $uploadURL . $photo["name"]);
				unset($photo["tmp_name"]) ;
			}
		} else {
			throw GwpmCommonException("Upload of profile photo failed.") ;
		}
		return $photo ;
	}
	 * 
	 */

}