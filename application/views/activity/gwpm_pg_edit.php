<h2 class='gwpm-content-title'>Profile Page<?php if(!$this->isEditMode() && !$this->isUpdateMode()) {?> - <a class='edit-mode-link' href='<?php $this->get_gwpm_edit_link(); ?>'>Edit Profile</a><?php } ?></h2>
<br />
<?php
$modelObj = $this->get('model');
?>
<form name="gwpm-profile-form" action="<?php $this->getActionURL(); ?>"
	method="post">
	<div id="gwpm_accordion">
		<h3>
			<a href="#">Basic Information</a>
		</h3>
		<div>
			<table class='gwpm-table'>
				<tbody>
					<tr >
						<td valign="top">User ID:</td>
						<td valign="top"><input name="userId" id="userId"
							 /></td>
					</tr>
					<tr>
						<td valign="top">First Name:</td>
						<td valign="top"><input name="content[fname]" id="content[fname]"
							value="sssss" maxLength="25" /></td>
					</tr>
					<tr>
						<td valign="top">2 Name:</td>
						<td valign="top"><input name="content[lname-1]" id="content[lname-1]"
							value="xxxxx" /></td>
					</tr>	
					<tr>
						<td valign="top">3 Name:</td>
						<td valign="top"><input name="content[lname-2]" id="content[lname-2]"
							value="dddddd" /></td>
					</tr>
					<tr>
						<td valign="top">4 Name:</td>
						<td valign="top"><input name="content[lname-3]" id="content[lname-3]"
							value="" /></td>
					</tr>			 
				</tbody>
			</table>
		</div>
	</div>
	<table class='gwpm-table'>
		<tbody>
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
<script type="text/javascript">
             
	 jQuery(document).ready(function() {
		//jQuery("#gwpm_dob").datepicker({ dateFormat: 'MM d, yy', changeMonth: true, changeYear: true, firstDay: 0, monthNames: ['January','February','March','April','May','June','July','August','September','October','November','December'], monthNamesShort: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sept','Oct','Nov','Dec'], dayNames: ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'], dayNamesShort: ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'], dayNamesMin: ['Su','Mo','Tu','We','Th','Fr','Sa'], isRTL: false, minDate: '-100y', maxDate: '+5y', yearRange: '-100y:+5y' });
		var today = new Date();
		var year = today.getFullYear() - 18 ;
		var month = today.getMonth() ;
		jQuery("#gwpm_dob").datetimepicker({ ampm: true, maxDate: new Date(year, month, 1) });
		jQuery("#gwpm_accordion").accordion({ autoHeight: false });
	 });
			  
</script>