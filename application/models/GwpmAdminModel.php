<?php

/*
 * Created on May 6, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class GwpmAdminModel {

	function getSubscribedUsers() {
		global $wpdb;
		$resultList = array ();
		$queryString = "SELECT $wpdb->users.ID, $wpdb->users.display_name, $wpdb->users.user_email FROM $wpdb->users " .
				" WHERE $wpdb->users.ID IN ( " .
				" SELECT $wpdb->usermeta.user_id FROM $wpdb->usermeta WHERE" .
				" $wpdb->usermeta.meta_key = %s AND $wpdb->usermeta.meta_value LIKE %s )";
		$preparedSql = $wpdb->prepare($queryString,  $wpdb->prefix . 'capabilities', '%subscriber%');
		$result = $wpdb->get_results($preparedSql) ;
		foreach ($result as $obj) {
			if (isset ($obj) && $obj != null)
				array_push($resultList, $obj);
		}
		return $resultList ;
	}
	
	function getAllMatrimonyUsers() {
		global $wpdb;
		$resultList = array ();
		$queryString = "SELECT $wpdb->users.ID, $wpdb->users.display_name, $wpdb->users.user_email FROM $wpdb->users " .
				" WHERE $wpdb->users.ID IN ( " .
				" SELECT $wpdb->usermeta.user_id FROM $wpdb->usermeta WHERE" .
				" $wpdb->usermeta.meta_key = %s AND $wpdb->usermeta.meta_value LIKE %s )";
		$preparedSql = $wpdb->prepare($queryString,  $wpdb->prefix . 'capabilities', '%matrimony_user%');
		$result = $wpdb->get_results($preparedSql) ;
		foreach ($result as $obj) {
			if (isset ($obj) && $obj != null)
				array_push($resultList, $obj);
		}
		return $resultList ;
	}

	function saveDynamicFields($gwpmFieldLabels, $gwpmFieldTypes, $gwpmFieldValue, $earlierCount) {

		global $wpdb ;
		$totalFields = get_option(GWPM_DYNA_FIELD_COUNT);
		appendLog( print_r($gwpmFieldLabels, true) );
		$keys = array_keys($gwpmFieldLabels)  ;
		// $deletedObjs = explode(',', $deletedItems) ;
		$save_options = null ;
		
		if($totalFields == false )
			$totalFields = 0 ;
		appendLog( $earlierCount . " - " . $totalFields );
		
		if($earlierCount == $totalFields) {
			foreach ($keys as $mKey) {
				$save_options = null ;
				appendLog("\nK: " . $mKey . "\n" );
				$save_options['gwpm_dyna_field_label'] = $gwpmFieldLabels[$mKey] ;
				$save_options['gwpm_dyna_field_type'] = $gwpmFieldTypes[$mKey] ;
				if($gwpmFieldTypes[$mKey] == "select" ) {
					appendLog( "Select Type" );
					$gwpm_field_values = $gwpmFieldValue[$mKey] ;
					appendLog( "zKey: " . $mKey . ' - ' . $gwpm_field_values );
					$itr = 1 ;
					$save_options_values = null ;
					foreach ($gwpm_field_values as $vKey) {
						appendLog( "vKey: " . $vKey . "<br />" );
						$save_options_values[$itr] = $vKey ;
						$itr++ ;
					}
					$save_options['gwpm_dyna_field_values'] = $save_options_values ;
				} else if($gwpmFieldTypes[$mKey] == 'yes_no') {
					appendLog( "Yes/No Type" );
				} else {
					appendLog( "Text Type" );
				}
				
				$totalFields++  ;
				
				appendLog( print_r($save_options, true) );
				$result = update_option (GWPM_DYNA_KEY_PREFIX . ($totalFields), $save_options) ;
				if($result == 1)
				update_option (GWPM_DYNA_FIELD_COUNT, ($totalFields)) ;
			}
		} else {
			throw new GwpmCommonException("Invalid Request") ;
		}
	}
	
	function deleteDynamicField($valObj) {
		if(isset($valObj) && $valObj != null) {
			if(strpos($valObj, '_')) {
				$idsAry = explode('_', $valObj) ;
				$rowId = $idsAry[0] ;
				$valueId = $idsAry[1] ;

				$editOption = get_option('gwpm_dyna_field_' . $rowId) ;
				$valuesAry = $editOption['gwpm_dyna_field_values'] ;
				 
				unset($valuesAry[$valueId]) ;
				 
				$editOption['gwpm_dyna_field_values'] =  $valuesAry ;
				update_option('gwpm_dyna_field_' . $rowId, $editOption) ;
				
				$resultObj['message'] = "Value for dynamic filed deleted successfully. " ; 
				$resultObj['result'] = 1 ;
			} else {
				$delResult = delete_option('gwpm_dyna_field_' . $valObj) ;
				$resultObj['message'] = "Dynamic field deleted successfully. ";
				$resultObj['result'] = 1 ;
			}
		} else {
			$resultObj['message'] = "Exception in deleting dynamic field !" ;
			$resultObj['result'] = 0 ;
		}
		echo json_encode( $resultObj ) ;
	}

	function updateDynamicField($valObj) {
		if (isset ($valObj) && $valObj != null && sizeof($valObj) > 0) {
			appendLog(print_r($valObj, true) );
			foreach ($valObj as $key => $all) {
				if (strpos($key, '_')) {
					$idsAry = explode('_', $key);
					$rowId = $idsAry[0];
					$valueId = $idsAry[1];

					$editOption = get_option('gwpm_dyna_field_' . $rowId);
					$valuesAry = $editOption['gwpm_dyna_field_values'];
					appendLog(print_r($valuesAry, true));
					$srcValue = $valuesAry[$valueId] ;
					appendLog("srcValue: " . $srcValue);
					
					$valuesAry[$valueId] = $valObj[$key] ;
					$editOption['gwpm_dyna_field_values'] = $valuesAry ;
					
					$result = update_option(GWPM_DYNA_KEY_PREFIX . ($rowId), $editOption);
					appendLog("valuesAry: " . $result);
					
					$resultObj['message'] = $valObj[$key] ;
					$resultObj['result'] = 1 ;
					
				} else {
					appendLog("vKey: " . $key . " - " . $valObj[$key] . " $$ ");
					$editOption = get_option(GWPM_DYNA_KEY_PREFIX . $key);
					appendLog("editOption: ");
					appendLog(print_r($editOption, true));
					$valuesAry = $editOption['gwpm_dyna_field_label'];
					$editOption['gwpm_dyna_field_label'] = $valObj[$key];
					appendLog("valuesAry: " . $valuesAry);
					$result = update_option(GWPM_DYNA_KEY_PREFIX . ($key), $editOption);
					appendLog("valuesAry: " . $result);
					
					$resultObj['message'] = $valObj[$key] ;
					$resultObj['result'] = 1 ;
				}
			} 
		} else {
			$resultObj['message'] = "Exception in deleting dynamic field !" ;
			$resultObj['result'] = 0 ;
		}
		echo json_encode( $resultObj ) ;
	}
}
