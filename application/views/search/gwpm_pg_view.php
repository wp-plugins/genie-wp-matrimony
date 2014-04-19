<h2 class='gwpm-content-title'>Search Page</h2>
<br />
<?php
$this->set('action', 'edit') ;
$modelObj = $this->get('model');
?>
<form name="gwpm-profile-form" action="<?php $this->getActionURL(); ?>"
	method="post" >
<table class='gwpm-table'>
		<tbody>
			<tr>
		        <td valign="top">Account ID:</td>
		        <td valign="top"><input name="userId" id="userId" value="" /></td>
      		</tr>
			<tr>
				<td valign="top">Name:</td>
				<td valign="top"><input name="username" id="username" value="" /></td>
			</tr>
			<tr>
	        	<td valign="top">Age Range:<br /><span style="color: #f6931f; font-weight: bold;" >(18 to 60 Yrs)</span></td>
	       		<td valign="top">
  					<input id="gwpm_age" style="color: #f6931f; font-weight: bold;" disabled="disabled" />
  					<input id="gwpm_age_from" name="gwpm_age_from" class="gwpm_hidden_fields" />
  					<input id="gwpm_age_to" name="gwpm_age_to" class="gwpm_hidden_fields" />
  					<div id="slider-range" style="width: 13em; margin-top: 15px;"></div>
	       		</td>
      		</tr>
			<tr>
	        	<td valign="top">Born Before:</td>
	       		<td valign="top"><input name="gwpm_dob" id="gwpm_dob"
						class="gwpm-datepicker" /></td>
      		</tr>
      		<tr>
				<td valign="top">Address:</td>
				<td valign="top"><input name="gwpm_address" id="gwpm_address" /></td>
			</tr>
			<tr>
	        	<td valign="top">Gender:</td>
	       		<td valign="top"><?php $this->getSelectItem(getGenderOptions(), 'gwpm_gender' ) ; 	?></td>
      		</tr>
      		<tr>
				<td valign="top">Marital Status:</td>
				<td valign="top">
					<?php $this->getSelectItem(getMaritalOptions(), 'gwpm_martial_status' ) ; 	?>
				</td>
			</tr>
			<tr>
				<td valign="top">Star Sign (Nakshatram):</td>
				<td valign="top">
					<?php $this->getSelectItem(getStarSignOptions(), 'gwpm_starsign' ) ; 	?>
				</td>
			</tr>
			<tr>
				<td valign="top">Zodiac Sign (Raasi):</td>
				<td valign="top">
					<?php $this->getSelectItem(getZodiacOptions(), 'gwpm_zodiac' ) ; 	?>
				</td>
			</tr>
			<tr>
				<td valign="top">Sevvai Dosham:</td>
				<td valign="top">
					<?php $this->getSelectItem(getYesNoOptions(), 'gwpm_sevvai_dosham' ) ; 	?>
				</td>
			</tr>
			<tr>
				<td valign="top">Caste:</td>
				<td valign="top"><input name="gwpm_caste" id="gwpm_caste" /></td>
			</tr>
			<tr>
				<td valign="top">Religion:</td>
				<td valign="top"><input name="gwpm_religion" id="gwpm_religion" /></td>
			</tr>
			<tr>
				<td valign="top">Education:</td>
				<td valign="top"><?php $this->getSelectItem(getQualificationOptions(), 'gwpm_education[qualification]') ; ?></td>
			</tr>
			<tr>
				<td valign="top">Employment:</td>
				<td valign="top"><?php $this->getSelectItem(getEmploymentStatusOptions(), 'gwpm_education[status]') ; 	?></td>
			</tr>
			<?php
				$totalDynamicFields = $modelObj['gwpm_dynamic_field_count'] ;
				$dyna_field_item = $modelObj['dyna_field_item'] ;
				if($dyna_field_item != null && sizeof($dyna_field_item) > 0) {
					$keys = array_keys($dyna_field_item)  ;				
					foreach ($keys as $vkey) {
						?>
							<tr>
								<td valign="top"><?php echo $dyna_field_item[$vkey]['label']  ?>:</td>
								<td valign="top">
									<?php
										 if($dyna_field_item[$vkey]['type'] == 'text') {
											gwpm_echo ('<input name="' . $vkey . '" id="' . $vkey . '" />' );
										 } else if($dyna_field_item[$vkey]['type'] == 'select') {
										 	$this->getSelectItem(getDynamicFieldOptions($dyna_field_item[$vkey]['value']), $vkey) ;
										 } else if($dyna_field_item[$vkey]['type'] == 'yes_no') {
										 	$this->getSelectItem(getYesNoOptions(), $vkey) ;
										 }
									?>
								</td>
							</tr>
						<?php
					}
				}
			?>
			<tr>
				<td valign="top">With Profile Photo:</td>
				<td valign="top"><input type="checkbox" name="gwpm_has_photo" value="1"/></td>
			</tr>
		</tbody>
</table>
<table class='gwpm-table'>
		<tbody>
			<tr>
				<td><input type="submit" value="Search" class="gwpm-button"
					name="search"></td>
				<td><input type="reset" value="Clear"
					 class="gwpm-button"
					name="cancel"></td>
			</tr>
		</tbody>
</table>
</form>
<script type="text/javascript">
  
jQuery(document).ready(function() {
		var today = new Date();
		var year = today.getFullYear() - 18 ;
		var month = today.getMonth() ;
		jQuery("#gwpm_dob").datetimepicker({ ampm: true, maxDate: new Date(year, month, 1) });
		jQuery( "#slider-range" ).slider({
	      range: true,
	      min: 18,
	      max: 60,
	      values: [ 18, 60 ],
	      slide: function( event, ui ) {
	        jQuery( "#gwpm_age" ).val( ui.values[ 0 ] + " to " + ui.values[ 1 ] + " Yrs");
	        jQuery( "#gwpm_age_from" ).val(ui.values[ 0 ]) ;
	        jQuery( "#gwpm_age_to" ).val(ui.values[ 1 ]) ;
	      }
	    });
//	    jQuery( "#gwpm_age" ).val( "18 to 60 Yrs" );
//	    jQuery( "#gwpm_age_from" ).val("18") ;
//	    jQuery( "#gwpm_age_to" ).val("60") ;
});

</script>