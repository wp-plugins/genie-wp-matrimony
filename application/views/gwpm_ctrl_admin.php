<div class="wrap">
<?php
if (!function_exists('is_admin')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit ();
}

global $wpdb;
global $matrimonyPostId;
screen_icon();
echo "<h2>" . __('Genie WP Matrimony Plugin Configuration', 'genie_wp_matrimony') . "</h2>";

$gwpmSetupModel = new GwpmSetupModel();

if (isset($_POST['userRole']) && $_POST['userRole'] == 'yes') {
	$gwpmSetupModel->setupGWPMDetails();
	$setupStatus = true;
} elseif (isset($_POST['userRole']) && $_POST['userRole'] == 'no') {
	$gwpmSetupModel->removeGWPMDetails();
	$setupStatus = false;
} else {
	$setupStatus = $gwpmSetupModel->checkSetupStatus();
}

$urlId = null ;

if ($setupStatus) {
	$resultObj[1] = "";
	$resultObj[0] = "selected";
	$urlId = $gwpmSetupModel->getMatrimonialId(); 
} else {
	$resultObj[0] = "";
	$resultObj[1] = "selected";
}

?>

<form name="gwpm_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<?php    echo "<h4>" . __( 'Options: ', 'genie_wp_matrimony' ) . "</h4>"; ?>
	<input type="hidden" name="page_loaded" value="Y">
	<p><?php _e("Run Matrimonial Setup: " ); ?>
	<select id="userRole" name="userRole" width=55px >
		<option name='yes' value='yes' <?php echo $resultObj[0]; ?>>Yes</option>
		<option name='no' value='no' <?php echo $resultObj[1]; ?>>No</option>
	</select> <?php if(isset($urlId)) {
					echo "<br /><br /><a href='" . get_site_url() . "?page_id=" . $urlId . "' target='_blank'>Click Here to go to Matrimonial Page.</a>";
				}
				?>
	<p class="submit">
	<input type="submit" name="SubmitForm" value="<?php _e('Update Options', 'genie_wp_matrimony' ) ?>" />
	</p>
</form>

</div>