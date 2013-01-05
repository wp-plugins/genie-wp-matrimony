<?php
class GwpmSearchController extends Controller {

	function update() {
		$searchObj = new GwpmSearchVO($_POST);
		$this->set("model", $this->_model->searchUsers($searchObj)) ;
	}

}