<h2 class='gwpm-content-title'>Search Results</h2>
<br />
<?php
$resultObj = $this->get('model');
$resultCount = sizeof($resultObj);
?>
<table class='gwpm-search-result'>
	<tbody>
		<?php
			
			if ($resultCount > 0) {
				$counter = 1;
					?> 
					<tr>
						<th valign="top">S.No. </th>
						<th valign="top">Image</th>
				        <th valign="top">User Id</th>
				        <th valign="top">Name</th>
				        <th valign="top">Email Id</th>
		      		</tr>
		      		<?php  
				foreach ($resultObj as $userObj) {
						
					$gravatarDetail = get_avatar( $userObj->ID , 48 ) ;
					$gravatarDetail = $gravatarDetail ? $gravatarDetail : getGravatarImageForUser($userObj->ID, true );
					
					?>
					<tr>
						<td valign="top"><?php gwpm_echo(  $counter ); ?></td>
						<td valign="top"><?php echo ($gravatarDetail ); ?></td>
				        <td valign="top">
				        	<a href="<?php $this->get_gwpm_formated_url('page=profile&action=view&pid=' . $userObj->ID) ?>"
				        	 target="_blank" ><?php gwpm_echo( GWPM_USER_PREFIX . $userObj->ID ); ?></a></td>
				        <td valign="top"><?php gwpm_echo(  $userObj->display_name ); ?></td>
				        <td valign="top"><?php gwpm_echo(  $userObj->user_email ); ?></td>
				        
		      		</tr>
					<?php
				$counter++ ;
				}
			} else {
				echo "<h3>No Results found with the given criterias. Please try some other options.</h3>" ;
			}
			
		?>
	</tbody>
</table>
<nav id="nav-single"><br />					
		<span class="nav-previous">
		<?php echo $this->getPlainURL(); ?>
		</span> 
</nav>
<script type="text/javascript">
  
jQuery(document).ready(function() {
});

</script>