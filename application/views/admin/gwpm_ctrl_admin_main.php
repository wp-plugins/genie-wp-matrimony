<?php 

if (!function_exists('is_admin')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit ();
}

?>

<form name="gwpm_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>"><br />
	<input type="hidden" name="page_loaded" value="Y">
	<table class='widefat gwpm-search-result'><tr><td>
	<p><?php _e("Run Matrimonial Setup: " ); ?></p></td><td>
	<select id="pluginSetup" name="pluginSetup" width="55px" >
		<option name='yes' value='yes' <?php echo $resultObj[0]; ?>><?php _e("Yes", 'genie_wp_matrimony'); ?></option>
		<option name='no' value='no' <?php echo $resultObj[1]; ?>><?php _e("No", 'genie_wp_matrimony'); ?></option>
	</select></td></tr>  <tr><td>
<!-- <p><?php _e("Plugin Purpose: " ); ?></p></td><td>
	<select id="displayTerm" name="displayTerm" width="55px" >
		<option value='matrimony' <?php echo $resultObj[2]; ?>>  <?php _e("Matrimony", 'genie_wp_matrimony'); ?> </option>
		<option value='dating' <?php echo $resultObj[3]; ?>>  <?php _e("Dating", 'genie_wp_matrimony'); ?> </option>
	</select></td></tr>  <tr><td>  -->
	<p><?php _e("User Login Preference: " ); ?></p></td><td>	
	<input type="radio" name="loginPreferences" id="loginPreferences_1" value="1" <?php echo $resultObj[4]; ?> ><label for="loginPreferences_1"><?php _e("User should be registered and approved by Administrator", 'genie_wp_matrimony'); ?></label><br />
	<input type="radio" name="loginPreferences" id="loginPreferences_2" value="2" <?php echo $resultObj[5]; ?> ><label for="loginPreferences_2"><?php _e("User should be registered", 'genie_wp_matrimony'); ?></label><br />
	<input type="radio" name="loginPreferences" id="loginPreferences_3" value="3" <?php echo $resultObj[6]; ?> ><label for="loginPreferences_3"><?php _e("Anybody can view the profiles", 'genie_wp_matrimony'); ?></label>
	</td></tr> </table>
	<?php
		if(isset($urlId)) {
			echo "<br /><br /><a href='" . get_site_url() . "?page_id=" . $urlId . "' target='_blank'>Click Here to go to Matrimonial Page.</a>";
		}
	?>
	<p class="submit">
	<input type="submit" name="SubmitForm" value="<?php _e('Update Options', 'genie_wp_matrimony' ) ?>" />
	</p>
</form>