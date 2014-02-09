<form name="gwpm_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>"><br />
	<input type="hidden" name="page_loaded" value="Y">
	<p><?php _e("Run Matrimonial Setup: " ); ?>
	<select id="userRole" name="userRole" width=55px >
		<option name='yes' value='yes' <?php echo $resultObj[0]; ?>>Yes</option>
		<option name='no' value='no' <?php echo $resultObj[1]; ?>>No</option>
	</select> 
	<?php
		if(isset($urlId)) {
			echo "<br /><br /><a href='" . get_site_url() . "?page_id=" . $urlId . "' target='_blank'>Click Here to go to Matrimonial Page.</a>";
		}
	?>
	<p class="submit">
	<input type="submit" name="SubmitForm" value="<?php _e('Update Options', 'genie_wp_matrimony' ) ?>" />
	</p>
</form>