<?php


/*
 * Created on Apr 28, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class GwpmGalleryModel {

	function getGalleryImages($userId = null) {
		if(!isset($userId)) $user = wp_get_current_user();
		else $user = get_userdata( $userId );
		$varObj = get_user_meta($user->ID, "gwpm_gallery_img");
		$imageObj = new GwpmGalleryVO();
		$imageObj->userId = $user->ID;
		if (isset ($varObj) && sizeof($varObj) > 0) {
			$imageObj->gwpm_gallery_img = $varObj[0];
			$imageObj->max_id = sizeof($varObj[0]);
		}
		return $imageObj;
	}

	function updateGallery($galleryObj) {
		$imageObjs = get_user_meta($galleryObj->userId, "gwpm_gallery_img");
		if (isset ($imageObjs) && sizeof($imageObjs) > 0) {
			$imageObjs = $imageObjs[0];
		} else
			$imageObjs = array ();
		foreach (array_keys(get_class_vars(get_class($galleryObj))) as $key) {
			if ($key == "gwpm_gallery_img") {
				$photoObjs = $this->trimLimitedImages($galleryObj-> $key , sizeof($imageObjs) + 1 ) ;
				if(sizeof($photoObjs) > 0) {
					foreach ($photoObjs as $photoObj) {
						if ($photoObj["size"] != 0 ) {
							global $gwpm_activity_model ;
							$imageObjs[$photoObj["id"]] = savePhotoToUploadFolder($photoObj, $galleryObj->userId, $photoObj["id"]);
							$gwpm_activity_model->addActivityLog("gallery", "Updated Gallery", $galleryObj->userId) ;
						} else
							continue;
					}
					update_user_meta($galleryObj->userId, $key, $imageObjs);
				}
			}
		}
	}

	function getMaxId($imageObjs) {
		$maxId = 0;
		foreach ($imageObjs as $imageObj) {
			if ($imageObj["id"] > $maxId)
				$maxId = $imageObj["id"];
		}
		return $maxId;
	}

	function trimLimitedImages($imageObj, $counter) {
		$newImgList = array() ;
		if(isset($imageObj) && sizeof($imageObj) > 0) { 
			foreach ($imageObj as $objs) {
				if ($counter >= GWPM_GALLERY_MAX_IMAGES)
					unset ($imageObj[$counter]);
				else {
					$objs["id"] = $counter ;
					$newImgList[$counter] = $objs ;
				}
				$counter++;
			}
		}
		return $newImgList;
	}
	
	function delete($userId, $photoId) {
		$imageObjs = get_user_meta($userId, "gwpm_gallery_img");
		if (isset ($imageObjs) && sizeof($imageObjs) > 0) {
			$imageObjs = $imageObjs[0];
		} else
			return 0;
		unset($imageObjs[$photoId]) ;
		update_user_meta($userId, "gwpm_gallery_img", $imageObjs);
		$resultObj['message'] = "Selected photo deleted successfully." ;
		$resultObj['result'] = 1 ;
		echo json_encode( $resultObj ) ;
	}

}