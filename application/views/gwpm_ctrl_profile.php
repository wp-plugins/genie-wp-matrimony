<?php
/*
 * Created on Apr 14, 2012
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
?>

<div class="wrap">
<?php

screen_icon('options-general'); 
echo "<h2>" . __( 'Matrimonial Profile', 'genie_wp_matrimony' ) . "</h2>" ;
try {
	$profileModel = new GwpmProfileModel() ;
	$userObj = wp_get_current_user();
	$modelObj = $profileModel->getUserObj($userObj->ID);
	$templateObj = new GwpmTemplate(null, null, null);

	if(isset($_POST['update-button']) && $_POST['update-button'] == 'Update') {
		$_keys = getDynamicFieldKeys() ;
		$profileObj = new GwpmProfileVO($_POST, $_keys);
		$profileObj->gwpm_profile_photo = $_FILES["gwpm_profile_photo"] ;
		$validateObj = $profileObj->validate();
		if (sizeof($validateObj) == 0) {
			$profileModel->updateUser($profileObj);
			$success_message = 'Profile updated successfully!!';
		} else {
			$warn_message = 'Please correct the below fields';
		}
		$counter = 0;
		if (isset ($validateObj)) {
			$counter = sizeof($validateObj);
		}
		if ($counter == 0) {
			if (isset ($success_message)) {
				echo "<h2>" . $success_message . "</h2>";
			}
		} else {
			if (isset ($warn_message)) {
				echo "<h2>" . $warn_message . "</h2>";
			}
			foreach ($validateObj as $message) {
				echo '<h4 class="gwpm-error-msg">' . $message . '</h4>';
				$counter++;
			}
		}
		$modelObj = $profileObj ;
	}
} catch(Exception $e) {
	echo $e;
}

?> 
<form name="gwpm-profile-form" action="<?php echo $_SERVER['REQUEST_URI'] ?>"
	method="post" enctype="multipart/form-data">
	<div id="gwpm_accordion">
		<h3>
			<a href="#">Basic Information</a>
		</h3>
		<div>
			<table class='gwpm-table'>
				<tbody>
					<tr class="gwpm_hidden_fields">
						<td valign="top">User ID:</td>
						<td valign="top"><input name="userId" id="userId"
							value="<?php echo $modelObj->userId ; ?>" maxLength="25" /></td>
					</tr>
					<tr>
						<td valign="top">First Name:</td>
						<td valign="top"><input name="first_name" id="first_name"
							value="<?php echo gwpm_echo ( $modelObj->first_name ); ?>" maxLength="25" /><span class="gwpm-mandatory">*</span></td>
					</tr>
					<tr>
						<td valign="top">Last Name:</td>
						<td valign="top"><input name="last_name" id="last_name"
							value="<?php echo gwpm_echo ( $modelObj->last_name ); ?>" /><span class="gwpm-mandatory">*</span></td>
					</tr>
					<tr class="gwpm_hidden_fields">
						<td valign="top">Email ID:</td>
						<td valign="top"><input name="user_email" id="user_email"
							value="<?php gwpm_echo ( $modelObj->user_email ) ; ?>" /><span class="gwpm-mandatory">*</span></td>
					</tr>
					<tr>
						<td valign="top">Contact No:</td>
						<td valign="top"><input name="gwpm_contact_no" id="gwpm_contact_no"
							value="<?php gwpm_echo ( $modelObj->gwpm_contact_no ) ; ?>" maxLength="15" /></td>
					</tr>
					<tr>
						<td valign="top">Gender:</td>
						<td valign="top">
							<?php $templateObj->getSelectItem(getGenderOptions(), 'gwpm_gender', ($modelObj->gwpm_gender)) ; 	?><span class="gwpm-mandatory">*</span>
						</td>
					</tr>
					<tr>
						<td valign="top">Address Line 1:</td>
						<td valign="top"><input name="gwpm_address[address1]" id="gwpm_address[address1]"
							value="<?php echo ( $modelObj->gwpm_address['address1'] ) ; ?>" maxLength="50" /></td>
					</tr>
					<tr>
						<td valign="top">Address Line 2:</td>
						<td valign="top"><input name="gwpm_address[address2]" id="gwpm_address[address2]"
							value="<?php echo ( $modelObj->gwpm_address['address2'] ) ; ?>" maxLength="50" /></td>
					</tr>
					<tr>
						<td valign="top">City:</td>
						<td valign="top"><input name="gwpm_address[city]" id="gwpm_address[city]"
							value="<?php echo ( $modelObj->gwpm_address['city'] ) ; ?>" maxLength="25" /></td>
					</tr>
					<tr>
						<td valign="top">State:</td>
						<td valign="top">
							<?php $templateObj->getSelectItem(getStateOptions(), 'gwpm_address[state]', ($modelObj->gwpm_address['state'])) ; 	?>
						</td>
					</tr>
					<tr class="gwpm_hidden_fields">
						<td valign="top">Country:</td>
						<td valign="top"><input name="gwpm_address[country]" id="gwpm_address[country]"
							value="India" /></td>
					</tr>
					<tr class="gwpm_hidden_fields">
						<td valign="top">Zip / Postal Code:</td>
						<td valign="top"><input name="gwpm_address[pin]" id="gwpm_address[pin]"
							value="000000" /></td>
					</tr>
					<tr>
						<td valign="top">About You:</td>
						<td valign="top">
							<textarea name="description" id="description" maxLength="200" ><?php gwpm_echo( $modelObj->description ); ?></textarea>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<h3>
			<a href="#">Horoscope Information</a>
		</h3>
		<div>
			<table class='gwpm-table'>
				<tbody>
					<tr>
						<td valign="top">Date of Birth:</td>
						<td valign="top"><input name="gwpm_dob" id="gwpm_dob"
							class="gwpm-datepicker"
							value="<?php gwpm_echo ( $modelObj->gwpm_dob ); ?>" maxLength="25" />
							<span class="gwpm-mandatory">*</span> (DD/MM/YYYY)</td>
					</tr>
					<tr>
						<td valign="top">Marital Status:</td>
						<td valign="top">
							<?php $templateObj->getSelectItem(getMaritalOptions(), 'gwpm_martial_status', ($modelObj->gwpm_martial_status)) ; 	?>
							<span class="gwpm-mandatory">*</span>
						</td>
					</tr>
					<tr>
						<td valign="top">Star Sign (Nakshatram):</td>
						<td valign="top">
							<?php $templateObj->getSelectItem(getStarSignOptions(), 'gwpm_starsign', ($modelObj->gwpm_starsign)) ; 	?>
							<span class="gwpm-mandatory">*</span>
						</td>
					</tr>
					<tr>
						<td valign="top">Zodiac Sign (Raasi):</td>
						<td valign="top">
							<?php $templateObj->getSelectItem(getZodiacOptions(), 'gwpm_zodiac', ($modelObj->gwpm_zodiac)) ; 	?>
							<span class="gwpm-mandatory">*</span>
						</td>
					</tr>
					<tr>
						<td valign="top">Sevvai Dosham:</td>
						<td valign="top">
							<?php $templateObj->getSelectItem(getYesNoOptions(), 'gwpm_sevvai_dosham', ($modelObj->gwpm_sevvai_dosham)) ; 	?>
							<span class="gwpm-mandatory">*</span>
						</td>
					</tr>
					<tr>
			        	<td valign="top">Caste:</td>
			       		<td valign="top">
			       			<input name="gwpm_caste" id="gwpm_caste" value="<?php gwpm_echo ( $modelObj->gwpm_caste ) ; ?>" />
							<span class="gwpm-mandatory">*</span> 
						</td>
			      	</tr>
		      		<tr>
			        	<td valign="top">Religion:</td>
			       		<td valign="top">
			       			<input name="gwpm_religion" id="gwpm_religion" value="<?php gwpm_echo ( $modelObj->gwpm_religion ) ; ?>" />
							<span class="gwpm-mandatory">*</span> 
						</td>
		      		</tr>
				</tbody>
			</table>
		</div>
		<h3>
			<a href="#">Education & Work Information</a>
		</h3>
		<div>
			<table class='gwpm-table'>
				<tbody>
					<tr>
						<td valign="top">Qualification:</td>
						<td valign="top">
							<?php $templateObj->getSelectItem(getQualificationOptions(), 'gwpm_education[qualification]', ($modelObj->gwpm_education['qualification'])) ; ?>
							<span class="gwpm-mandatory">*</span><br />
							<input class="gwpm_hidden_fields" name="gwpm_education[qualification_other]" id="gwpm_education_other"
								value="<?php 	if(isset($modelObj->gwpm_education['qualification_other'])) {
													gwpm_echo ( $modelObj->gwpm_education['qualification_other'] );		
												} else {
													echo "none" ;
												} ?>" />
						</td>
					</tr>
					<tr>
						<td valign="top">Specialization / Major:</td>
						<td valign="top"><input name="gwpm_education[specialization]" id="gwpm_education[specialization]"
							value="<?php gwpm_echo ( $modelObj->gwpm_education['specialization'] ); ?>" maxLength="50" />
							<span class="gwpm-mandatory">*</span></td>
					</tr>
					<tr>
						<td valign="top">Employement Status:</td>
						<td valign="top">
							<?php $templateObj->getSelectItem(getEmploymentStatusOptions(), 'gwpm_education[status]', ($modelObj->gwpm_education['status'])) ; 	?>
							<span class="gwpm-mandatory">*</span>
						</td>
					</tr>
					<tr>
						<td valign="top">Work Place Information:</td>
						<td valign="top"><input name="gwpm_work[place]" id="gwpm_work[place]"
							value="<?php gwpm_echo ( $modelObj->gwpm_work['place'] ); ?>" maxLength="30" /></td>
					</tr>
					<tr>
						<td valign="top">Designation:</td>
						<td valign="top"><input name="gwpm_work[designation]" id="gwpm_work[designation]"
							value="<?php gwpm_echo ( $modelObj->gwpm_work['designation'] ); ?>" maxLength="30" /></td>
					</tr>
					<tr>
						<td valign="top">Annual Income (INR):</td>
						<td valign="top"><input name="gwpm_work[income]" id="gwpm_work[income]"
							value="<?php gwpm_echo ( $modelObj->gwpm_work['income'] ); ?>" maxLength="30" /></td>
					</tr>
				</tbody>
			</table>
		</div>
		<h3>
			<a href="#">Physical Apprearance</a>
		</h3>
		<div>
			<table class='gwpm-table'>
				<tbody>
					<tr>
						<td valign="top">Height (CMS):</td>
						<td valign="top"><input name="gwpm_physical[height]" id="gwpm_physical[height]"
							value="<?php gwpm_echo ( $modelObj->gwpm_physical['height'] ); ?>" maxLength="5" /></td>
					</tr>
					<tr>
						<td valign="top">Weight (KGS):</td>
						<td valign="top"><input name="gwpm_physical[weight]" id="gwpm_physical[weight]"
							value="<?php gwpm_echo ( $modelObj->gwpm_physical['weight'] ); ?>" maxLength="5" /></td>
					</tr>
					<tr>
						<td valign="top">Color Complexion:</td>
						<td valign="top"><input name="gwpm_physical[color_complexion]" id="gwpm_physical[color_complexion]"
							value="<?php gwpm_echo ( $modelObj->gwpm_physical['color_complexion'] ); ?>" maxLength="25" /></td>
					</tr>
					<tr>
						<td valign="top">Body Type:</td>
						<td valign="top">
							<?php $templateObj->getSelectItem(getPhysicalType(), "gwpm_physical[body_type]", ($modelObj->gwpm_physical['body_type'])) ; 	?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<?php 
			$dynaData = getDynamicFieldData() ;
			$totalDynamicFields = $dynaData['gwpm_dynamic_field_count'] ;
			$dyna_field_item = $dynaData['dyna_field_item'] ;
			
			if(sizeof($dyna_field_item) > 0) {
				?>
				<h3>
					<a href="#">Other Information</a>
				</h3>
				<div>
					<table class='gwpm-table'>
						<tbody>
							<?php   
							$keys = array_keys($dyna_field_item)  ;
							foreach ($keys as $vkey) {
								?>
									<tr>
										<td valign="top"><?php echo $dyna_field_item[$vkey]['label']  ?>:</td>
										<td valign="top">
											<?php
												 if($dyna_field_item[$vkey]['type'] == 'text') {
													gwpm_echo ('<input name="' . $vkey . '" id="' . $vkey . '" value="' . 
																$modelObj-> $vkey . '" />' );
												 } else if($dyna_field_item[$vkey]['type'] == 'select') {
												 	$templateObj->getSelectItem(getDynamicFieldOptions($dyna_field_item[$vkey]['value']), $vkey, $modelObj-> $vkey) ;
												 } else if($dyna_field_item[$vkey]['type'] == 'yes_no') {
												 	$templateObj->getSelectItem(getYesNoOptions(), $vkey, $modelObj-> $vkey) ;
												 }
											?>
										<span class="gwpm-mandatory">*</span></td>
									</tr>
								<?php
							}
							?>
						</tbody>
					</table>
				</div>
				<?php
			}
			
		?>
		<h3>
			<a href="#">Profile Photo</a>
		</h3>
		<div>
			<table class='gwpm-table'>
				<tbody>
					<tr>
						<td valign="top">Profile Photo:</td>
						<td valign="top">
						<input type="file" name="gwpm_profile_photo" id="gwpm_profile_photo" /> </ br>
						<span class="gwpm-help" >Image maximum size <b>500</b> kb </span>
					</tr>
				</tbody>
			</table>
		</div>
	</div> <br /><br />
	<table class='gwpm-table'>
		<tbody>
			<tr>
				<td><input type="submit" value="Update" class="gwpm-button"
					name="update-button"></td>
				<td><input type="button" value="Cancel" class="gwpm-button"
					name="cancel-button"></td>
			</tr>
			<tr><td colspan=2><span class="gwpm-mandatory">* - Mandatory Fields</span></td></tr>
		</tbody>
	</table>
</form>
<script type="text/javascript">
             
	 jQuery(document).ready(function() {
		jQuery("select").change(function(obj){
			if(obj.currentTarget.id == "gwpm_education[qualification]" && obj.currentTarget.value == 7) {
				jQuery("#gwpm_education_other").removeClass("gwpm_hidden_fields"); 
				jQuery("#gwpm_education_other").val("") ; 
			} else {
				jQuery("#gwpm_education_other").addClass("gwpm_hidden_fields");
				jQuery("#gwpm_education_other").val("none") ; 
			}
		}) ;
	 });
			  
</script>