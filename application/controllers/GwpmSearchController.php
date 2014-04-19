<?php
class GwpmSearchController extends GwpmMainController {

	function view() {
		$this->set("model", $this->_model->getDynamicFieldData()) ;
	}
	
	function update() {
		$_keys = getDynamicFieldKeys() ;
		$searchObj = new GwpmSearchVO($_POST, $_keys);
		$this->set("model", $this->_model->searchUsers($searchObj)) ;
	}

}