<?php
/*
 * Created on Oct 28, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 class GwpmMessagesController extends GwpmMainController {
 	
 	function view() {
		$f = 0;
		if(isset($_GET['f']) && !isNull($_GET['f'])) {
			$f = intval($_GET['f']);
		}
		$this->set('model', $this->_model->getMessagesList($f));
		//$this->set('model', $this->_model->getMessagesList($_GET['pid']));
	}

	function update() {
		$user = wp_get_current_user();
		$userId = $user->ID ;	
		$itrId = $_GET['pid'] ;
		$msgType = $_GET['type'] ;
		$send_message_result = $this->_model->updateMessage($userId, $itrId, "User " . $user->display_name . " showed interest on your profile.", $msgType) ;
		if($send_message_result == 1)
			$this->set('success_message', 'Interest updated to the user successfully!!');
		else if($send_message_result == 0)	
			$this->set('success_message', 'Interest sent to the user successfully!!');
		else if($send_message_result == -1)	
			$this->set('success_message', 'You have sent maximum number of interests. Please wait for the recepient response. !!');
	}
 }
 
?>
