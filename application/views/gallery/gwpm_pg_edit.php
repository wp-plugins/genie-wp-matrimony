<h2 class='gwpm-content-title'>
	Gallery Page
	<?php if(!$this->isEditMode() && !$this->isUpdateMode()) {?>
	- <a class='edit-mode-link'
		href='<?php $this->get_gwpm_edit_link(); ?>'>Edit Gallery</a>
	<?php } ?>
</h2>
<?php
$modelObj = $this->get('model');
echo "Photos in gallery : <span id='imgCounts' >" . ( $modelObj->max_id ) . "</span> of " . GWPM_GALLERY_MAX_IMAGES . " (Click on the Image to delete)."   ;
$dynamic_content =  '<table style="width: 100%" class="gwpm_dynamic_content_holder"><tr> ' .
					'<td style="min-width: 10px;" ><input name="gwpm_gallery_img[ADDIDHERE]" ' .
					'type="file" id="gwpm_gallery_img[ADDIDHERE]" /></td> ' .
					'<td style="width: 30%"> ' .
					'<img id="delImageADDIDHERE" name="delImageADDIDHERE" ' .
					'style="width: 30px; height: 30px;" src="' . GWPM_PUBLIC_IMG_URL . URL_S . 'delete.png" ' .
					'alt="Delete Record" ' .
					'onClick="javascript:deleteDynamicComponent(ADDIDHERE,\'dynamicComponentHolder\',\'noOfRecords\');" /> ' .
					'</td></tr></table>';
?>
<br />
<script language="JavaScript" type="text/javascript">
 	var compSetId = 0;
	var totalServices = 0;
</script>
  
<div id="dynamicContent"
	style="visibility: hidden; display: none; height: 0px; max-height: 0px;">
	<?php echo $dynamic_content ;?>
</div>
<div id="gwpm_resultBox"></div>
<div id="gwpm-gallery">
	
	<ul>
		<?php 
		if(isset($modelObj->gwpm_gallery_img)) {
			$counter = 0;
			foreach($modelObj->gwpm_gallery_img as $galObj) { ?>
				<li id="gwpm_image_holder_<?php echo $counter; ?>">
					<img class="gwpm-thumb-image" alt="profile-picture" id="<?php gwpm_echo($galObj['id']); ?>" src="<?php 
						echo $this->getUserImageURL ($modelObj->userId , $galObj['thumb_name'] ); ?>" /> 
				</li>
		<?php $counter++ ; }
		} else {
			echo "<li>No photos found in user gallery.</li>" ;
		} ?> 
	</ul>
	
</div>
<div id="dynaControlBox">
		<input name="add" id="add" type="button" class="gwpm-button"
		value="Add Photo"
		onClick="javascript:addDynamicComponents('dynamicContent','dynamicComponentHolder','noOfRecords', 'noOfRowstoAdd');" />
</div>
<form name="gwpm-profile-form" action="<?php $this->getActionURL(); ?>"
						method="post" enctype="multipart/form-data">
	<table class='gwpm-table'>
		<tbody>
			<tr>
				<td colspan="2">
					<span id="dataRecorder" style="display: none; visibility: hidden;"  >Total No of Records: 
						<span name="noOfRecords" id="noOfRecords" style="width: 20px;" maxlength="2">0</span><br /><br />
						Deleted Rows: <input type="text" name="deletedRows" id="deletedRows" value="" />
					</span>
					<div id="dynamicComponentHolder"></div>
				</td>
			</tr>
			<tr>
				<td><input type="submit" value="Update" class="gwpm-button"
						name="update"></td>
					<td><input type="button" value="Cancel"
						onClick="javascript:window.history.back();" class="gwpm-button"
						name="cancel"></td>
			</tr>
		</tbody>
	</table>
</form>

<script language="JavaScript" type="text/javascript">
  jQuery("li").click(function(obj){
  	
  	var objId = obj.currentTarget.id ;
  	if(objId.indexOf("gwpm_image_holder_") >= 0) {
  		if(confirm("You are about to delete the photo. Proceed?")) {
			 jQuery(this).hide('slow', function(){
			 	jQuery("#resultBox").text("") ;
			 	$photo = (jQuery(this).children()[0]) ;
			 	var data = getAjaxRequestorObj('gallery_delete', $photo.id);
			 	data.userId = <?php echo $modelObj->userId ; ?> ;
				jQuery.post(MyAjax.ajaxurl, data, function(response) {
					var resObj = jQuery.parseJSON( response ) ;
					if(resObj.result == 1) {
						jQuery("#gwpm_resultBox").text(resObj.message) ;
						var counts =  jQuery("#imgCounts").html() ;
						jQuery("#imgCounts").html( counts - 1 ) ;
					} else {
						jQuery("#gwpm_resultBox").text(response) ;
					}
					jQuery("#gwpm_resultBox").fadeIn(100);
					jQuery("#gwpm_resultBox").slideUp(4000) ;
				});
			 });
  		}
  	}
  	
  });
</script>
  