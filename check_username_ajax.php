<?php

//error_reporting(0);
require_once 'config.php';

if(isset($_POST["username"]))
{
	
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		die();
	}

	
	
	$username =  strtolower(trim($_POST["username"])); 
	
	
	$username = filter_var($username, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
	
	$results = mysqli_query($connection,"SELECT id FROM login WHERE username='$username'");
	
	
	$username_exist = mysqli_num_rows($results); //total records
	
	
	if($username_exist) {
            
		die('<img id="user_stat" src="images/cross.png" />');
	}else{
            if(ctype_alnum($username))
		die('<img id="user_stat" src="images/tick.png" />');
            else
                die('<img id="user_stat" src="images/cross.png" />');
	}
	
	
	mysqli_close($connecDB);
}
?>

