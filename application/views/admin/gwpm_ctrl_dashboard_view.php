<?php

global $genieWPMatrimonyController ;

$gwpmAdminModel = new GwpmAdminModel();
$resultObj = null ;

if($user_type == 'matrimony')
	$resultObj = $gwpmAdminModel->getAllMatrimonyUsers() ;
else
	$resultObj = $gwpmAdminModel->getSubscribedUsers() ;
	
$resultCount = sizeof($resultObj);

?>
<table class='widefat gwpm-search-result'>
<?php

if ($resultCount > 0) {
	$page_id = $genieWPMatrimonyController->getGWPMPageId() ;
	$counter = 1;
	?>
	<thead>
		<tr>
			<th class="gwpm_fifrst_cell column-name" valign="top">S.No.</th>
			<th class="column-name" valign="top">Display Pic</th>
			<th class="column-name" valign="top">User Id</th>
			<th class="column-name" valign="top">Name</th>
			<th class="column-name" valign="top">Email Id</th>
			<th class="column-name" valign="top">Action</th>
		</tr>
	</thead>
	<?php
	foreach ($resultObj as $userObj) {
		?>
		<tbody>
			<tr>
				<td valign="top"><?php gwpm_echo(  $counter ); ?></td>
				<td valign="top"><?php			
					$gravatarDetail = get_avatar( $userObj->ID , 48 ) ;
					$gravatarDetail = $gravatarDetail ? $gravatarDetail : getGravatarImageForUser($userObj->ID, true );	
					echo $gravatarDetail ;		
				?></td>
				<td valign="top"><a
					href="<?php echo  get_site_url() . '?page_id=' . $page_id . '&page=profile&action=view&pid=' . $userObj->ID ?>"
					target="_blank"><?php gwpm_echo( GWPM_USER_PREFIX . $userObj->ID ); ?>
				</a></td>
				<td valign="top"><?php gwpm_echo(  $userObj->display_name ); ?></td>
				<td valign="top"><?php gwpm_echo(  $userObj->user_email ); ?></td>
				<td valign="top"><a
					href="user-edit.php?user_id=<?php echo $userObj->ID . '#user_login' ; ?>"
					target="_blank">Change Role</a></td>
			</tr>
		</tbody>
		<?php
		$counter++ ;
	}
} else {
	echo "<tbody><tr><td class='gwpm_fifrst_cell column-name' valign='top'><h3>No users found !!!</h3></td></tbody>" ;
}

?>
</table>
