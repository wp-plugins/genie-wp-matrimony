<?php
class GwpmAdminController extends GwpmMainController {

	function view() {
		$model = new GwpmAdminVO();
		$model->userList = $this->_model->getSubscribedUsers() ;
		$this->set("model", $model) ;
	}

} 