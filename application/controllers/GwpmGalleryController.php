<?php
class GwpmGalleryController extends GwpmMainController {

	function view() {
		if(isset($_GET['pid'])) $pid = $_GET['pid'] ;
		else $pid = null ;
		$this->set('model', $this->_model->getGalleryImages($pid));
	}
	
	function edit() {
		$this->set('model', $this->_model->getGalleryImages());
	}
	
	function update() {
		
		 $user = wp_get_current_user();
		 $galleryObj = new GwpmGalleryVO() ;
		 $galleryObj->gwpm_gallery_img = $this->rearrange($_FILES['gwpm_gallery_img']) ;
		 $galleryObj->deletedImages = explode(",", $_POST["deletedRows"] ) ;
		 $galleryObj->userId = $user->ID ;		 
		 $this->_model->updateGallery($galleryObj);
		 $this->set('success_message', 'Images uploaded successfully!!');
		 
	}
	
	function rearrange($arr) {
		foreach ($arr as $key => $all) {
			foreach ($all as $i => $val) {
				$new[$i][$key] = $val;
			}
		}
		return $new;
	}
	
}