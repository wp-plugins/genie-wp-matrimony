<h2 class='gwpm-content-title'><?php gwpm_echo(gwpm_get_display_name($this->get('pid'))) ; ?>&nbsp;Activities</h2>
<br />
<?php
$resultObj = $this->get('model');
$resultCount = sizeof($resultObj);
?>
<table class='gwpm-activity-result'>
	<tbody>
		<?php
			
			if ($resultCount > 0) {
				$counter = 1;
					?> 
					<tr>
				        <th valign="top">Activity</th>
				        <th valign="top">Time</th>
		      		</tr>
		      		<?php  
				foreach ($resultObj as $activityObj) {
					?>
					<tr>
				        <td valign="top"><?php gwpm_echo(  $activityObj->act_text ); ?></td>
				        <td valign="top"><?php gwpm_echo(  $activityObj->act_time ); ?></td>
		      		</tr>
					<?php
				$counter++ ;
				}
			} else {
				echo "<h3>No Activity found for the user. Please try again later.</h3>" ;
			}
			
		?>
	</tbody>
</table>
<nav id="nav-single"><br />					
		<span class="nav-previous">
		<?php echo $this->getPlainURL(); ?>
		</span> 
</nav>