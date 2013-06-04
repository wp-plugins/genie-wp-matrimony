<?php
	$modelObj = $this->get('model');
?>
<h1>
	<h2 class='gwpm-content-title'>Welcome</h2>
	<table class='gwpm-table'>
		<tbody>
			<tr>
				<td>View your Profile and update data from <a href='<?php $this->get_gwpm_formated_url('page=profile&action=view') ?>' >Account</a></td></tr><tr>
				<td>View the last <?php echo GWPM_CONVERSE_MAX_NOS ?> activity at <a href='<?php $this->get_gwpm_formated_url('page=activity&action=view') ?>' >Activity</a></td></tr><tr>
				<td>View Interests, Messages from other users at <a href='<?php $this->get_gwpm_formated_url('page=messages&action=view') ?>' >Messages</a> (You have <?php echo $modelObj->unreadMessages; ?> unread notifications)</td></tr><tr>
				<td>View your Gallery of photos at <a href='<?php $this->get_gwpm_formated_url('page=gallery&action=view') ?>' >Gallery</a></td></tr><tr>
				<td>View your Search other users at <a href='<?php $this->get_gwpm_formated_url('page=search&action=view') ?>' >Search</a></td></tr><tr>
			</tr>
		</tbody>
	</table>
</h1>