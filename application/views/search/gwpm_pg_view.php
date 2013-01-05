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
				<td valign="top">Education:</td>
				<td valign="top"><?php $this->getSelectItem(getQualificationOptions(), 'gwpm_education') ; ?></td>
			</tr>
			<tr>
				<td valign="top">Employement:</td>
				<td valign="top"><?php $this->getSelectItem(getEmployementStatusOptions(), 'gwpm_work') ; 	?></td>
			</tr>
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
});

</script>