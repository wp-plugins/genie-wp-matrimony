<?php

$user_key = $_POST['user_key'] ;

$queryString = "SELECT DISTINCT (wp_usermeta.user_id) FROM wp_usermeta WHERE ( meta_value LIKE '%s' AND ( meta_key = 'first_name' OR meta_key='last_name' ) ) " ;

if(!isNull($user_key)) {

	global $wpdb;

	$args[0] = ($user_key) . '%' ;
	// $args[0] = '%' . like_escape($user_key) . '%' ;
	echo "Search Args: " . print_r($args) . '<br />';
	$preparedSql = $wpdb->prepare($queryString, $args);
	$result = $wpdb->get_results($preparedSql);
	
	foreach($result as $obj) {
		echo $obj->user_id . "<br />";
	}

} else {
	?>

<form method="post">
	<div style='width: 200px;'>User Key:</div>
	<input type="text" name="user_key"><br /> <input type="submit"
		value="Search"><br />
</form>

	<?php
}