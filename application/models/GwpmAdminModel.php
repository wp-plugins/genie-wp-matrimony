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
		$preparedSql = $wpdb->prepare($queryString, 'wp_capabilities', '%subscriber%');
		$result = $wpdb->get_results($preparedSql) ;
		foreach ($result as $obj) {
			if (isset ($obj) && $obj != null)
				array_push($resultList, $obj);
		}
		return $resultList ;
	}

}
