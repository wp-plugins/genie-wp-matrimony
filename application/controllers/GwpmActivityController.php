<?php
class GwpmActivityController extends GwpmMainController {

	function addActivityLog() {
		$this->_model->addActivityLog("login", "sample");
	}
	
	function view() {
		if(isset($_GET['pid'])) $pid = $_GET['pid'] ;
		else $pid = null ;
		$this->set('model', $this->_model->getUserActivity($pid));
	}

}