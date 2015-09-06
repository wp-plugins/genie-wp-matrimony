<?php
/*
 * Created on Oct 28, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 
 class GwpmMessagesModel {
	function getMessagesList($f = 0) {
		global $wpdb;
		$userId = get_current_user_id();
		$t = $f + GWPM_CONVERSE_MAX_NOS ;
		$messages_table_name = $wpdb->prefix . "gwpm_messages";
		$user_table_name = $wpdb->prefix . "users";
		$resultList = array ();		
		$queryString = "SELECT a.pmid, a.user_id, a.user_message, a.created, b.display_name " .
				"FROM " . $messages_table_name . " a, " . $user_table_name . " b " .
				"WHERE a.user_id=b.id AND a.recipient_id =%d  ORDER BY created DESC LIMIT %d, %d";
		$preparedSql = $wpdb->prepare($queryString, $userId, $f, $t);
		appendLog($preparedSql) ;
		$result = $wpdb->get_results($preparedSql) ;
		foreach ($result as $obj) {
			if (isset ($obj) && $obj != null) {
				array_push($resultList, $obj);
			}
		}
		return $resultList ;
	}
	
	function updateMessage($uid, $rid, $msg, $type, $pmid = null) {
		global $wpdb;
		$messages_table_name = $wpdb->prefix . "gwpm_messages";
		$user_table_name = $wpdb->prefix . "users";
		
		if($type == 'int' && $pmid == null) {
			$queryString = "SELECT msgtab.unread msgcount FROM " . $messages_table_name . " msgtab, " . $user_table_name . " usertab " .
					" WHERE msgtab.pmid=-1 AND msgtab.user_id=%d AND msgtab.recipient_id=%d AND usertab.id=%d" ;
			$preparedSql = $wpdb->prepare($queryString, $uid, $rid, $rid);
			appendLog($preparedSql) ;
			$result = $wpdb->get_results($preparedSql) ;
			$obj = $result[0] ;
			$datetime = date('Y-m-d H:i:s');
			$endVal = -1 ;
			if (isset ($obj) && $obj != null && $obj->msgcount > 0) {
				if($obj->msgcount < GWPM_MAX_USER_MESSAGES ) {
					$new_val = $obj->msgcount++ ;
					appendLog("new value : " + $new_val) ;
					$endVal = $wpdb->update($messages_table_name, array("unread" => ($obj->msgcount++), "created" => $datetime ), array("pmid" => '-1', "user_id" => $uid, "recipient_id" => $rid)) ;
				}
			} else {
				$wpdb->insert($messages_table_name, array("pmid" => '-1', "created" => $datetime, "user_id" => $uid, "recipient_id" => $rid, "user_message" => $msg, "folder" => 'sent', "unread" => '1' ), array("%s", "%s", "%d", "%d", "%s", "%s", "%d"));
				$endVal = 0;
			}
		}
		return $endVal ;
	}
	
 }
 
 
?>
