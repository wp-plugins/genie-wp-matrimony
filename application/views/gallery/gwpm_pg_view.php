<h2 class='gwpm-content-title'>
<?php gwpm_echo(gwpm_get_display_name($this->get('pid'))) ; ?>&nbsp;Gallery Page
	<?php if(!$this->isEditMode() && !$this->isUpdateMode() && $this->isOwnPage()) {?>
	- <a class='edit-mode-link'
		href='<?php $this->get_gwpm_edit_link(); ?>'>Edit Galley</a>
	<?php } ?>
</h2>
<br />
<?php
$modelObj = $this->get('model');
?>
<table class='gwpm-table'>
	<tbody>
		<tr>
			<td>
				<div id="gwpm-gallery">
					<ul>
						<?php 
						if(isset($modelObj->gwpm_gallery_img) && sizeof($modelObj->gwpm_gallery_img) > 0) {
							foreach($modelObj->gwpm_gallery_img as $galObj) { ?>
								<li>
									<a class="gwpm_profile_link" href="<?php echo $this->getUserImageURL ($modelObj->userId , $galObj['name'] ); ?>"
										 title=""> 
									<img class="gwpm-thumb-image" alt="profile-picture" src="<?php 
										echo $this->getUserImageURL ($modelObj->userId , $galObj['thumb_name'] ); ?>" /></a>
								</li>
						<?php }
						} else {
							echo "No photos found in user gallery." ;
						} ?> 
					</ul>
				</div>
			</td>
		</tr>
	</tbody>
</table>

<script type="text/javascript">
             
	 jQuery(document).ready(function() {
		jQuery('#gwpm-gallery a').lightBox({ 
			imageLoading: "<?php echo GWPM_PUBLIC_IMG_URL . URL_S . lightbox  ;?>/lightbox-ico-loading.gif",
			imageBtnPrev : '<?php echo GWPM_PUBLIC_IMG_URL . URL_S . lightbox  ;?>/lightbox-btn-prev.gif',
			imageBtnNext : '<?php echo GWPM_PUBLIC_IMG_URL . URL_S . lightbox  ;?>/lightbox-btn-next.gif',
			imageBtnClose : '<?php echo GWPM_PUBLIC_IMG_URL . URL_S . lightbox  ;?>/lightbox-btn-close.gif',
			imageBlank : '<?php echo GWPM_PUBLIC_IMG_URL . URL_S . lightbox  ;?>/lightbox-blank.gif'
		});
	 });
			  
</script>