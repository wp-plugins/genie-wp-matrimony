<?php
/*
 * Created on Apr 29, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class GwpmIndexController extends GwpmMainController {

	function view() {
		$this->set('model', $this->_model->getAllNotifications());
	}

}