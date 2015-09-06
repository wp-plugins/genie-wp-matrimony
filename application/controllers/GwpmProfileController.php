<?php
class GwpmProfileController extends GwpmMainController {

	function view() {
		if(isset($_GET['pid'])) $pid = $_GET['pid'] ;
		else $pid = null ;
		$this->set('model', $this->_model->getUserObj($pid));
	}

	function edit() {
		$this->set('model', $this->_model->getUserObj());
	}

	function update() {
		
		$_keys = getDynamicFieldKeys() ;
		$profileObj = new GwpmProfileVO($_POST, $_keys);
		$profileObj->gwpm_profile_photo = $_FILES["gwpm_profile_photo"] ;
		$validateObj = $profileObj->validate();
		if (sizeof($validateObj) == 0) {
			$this->_model->updateUser($profileObj);
			$this->set('success_message', 'Profile updated successfully!!');
		} else {
			$this->set('error_messages', $validateObj);
			$this->set('warn_message', 'Please correct the below fields');
		}
	}

}