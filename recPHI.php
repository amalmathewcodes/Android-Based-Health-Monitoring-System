<?php


include 'functions.php';
if(!empty($_POST))
{
   
     
    $msg=$_POST['phirep'];

    
    
           $phi_rep = json_decode($msg, TRUE);
            require_once 'config.php';
    $sql = "INSERT INTO `phi_report`(`pid`, `time`, `pulse`, `bs`, `bp`, `temp`, `status`) 
        VALUES ((select id from user_details where uid=".$phi_rep['Pid']."),'".$phi_rep['RepTime']."',".$phi_rep['PulseRate'].",".$phi_rep['BSugar'].",".$phi_rep['BPressure'].",".$phi_rep['Temperature'].",'".$phi_rep['Status']."')";
insertOrUpdateLocation($phi_rep['lng'],$phi_rep['lat'],$phi_rep['Pid']);    

    mysqli_query($connection,$sql) or die("Error: ".mysqli_error());
     echo "true";
 
     
   /* mysqli_query($connection,$sql);
    $sql1 = "INSERT INTO `location`(`longitude`, `lattitude`, `uid`) 
         VALUES ('".$phi_rep['lng']."','".$phi_rep['lat']."',".$phi_rep['Pid'].")";
    mysqli_query($connection,$sql) or die("Error: ".mysqli_error());
    mysqli_query($connection,$sql1) or die("Error: ".mysqli_error($connection));
   
    echo "true";
     */
    
}
else
    echo 'NoParams';

?>