<?php


/*
 * Created on Apr 28, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class GwpmActivityModel {

	public function addActivityLog($actType, $actText, $userid){
		global $wpdb;
		$table_name = $wpdb->prefix . "gwpm_activity";
		$datetime = date('Y-m-d H:i:s');
		$wpdb->insert($table_name, array("uid" => $userid, "act_time" => $datetime, "act_type" => $actType, "act_text" => $actText), array("%d", "%s", "%s", "%s"));
	}
	
	function getUserActivity($userId = null) {
		global $wpdb;
		if(!isset($userId) || $userId == "") $userId = get_current_user_id();
		$table_name = $wpdb->prefix . "gwpm_activity";
		$resultList = array ();
		$queryString = "SELECT act_time, act_type, act_text FROM $table_name " .
				" WHERE uid = %d AND act_type != %s ORDER BY act_time ASC limit 0, %d" ;
		$preparedSql = $wpdb->prepare($queryString, $userId, "login", GWPM_ACTIVITY_MAX_NOS);
		$result = $wpdb->get_results($preparedSql) ;
		foreach ($result as $obj) {
			if (isset ($obj) && $obj != null)
				array_push($resultList, $obj);
		}
		return $resultList ;
	}

}