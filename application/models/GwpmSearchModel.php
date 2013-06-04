<?php

/*
 * Created on Apr 28, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class GwpmSearchModel {

	function getUserById($userId) {
		$userObj = get_userdata($userId);
		if (isset ($userObj) && isset ($userObj->ID) && !user_can($userObj->ID, 'level_10') && user_can($userObj->ID, 'matrimony_user')) {
			return $userObj;
		}
		return null;
	}

	function searchUsers($searchObj) {
		global $wpdb;
		$resultList = array ();
		if (!isNull($searchObj->userId)) {
			$tempObj = $this->getUserById(getStrippedUserId($searchObj->userId));
			if (isset ($tempObj) && $tempObj != null)
				array_push($resultList, $tempObj);
		} else {
			$counter = 0;
			$args = array ();
			$queryString = "SELECT DISTINCT ($wpdb->usermeta.user_id) FROM $wpdb->usermeta ";
			if (!isNull($searchObj->username)) {
				$queryString .= $this->appendWhereOr("meta_value LIKE '%s' AND ( meta_key = 'first_name' OR meta_key='last_name' )", $counter);
				$args[$counter] = $searchObj->username . '%';
				$counter++;
			}
			if (!isNull($searchObj->gwpm_address)) {
				$queryString .= $this->appendWhereOr("meta_value = '%s' AND meta_key = 'gwpm_address' ", $counter);
				$args[$counter] = $searchObj->gwpm_address;
				$counter++;
			}
			if (!isNull($searchObj->gwpm_gender)) {
				$queryString .= $this->appendWhereOr("meta_value = '%s' AND meta_key = 'gwpm_gender' ", $counter);
				$args[$counter] = $searchObj->gwpm_gender;
				$counter++;
			}
			if (!isNull($searchObj->gwpm_martial_status)) {
				$queryString .= $this->appendWhereOr("meta_value = '%s' AND meta_key = 'gwpm_martial_status' ", $counter);
				$args[$counter] = $searchObj->gwpm_martial_status;
				$counter++;
			}
			if (!isNull($searchObj->gwpm_zodiac)) {
				$queryString .= $this->appendWhereOr("meta_value = '%s' AND meta_key = 'gwpm_zodiac' ", $counter);
				$args[$counter] = $searchObj->gwpm_zodiac;
				$counter++;
			}
			if (!isNull($searchObj->gwpm_starsign)) {
				$queryString .= $this->appendWhereOr("meta_value = '%s' AND meta_key = 'gwpm_starsign' ", $counter);
				$args[$counter] = $searchObj->gwpm_starsign;
				$counter++;
			}
			if (!isNull($searchObj->gwpm_sevvai_dosham)) {
				$queryString .= $this->appendWhereOr("meta_value = '%s' AND meta_key = 'gwpm_sevvai_dosham' ", $counter);
				$args[$counter] = $searchObj->gwpm_sevvai_dosham;
				$counter++;
			}
			if (!isNull($searchObj->gwpm_caste)) {
				$queryString .= $this->appendWhereOr("meta_value = '%s' AND meta_key = 'gwpm_caste' ", $counter);
				$args[$counter] = $searchObj->gwpm_caste;
				$counter++;
			}
			if (!isNull($searchObj->gwpm_religion)) {
				$queryString .= $this->appendWhereOr("meta_value = '%s' AND meta_key = 'gwpm_religion' ", $counter);
				$args[$counter] = $searchObj->gwpm_religion;
				$counter++;
			}
			if (!isNull($searchObj->gwpm_has_photo)) {
				$queryString .= $this->appendWhereOr("(meta_key = 'gwpm_profile_photo' OR meta_key = 'gwpm_gallery_img' ) " .
						" AND (meta_value IS NOT NULL and meta_value != 'a:0:{}' )", $counter);
				$counter++;
			}
			if ($counter > 0 ) {
				$preparedSql = $wpdb->prepare($queryString, $args);
				$result = $wpdb->get_results($preparedSql);
				foreach($result as $obj) {
					$tempObj = $this->getUserById( $obj->user_id );
					if (isset ($tempObj) && $tempObj != null)
						array_push($resultList, $tempObj);
				}
			}
		}
		return $resultList;
	}

	private function appendWhereOr($str, $counter) {
		if ($counter == 0) {
			$str = " WHERE ( " . $str . " ) ";
		} else {
			$str = " OR ( " . $str . " ) ";
		}
		return $str;
	}

}