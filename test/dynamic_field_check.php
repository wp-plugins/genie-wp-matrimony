<?php

$fieldCounts = $_POST['gwpm_dyna_field_count'] ;

// while($fieldCounts-- == 0) {
	
// }

$label = $_POST['gwpm_dyna_field_label'] ;
$type = $_POST['gwpm_dyna_field_type'] ;

if(!isNull($label) && !isNull($type)) {

	global $wpdb;
	$totalFields = get_option(GWPM_DYNA_FIELD_COUNT);
	echo $totalFields ;
	if($totalFields == false )
		$totalFields = 0 ;
	$save_options['gwpm_dyna_field_label'] = $label ;
	$save_options['gwpm_dyna_field_type'] = $type ;
	if($type == "select" ) {
		$save_options_values[0] = "Sample" ;
		$save_options_values[1] = "New Values" ;
		$save_options['gwpm_dyna_field_values'] = $save_options_values ;
	}
	print_r($save_options) ;	
	$result = update_option (GWPM_DYNA_KEY_PREFIX . ($totalFields + 1), $save_options) ;
	if($result == 1)
		update_option (GWPM_DYNA_FIELD_COUNT, ($totalFields + 1)) ;
	
} else {
	?>

<form method="post">	
	Field Label: <input type="text" name="gwpm_dyna_field_label"><br />
	Field Option Type: <select name="gwpm_dyna_field_type">
		<option value="text" selected >text</option>
		<option value="select" >select</option>
	</select><br />	
	<input type="submit" value="Create"><br />
</form>
<br /><br /><br /><br />
	<?php
	
	$totalFields = get_option(GWPM_DYNA_FIELD_COUNT);
	
	for(;$totalFields > 0; $totalFields-- ) {
		$fetched_options = get_option(GWPM_DYNA_KEY_PREFIX . $totalFields) ;
		print_r($fetched_options) ;		
		echo '<br />' . $fetched_options['gwpm_dyna_field_label'] ;
		
		if($fetched_options[gwpm_dyna_field_type] == "select") {
			?>
			<select name="<?php echo GWPM_DYNA_KEY_PREFIX . $totalFields; ?>" >
				<option value="<?php echo $fetched_options['gwpm_dyna_field_values'] [0] ?>"><?php echo $fetched_options['gwpm_dyna_field_values'] [0] ?></option>
				<option value="<?php echo $fetched_options['gwpm_dyna_field_values'] [1] ?>"><?php echo $fetched_options['gwpm_dyna_field_values'] [1] ?></option>
			</select>
			<?php 
		} else {
			?>
			<input type="text" name="<?php echo GWPM_DYNA_KEY_PREFIX . $totalFields; ?>" />	
			<?php 	
		}
		echo "<br /><br />" ;
	}
		
}