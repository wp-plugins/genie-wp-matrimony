<?php

/*
 * Created on Apr 28, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class GwpmIndexModel {
	
	function getAllNotifications() {
		$notificationVO = new GwpmNotificationVO() ;
		$notificationVO->unreadMessages = $this->getUnreadMessages() ;
		return $notificationVO ;
	}
	
	function getUnreadMessages() {
		global $wpdb;
		$userId = get_current_user_id();
		print_r ( $userId );
		$messages_table_name = $wpdb->prefix . "gwpm_messages";	
		$queryString = "SELECT count(mid) counts " .
				"FROM " . $messages_table_name ;
				"WHERE recipient_id =%d and unread=1";
		$preparedSql = $wpdb->prepare($queryString, $userId);
		$result = $wpdb->get_results($preparedSql) ;
		$resultVal = $result[0] ;
		return $resultVal->counts ;
	}
}