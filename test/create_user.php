<?php

function createDateRangeArray($strDateFrom,$strDateTo)
{
    // takes two dates formatted as YYYY-MM-DD and creates an
    // inclusive array of the dates between the from and to dates.

    // could test validity of dates here but I'm already doing
    // that in the main script

    $aryRange=array();

    $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
    $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

    if ($iDateTo>=$iDateFrom)
    {
        while ($iDateFrom<$iDateTo)
        {
            $iDateFrom+= ( 48640000 * rand (1, 6)); // add 24 hours
            array_push($aryRange,date('m/d/Y',$iDateFrom));
        }
    }
    return $aryRange;
}

$user_key = isset($_POST['user_key']) ? $_POST['user_key'] : null ;
$noOfUsers = isset($_POST['noOfUsers']) ? $_POST['noOfUsers'] : null;
$gender =  isset($_POST['gender']) ? $_POST['gender'] : null; 

$tempage = createDateRangeArray( '1980-10-01', '1985-10-05') ;
$userIdList = array() ;

if(!isNull($user_key)) {

	$mymodel = new GwpmProfileModel() ;
	
	for($cnt = 0; $cnt < $noOfUsers; $cnt++) {
		$user_name = $user_key . $cnt ;
		$user_email = $user_name . '@127.0.0.1' ;
		$random_password = 'passd' ;
		$user_id = username_exists( $user_name );
		if ( !$user_id and email_exists($user_email) == false ) {
			$user_id = wp_create_user( $user_name, $random_password, $user_email );
			echo __("User created: " . $user_id . '<br />') ;
			array_push($userIdList, $user_id) ;
		} else {
			echo __('User already exists.  Password inherited.' . '<br />');
		}
		
		$_POST['userId'] = $user_id ;
		$_POST['first_name'] = $user_name . "fn" ;
		$_POST['last_name'] = $user_name . "ln" ;
		$_POST['user_email'] = $user_email ;
		$_POST['gwpm_gender'] =  $gender ;		
				
		$_POST['gwpm_martial_status'] =  '1' ;
		$_POST['gwpm_religion'] = 'Hindu' ;
		$_POST['gwpm_sevvai_dosham'] =  rand ( 1 , 2 ) ;
		$_POST['gwpm_starsign'] =  rand ( 1 , 26 ) ;
		$_POST['gwpm_user'] =  '1' ;
		$_POST['gwpm_caste'] =  'Mudliyar' ;
		$_POST['gwpm_zodiac'] =  rand ( 1 , 12 ) ;
		$_POST['gwpm_contact_no'] = ( rand ( 1 , 12 ) * 100000000 ) + 989898 + rand (5000 , 10000);
				
		$gwpm_education['qualification'] = "" . rand ( 1 , 4) ;
		$gwpm_education['qualification_other'] =   "" . 'Other' ;		
		$gwpm_education['specialization'] =  "" . rand ( 1 , 4) ;
		$gwpm_education['status'] =  "" . rand ( 1 , 4) ;    
		
		$_POST['gwpm_education'] =  $gwpm_education ;
		
		$dynaKeys = getDynamicFieldKeys() ;
		foreach($dynaKeys as $__keys) {
			echo "<br /> Dyna: " . $__keys ;
			$_POST[$__keys] = "" . rand ( 1 , 3) ; 
		}
		
		$abt = createDateRangeArray( '1980-10-01', '1985-10-05') ;
		$newdata =  $abt[0] ;
		echo $newdata . "<br />";
		
		$_POST['gwpm_dob'] = $newdata;
		
	//	$profileObj = new GwpmProfileVO($_POST);
		$profileObj = new GwpmProfileVO($_POST, $dynaKeys);
		
		$profileObj->gwpm_profile_photo = $_FILES["gwpm_profile_photo"] ;
		$validateObj = $profileObj->validate();		
		
		if (sizeof($validateObj) == 0) {
			$mymodel->updateUser($profileObj);
			echo __('success_message', 'Profile updated successfully!!' . '<br />' );
		} else {
			echo __('Please correct the below fields: ' . '<br />');
			print_r($validateObj) ;
			echo "<>br />" ;
		}
	}
	
	if(sizeof($userIdList) > 0) {
		
		global $wpdb;
		
		$table_name = $wpdb->prefix . "usermeta";
		$column_name = $wpdb->prefix . 'user_level' ;
		$column_name_2 = $wpdb->prefix . "capabilities";
		
		echo $table_name . '-' . $column_name  . ' - ' . implode(',', $userIdList);
		foreach($userIdList as $_userId) {
			$queryString = "update $table_name set meta_value = 1 WHERE meta_key = '$column_name' AND user_id = %s " ;
			echo '<br />' . $queryString ;
			$preparedSql = $wpdb->query($wpdb->prepare($queryString, $_userId));
			$result = $wpdb->get_results($preparedSql);	
			print_r($result) ;	
			$queryString = "UPDATE $table_name SET meta_value = 'a:1:{s:14:\"matrimony_user\";b:1;}' WHERE meta_key = '$column_name_2' AND user_id = (%s)" ;
			echo '<br />' .$queryString ;
			$preparedSql = $wpdb->query($wpdb->prepare($queryString, $_userId));
			$result = $wpdb->get_results($preparedSql);	
			print_r($result) ;
		}
	}
	
} else {
	?>
<form method="post" >
<div style='width: 200px;'>User Key: </div><input type="text" name="user_key" ><br />
<div style='width: 200px;'>No of users: </div><input type="text" name="noOfUsers" ><br />
<div style='width: 200px;'>gender: </div><input type="text" name="gender" ><br />
<input type="submit" value="createUser"><br />
</form>
<?php 
}
