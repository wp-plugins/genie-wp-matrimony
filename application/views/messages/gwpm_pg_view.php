<h2 class='gwpm-content-title'><?php gwpm_echo(gwpm_get_display_name($this->get('pid'))) ; ?>&nbsp;Messages</h2>
<br />
<?php
$resultObj = $this->get('model');
$resultCount = sizeof($resultObj);
?>
<table class='gwpm-conversation-result gwpm-search-result'>
	<tbody>
		<?php
			
			if ($resultCount > 0) {
				$counter = 1;
					?> 
					<tr>
				        <th valign="top">Sender</th>
				        <th valign="top">Message</th>
				 <!--         <th valign="top">Time</th>  -->
		      		</tr>
		      		<?php  
				foreach ($resultObj as $messageObj) {
					?>
					<tr>
				        <td valign="top" class="gwpm-user-id"><a href="<?php $this->get_gwpm_formated_url('page=profile&action=view&pid=' . $messageObj->user_id); ?>" 
				        				target="_blank" ><?php gwpm_echo( GWPM_USER_PREFIX . $messageObj->user_id ); ?></a><br />@ <?php gwpm_echo(  $messageObj->created ); ?></td>
				        <td valign="top"><?php gwpm_echo(  $messageObj->user_message ); ?></td>
				 <!--   <td valign="top" class="gwpm-created-date"><?php gwpm_echo(  $messageObj->created ); ?></td>  -->
		      		</tr>
					<?php
				$counter++ ;
				}
			} else {
				echo "<h3>No Converstaions found for the user. Please try again later.</h3>" ;
			}
			
		?>
	</tbody>
</table>
<nav id="nav-single"><br />					
		<span class="nav-previous">
		<?php echo $this->getPlainURL(); ?>
		</span> 
</nav>