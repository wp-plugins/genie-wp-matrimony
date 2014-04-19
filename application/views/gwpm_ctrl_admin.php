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
echo "<h2>" . __('Genie WP Matrimony Configuration', 'genie_wp_matrimony') . "</h2>";

if( isset( $_GET[ 'tab' ] ) ) {
	$active_tab = $_GET[ 'tab' ];  
} else {
	$active_tab = "main_options" ;
}

$dynaTabUrl = "?page=gpwma&tab=dyna_options" ;

if($active_tab == "main_options") {
	$gwpmSetupModel = new GwpmSetupModel();
	$urlId = $gwpmSetupModel->getMatrimonialId();
	
	if (isset($_POST['userRole']) && $_POST['userRole'] == 'yes') {
		$gwpmSetupModel->setupGWPMDetails();
		$setupStatus = true;
	} elseif (isset($_POST['userRole']) && $_POST['userRole'] == 'no') {		
		$gwpmSetupModel->removeGWPMDetails();
		$setupStatus = false;
	} else {
		$setupStatus = $gwpmSetupModel->checkSetupStatus();
	}
	
	if ($setupStatus) {
		$resultObj[1] = "";
		$resultObj[0] = "selected";
	} else {
		$resultObj[0] = "";
		$resultObj[1] = "selected";
		$dynaTabUrl = "#" ;
	}
}

?>
<div id="icon-themes" class="icon32"></div>         
<h2 class="nav-tab-wrapper">  
	<a href="?page=gpwma&tab=main_options" class="nav-tab <?php echo $active_tab == 'main_options' ? 'nav-tab-active' : ''; ?>" >General Options</a>  
	<a href="<?php echo $dynaTabUrl ; ?>" class="nav-tab <?php echo $active_tab == 'dyna_options' ? 'nav-tab-active' : ''; ?>" >Dynamic Fields</a>  
</h2>

<div class="current-theme-new">

<?php 

if($active_tab == "main_options") {
	require_once 'admin/gwpm_ctrl_admin_main.php';
} else {
	require_once 'admin/gwpm_ctrl_admin_dyna_fields.php';
}
?>
</div>

</div>