<?php
/*
 * Created on May 27, 2012
*
*/
class GwpmGalleryVO extends GwpmCommand {

	/**
	 * @mandatory
	 * @message="Invalid value in Employee Id"
	 */
	var $userId;
	var $deletedImages;
	var $gwpm_gallery_img;
	var $max_id ;
	
	function GwpmGalleryVO(){
		
	}
}