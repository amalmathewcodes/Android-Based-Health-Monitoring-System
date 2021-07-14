<?php


if(!empty($_GET))
{
    $id = $_GET['pid'];
    require_once 'config.php';
    $rep_array = array();
    $sql = "SELECT * FROM `phi_report` WHERE pid=(select id from user_details where uid=".$id.")";
    $result_rep = mysqli_query($connection,$sql) or die("Error: ".  mysqli_error());
    while($row = mysqli_fetch_array($result_rep))
    {
        $single_data = array(
            'Id' => $row['id'],
            'RepTime' => $row['time'],
            'PulseRate' => $row['pulse'],
            'BSugar' => $row['bs'],
            'BPressure' => $row['bp'],
            'Temperature' => $row['temp'],
            'Status' => $row['status'],
            'Diagnosis' => $row['diag'],
            
        );
        array_push($rep_array, $single_data);
        
    }
    $json = json_encode($rep_array);
    echo $json;
}
else
{
    echo "NoParams";
}

?>
