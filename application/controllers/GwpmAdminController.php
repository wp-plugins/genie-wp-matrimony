<?php
class GwpmAdminController extends GwpmMainController {

	function view() {
		$vo = new GwpmAdminVO();
		$vo->userList = $this->_model->getSubscribedUsers() ;
		$this->set("model", $vo) ;
	}

} 