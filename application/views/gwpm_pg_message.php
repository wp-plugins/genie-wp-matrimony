<div id="content" role="main" >
	<?php
		$counter = 0;
		$errMessage = $this->get('error_messages');
		$success_message = $this->get('success_message');
		$warn_message = $this->get('warn_message');
		$user_message = $this->get('user_message');
		echo '<br />' ;
		if (isset ($user_message)) {
			echo "<h2>" . $user_message . "</h2>";
		}
		
		if (isset ($errMessage)) {
			$counter = sizeof($errMessage);
		}
		if ($counter == 0) {
			if (isset ($success_message)) {
				echo "<h2>" . $success_message . "</h2>";
			}
			$pageURL = $this->getPlainURL();
		} else {
			if (isset ($warn_message)) {
				echo "<h2>" . $warn_message . "</h2>";
			}
			foreach ($errMessage as $message) {
				echo '<h4>' . $message . '</h4>';
				$counter++;
			}
			$pageURL = $this->getBackURL();
		}
	?>
	<nav id="nav-single"><br />					
		<span class="nav-previous">
		<?php echo $pageURL; ?>
		</span> 
	</nav>
</div>