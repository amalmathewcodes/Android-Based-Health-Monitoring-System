<?php

error_reporting(0);
require_once 'config.php';

if(isset($_POST["search"]))
{
	
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		die();
	}
$connecDB=mysqli_connect(db_host, db_user, db_pass) or die('Could not Connect'.mysqli_error());
  
	
	
	$searchString =  strtolower(trim($_POST["search"])); 
	
	
	$searchString = filter_var($searchString, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
	
	$results = mysqli_query($connection,"SELECT * FROM user_details WHERE pname like '%$searchString%' OR id like '%$searchString%' OR address like '%$searchString%' ");
	
	
        $header='<table id="repTable" border=1 cellpadding=1><div style="position:absolute">        
                <th>Patient ID</th>
                <th>Patient Name</th>
                <th>Gender</th>
                <th>Address</th>
                <th>Mobile Number</th>
                <th>Date Of Birth</th>
                <th>Emergency Number</th>
                <th>Email</th>
                <th>Action</th></tr></div>';
        $data="";
        while($row=  mysqli_fetch_array($results)){
            $data =$data."<tr>"
                    . "<td>".$row['id']."</td>"
                    . "<td>".$row['pname']."</td>"
                    . "<td>".$row['gender']."</td>"
                    ."<td>".$row['address']."</td>"
                    ."<td>".$row['mobno']."</td>"
                    ."<td>".$row['dob']."</td>"
                    ."<td>".$row['emerg']."</td>"
                    ."<td>".$row['email']."</td>"
                     ."<td><a href='user.php?delete=".$row['id']."'>Delete</a></td>"
                    ."</tr>";
        }
        echo $header.$data."</table>";
	
	mysqli_close($connecDB);
}
?>

