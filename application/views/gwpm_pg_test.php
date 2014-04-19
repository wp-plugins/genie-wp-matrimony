<?php 

global $genieWPMatrimonyController ;
$_page_link = "?page_id=" . $genieWPMatrimonyController->getGWPMPageId() . "&page=test" ; 
echo "<a href=' " . $_page_link . "' >Test Page</a>&nbsp;&nbsp;&nbsp;" ;
echo "<a href=' " . $_page_link . "&tpid=create' >Create Users</a>&nbsp;&nbsp;&nbsp;" ;
echo "<a href=' " . $_page_link . "&tpid=search' >Search Users</a>&nbsp;&nbsp;&nbsp;" ;
echo "<a href=' " . $_page_link . "&tpid=dynacreate' >Create dynamic field</a>&nbsp;&nbsp;&nbsp;" ;
echo "<a href=' " . $_page_link . "&tpid=dynatest' >Dynamic field test</a>&nbsp;&nbsp;&nbsp;" ;

echo "<br /><br /><br />" ;

if($_GET['tpid'] == 'create') {
	require_once (GWPM_ROOT . '\test\create_user.php');
} else if($_GET['tpid'] == 'search') {
	require_once (GWPM_ROOT . '\test\search_user.php');
} else if($_GET['tpid'] == 'dynacreate') {
	require_once (GWPM_ROOT . '\test\dynamic_field_create.php');
} else if($_GET['tpid'] == 'dynatest') {
	require_once (GWPM_ROOT . '\test\dynamic_field_check.php');
}

// echo get_option("siteurl") . '/' . strtolower(GWPM_PAGE_TITLE) . '/?page=subscribe' ;
// require_once (GWPM_ROOT . '\test\create_user.php');
// require_once (GWPM_ROOT . '\test\search_user.php');
// require_once (GWPM_ROOT . '\test\dynamic_field_check.php');
// require_once (GWPM_ROOT . '\test\dynamic_field_create.php');

?>