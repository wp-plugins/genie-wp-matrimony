<?php
$modelObj = $this->get('model');
if ($modelObj != null) {
	?>
<h2 class='gwpm-content-title'>
<?php gwpm_echo(gwpm_get_display_name($this->get('pid'))) ; ?>
	&nbsp;Profile Page
	<?php if(!$this->isEditMode() && !$this->isUpdateMode() && $this->isOwnPage())
	{
		?>
	- <a class='edit-mode-link'
		href='<?php $this->get_gwpm_edit_link(); ?>'>Edit Profile</a>
		<?php
	} else if (is_user_logged_in()) {
		?>
	- <a class='edit-mode-link'
		href='<?php $this->get_gwpm_formated_url('page=messages&action=update&type=int'); ?>'>Send
		Interest</a>
		<?php
	}	?>
</h2>
<br />
<table class='gwpm-table'>
	<tbody>
		<tr>
			<td>
				<div class="gwpm_profile_pic_holder">
					<a class="gwpm_profile_link"
						href="<?php echo $this->getUserImageURL ($modelObj->userId , $modelObj->gwpm_profile_photo['name'] ); ?>"
						target="_blank" title="Click to see the original image"> <img
						class="gwpm-thumb-image" alt="profile-picture"
						src="<?php 
									echo $this->getUserImageURL ($modelObj->userId , $modelObj->gwpm_profile_photo['thumb_name'] ); ?>" />
					</a></br> <span class="gwpm-help">Click to see the original image</span>
				</div>
			</td>
				<td style="border-bottom: none;">
					<table>
						<tr>
					        <td valign="top">User ID:</td>
					        <td valign="top"><?php echo GWPM_USER_PREFIX . $modelObj->userId ; ?></td>
			      		</tr>
						<tr>
							<td valign="top">Name:</td>
							<td valign="top"><?php gwpm_echo( $modelObj->first_name ); ?> <?php gwpm_echo( $modelObj->last_name ); ?></td>
						</tr>
						<tr>
				        	<td valign="top">Date of Birth:</td>
				       		<td valign="top"><?php gwpm_echo ( $modelObj->gwpm_dob ); ?></td>
			      		</tr>
			      		<tr>
							<td valign="top">Contact:</td>
							<td valign="top"> <?php gwpm_echo ( $modelObj->gwpm_contact_no ) ; ?> </td>
						</tr>
						<tr>
				        	<td valign="top">Gender:</td>
				       		<td valign="top"><?php gwpm_echo (getGenderOptions($modelObj->gwpm_gender )); ?></td>
			      		</tr>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
	<table class='gwpm-table'>
		<tbody>
			<tr>
				<td colspan="2">  
					<h3> Basic Information </h3>
				</td>
			</tr>
			<tr>
		        <td valign="top">Email ID:</td>
		        <td valign="top">   <?php echo ( $modelObj->user_email ) ; ?></td>
      		</tr>
      		<tr>
				<td valign="top">Address Line 1:</td>
				<td valign="top"><?php echo ( $modelObj->gwpm_address['address1'] ) ; ?></td>
			</tr>
			<tr>
				<td valign="top">Address Line 2:</td>
				<td valign="top"> <?php echo ( $modelObj->gwpm_address['address2'] ) ; ?></td>
			</tr>
			<tr>
				<td valign="top">City:</td>
				<td valign="top"> <?php echo ( $modelObj->gwpm_address['city'] ) ; ?></td>
			</tr>
			<tr>
				<td valign="top">State:</td>
				<td valign="top">
					<?php echo (getStateOptions( $modelObj->gwpm_address['state'])) ; 	?>
				</td>
			</tr>
			<tr class="gwpm_hidden_fields">
				<td valign="top">Country:</td>
				<td valign="top"> India </td>
			</tr>
			<tr class="gwpm_hidden_fields">
				<td valign="top">Zip / Postal Code:</td>
				<td valign="top"> 000000 </td>
			</tr>
      		<tr>
	        	<td valign="top">About You:</td>
	       		<td valign="top"><?php gwpm_echo( $modelObj->description ); ?></td>
      		</tr>
      		<tr>
				<td colspan="2">  
					<h3> Horoscope Information </h3>
				</td>
			</tr>
			<tr>
	        	<td valign="top">Date of Birth:</td>
	       		<td valign="top"><?php gwpm_echo ( $modelObj->gwpm_dob ); ?></td>
      		</tr>
      		<tr >
	        	<td valign="top">Marital Status:</td>
	       		<td valign="top">
	       			<?php gwpm_echo (getMaritalOptions($modelObj->gwpm_martial_status) ) ; 	?> 
				</td>
      		</tr>
      		<tr>
	        	<td valign="top">Star Sign (Nakshatram):</td>
	       		<td valign="top">
	       			<?php gwpm_echo (getStarSignOptions($modelObj->gwpm_starsign)) ; 	?> 
				</td>
      		</tr>
      		<tr>
	        	<td valign="top">Zodiac Sign (Raasi):</td>
	       		<td valign="top">
	       			<?php gwpm_echo (getZodiacOptions($modelObj->gwpm_zodiac)) ; 	?> 
				</td>
      		</tr>
      		<tr>
	        	<td valign="top">Sevvai Dosham:</td>
	       		<td valign="top">
	       			<?php gwpm_echo (getYesNoOptions($modelObj->gwpm_sevvai_dosham)) ; 	?> 
				</td>
      		</tr>
      		<tr>
	        	<td valign="top">Caste:</td>
	       		<td valign="top">
	       			<?php gwpm_echo ( $modelObj->gwpm_caste ); 	?> 
				</td>
      		</tr>
      		<tr>
	        	<td valign="top">Religion:</td>
	       		<td valign="top">
	       			<?php gwpm_echo ( $modelObj->gwpm_religion ); 	?> 
				</td>
      		</tr>
      		<tr>
				<td colspan="2">  
					<h3> Education & Work Information </h3>
				</td>
			</tr>
			<tr>
				<td valign="top">Qualification:</td>
				<td valign="top">
					<?php 
						gwpm_echo (getQualificationOptions($modelObj->gwpm_education['qualification'])) ; 	
					  	if(isset($modelObj->gwpm_education['qualification_other']) && $modelObj->gwpm_education['qualification_other'] != 'none') {
					  		gwpm_echo (' (' . $modelObj->gwpm_education['qualification_other'] . ')'); 
					  	}
					?>
				</td>
			</tr>
			<tr>
				<td valign="top">Specialization:</td>
				<td valign="top"> <?php gwpm_echo ( $modelObj->gwpm_education['specialization'] ); ?></td>
			</tr>
			<tr>
				<td valign="top">Employement Status:</td>
				<td valign="top"> <?php gwpm_echo ( getEmploymentStatusOptions( $modelObj->gwpm_education['status']) ) ; ?> </td>
			</tr>
			<tr>
				<td valign="top">Work Place Information:</td>
				<td valign="top"> <?php gwpm_echo ( $modelObj->gwpm_work['place'] ); ?> </td>
			</tr>
			<tr>
				<td valign="top">Designation:</td>
				<td valign="top"> <?php gwpm_echo ( $modelObj->gwpm_work['designation'] ); ?> </td>
			</tr>
			<tr>
				<td valign="top">Annual Income (INR):</td>
				<td valign="top"> <?php gwpm_echo ( $modelObj->gwpm_work['income'] ); ?> </td>
			</tr>
      		<tr>
				<td colspan="2">  
					<h3> Physical Apprearance </h3>
				</td>
			</tr>
      		<tr>
				<td valign="top">Height (CMS):</td>
				<td valign="top"><?php gwpm_echo( $modelObj->gwpm_physical['height'] ) ?></td>
			</tr>
			<tr>
				<td valign="top">Weight (KGS):</td>
				<td valign="top"><?php gwpm_echo( $modelObj->gwpm_physical ['weight'] ) ?></td>
			</tr>
			<tr>
				<td valign="top">Color Complexion:</td>
				<td valign="top"><?php gwpm_echo( $modelObj->gwpm_physical ['color_complexion'] ) ?></td>
			</tr>
			<tr>
				<td valign="top">Body Type:</td>
				<td valign="top">
					<?php gwpm_echo( getPhysicalType($modelObj->gwpm_physical ['body_type'] )) ?>
				</td>
			</tr>
			<?php 
			
			$dynaData = getDynamicFieldData() ;
			$totalDynamicFields = $dynaData['gwpm_dynamic_field_count'] ;
			$dyna_field_item = $dynaData['dyna_field_item'] ;
			
			if(sizeof($dyna_field_item) > 0) {
				?>
				<tr>
					<td colspan="2">  
						<h3> Other Informations </h3>
					</td>
				</tr>
				<?php   
				$keys = array_keys($dyna_field_item)  ;
				foreach ($keys as $vkey) {
					?>
						<tr>
							<td valign="top"><?php echo $dyna_field_item[$vkey]['label']  ?>:</td>
							<td valign="top">
								<?php
									$_type = $dyna_field_item[$vkey]['type'] ;
									 if($_type == 'text') {
										gwpm_echo( $modelObj-> $vkey ) ;
									 } else if($_type == 'yes_no' ) {
									 	gwpm_echo (getYesNoOptions($modelObj->$vkey)) ;
									 } else if ($_type == 'select'){
									 	gwpm_echo( $dyna_field_item[$vkey]['value'][$modelObj-> $vkey] ) ;
									 }
								?>
							</td>
						</tr>
					<?php
				}
			}
			?>
	</tbody>
</table>
<?php } else {
	include (GWPM_APPLICATION_URL . DS . 'views' . DS . 'gwpm_pg_login.php');
}
