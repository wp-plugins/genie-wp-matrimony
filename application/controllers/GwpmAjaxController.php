<?php
/*
 * Created on May 3, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
class GwpmAjaxController {
	function processRequest($controlObj){
		$model = $controlObj["model"] ;
		switch ($model) {
			case "gallery_delete":
				$this->delete_gallery_photo($controlObj);
				break;
			case "dynafield_delete":
				$this->dynafield_delete($controlObj) ;
				break;
			case "dynafield_update":
				$this->dynafield_update($controlObj) ;
				break;
			default:
				echo "Invalid Ajax request." ;
		}
	}

	function delete_gallery_photo($controlObj) {
		$galleryModel = new GwpmGalleryModel();
		$galleryModel->delete($controlObj["userId"], $controlObj["val"]) ;
	}

	function dynafield_delete($controlObj) {
		$adminModel = new GwpmAdminModel() ;
		$adminModel-> deleteDynamicField($controlObj["val"]) ;
	}

	function dynafield_update($controlObj) {
		$adminModel = new GwpmAdminModel() ;
		$adminModel-> updateDynamicField($controlObj["val"]) ;
	}
}
