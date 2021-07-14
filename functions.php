<?php

require_once 'config.php';



function validateSession() {
    if ($_SESSION['username'] != null)
        return true;
    else
        return false;
}

function insertOrUpdateLocation($lng, $lat, $id) {
    global $connection;
    $sql = "select * from `location` where uid=" . $id;
    $exist = false;
    $result = mysqli_query($connection, $sql);
    $sql1 = 'INSERT INTO `queries`(`query`) 
        VALUES ("' . $sql . '")';
    mysqli_query($connection, $sql1);
    if ($lng != "0.0" && $lat != "0.0") {
        if ($row = mysqli_fetch_array($result)) {
            $sql = "update `location` set longitude='" . $lng . "',latitude='" . $lat . "' where uid=" . $id;
            $result = mysqli_query($connection, $sql);
        } else {
            $sql = "insert into `location`(longitude,latitude,uid) values ('" . $lng . "','" . $lat . "',$id)";
            $result = mysqli_query($connection, $sql);
        }
        $sql1 = 'INSERT INTO `queries`(`query`) 
        VALUES ("' . $sql . '")';
        mysqli_query($connection, $sql1);
    }
}

function get_total_emergency() {
    global $connection;
    $count = 0;
    $sql = "select count(*) count from phi_report where status='Emergency'";
    
    $result = mysqli_query($connection, $sql);
    if ($row1 = mysqli_fetch_array($result, MYSQL_ASSOC)) {
        $count = $row1['count'];
       // echo "emergency count from query".$count;
    }

    return $count;
}

function getDiagnostics($id) {
   global $connection;
    $sql = "SELECT diag FROM `phi_report` WHERE id=" .$id;
    
    $result = mysqli_query($connection, $sql) ;
   
         
    if ($row1 = mysqli_fetch_array($result, MYSQL_ASSOC)) {
        return $row1['diag'];
    }
    return "";
}

function get_max_id_login() {
    global $connection;
    $maxid = 0;
    $sql = "select max(id) from login";
    $result = mysqli_query($connection, $sql);
    if ($row = mysqli_fetch_array($result)) {

        $maxid = $row[0];
    }

    return $maxid;
}

function isPulseOK($pulse) {
    return ( $pulse > 60 && $pulse < 100 ) ? true : false;
}

function isSugarOK($sugar) {
    return ( $sugar > 70 && $sugar < 130 ) ? true : false;
}

function isPressureOK($press) {
    return ( $press > 60 && $press < 130 ) ? true : false;
}

function isTemperatureOK($temp) {
    return ( $temp > 95 && $temp < 100 ) ? true : false;
}

function updateDiagnosticsAndSendEmailToUser($diag,$reportId){
     global $connection;
    
    
      $sql = "UPDATE `phi_report` SET `diag`='".$diag."' WHERE id=".$reportId;
        
        mysqli_query($connection,$sql) or die("Can't sent Diagnosis, ". mysqli_error());
       
         $sql = "SELECT * FROM `phi_report` WHERE id = ".$reportId;
        $result_phi = mysqli_query($connection,$sql) or die("Can't get the Diagnosis, ".  mysqli_error());
        $phi_result = mysqli_fetch_array($result_phi);
        
        
        $sql = "SELECT * FROM `user_details` WHERE id = ".$phi_result['pid'];
        $result_mu = mysqli_query($connection,$sql) or die("Can't get the Diagnosis, ".  mysqli_error());
        $row_user_detail = mysqli_fetch_array($result_mu);
        $body = "   <u>RECEIVED REPORT FROM MEDICAL USER</u><br>
            
  <br>Report Time: ".$phi_result['time']."
      <br>Status:<b> ".$phi_result['status']."</b><br><br>
          
         <u> DIAGNOSIS FROM TRUSTETD AUTHORITY</u>
                <h3>Diagnosis: ".$diag."</h3>
        
 

             
  <br><table border=1>
  <tr>
    <th>Report id</th><td>".$phi_result['id']."</td>
  </tr>
  <tr>
    <th>Patient id</th><td>".$phi_result['pid']."</td>
  <tr>
    <th>Patient name</th><td>".$row_user_detail['pname']."</td>
  </tr>
  <tr>
    <th>Pulse rate</th><td>".$phi_result['pulse']."</td>
  </tr>
  <tr>
    <th>Blood sugar</th><td>".$phi_result['bs']."</td> 
  </tr>
  <tr>
    <th>blood pressure</th><td>".$phi_result['bp']."</td>
  </tr>
  <tr>
    <th>temperature</th><td>".$phi_result['temp']."</td> 
  </tr>
</table>";

     $to = $row_user_detail['email'];
        
       sendMail($to,"Diagonostics Report", $body);
}
function getEmailBodyForReport($reportId){
    global $connection;
    
    $sql = "SELECT * FROM `phi_report` WHERE id = ".$reportId;
    
        $result_phi = mysqli_query($connection,$sql) or die("Can't get the phireport, ".  mysqli_error());
        
        $phi_result = mysqli_fetch_array($result_phi);
        
        //2. Getting mail id from medical user table
        $sql = "SELECT * FROM `user_details` WHERE id = ".$phi_result['pid'];
        $result_mu = mysqli_query($connection,$sql) or die("Can't get the userdetails, ".  mysqli_error());
        $row_user_detail = mysqli_fetch_array($result_mu);
       
        $sql = "SELECT * FROM location WHERE uid= ".$row_user_detail['uid'];
       
         $result_loc = mysqli_query($connection,$sql) or die("Can't get the location, ".  mysqli_error());
        $loc_result = mysqli_fetch_array($result_loc);

        
        $body="<table border=1><tr><th>patient name</th><td> ".$row_user_detail['pname']."
            </td></tr><th>Patient location</th>
               <td> <a target='_blank' href='https://www.google.com/maps/dir/".healthcare_location."/".$loc_result['latitude'].','. $loc_result['longitude']."'>open map</a></td>";      
        
    return $body;
    
}
function sendMail($to, $subject, $body) {

    require_once "Mail.php";

    $from = "Mobile Healthcare <mobilehealthcare3@gmail.com>";
    $to = "Medical User <" . $to . ">";

    $host = "smtp.gmail.com";
    $username = "mobilehealthcare3@gmail.com";
    $password = "ADMIN123";

    $headers = array('From' => $from,
        'To' => $to,
        'Subject' => $subject,
        'Content-type' => 'text/html; charset=iso-8859-1' . '\r\n'
    );
    
    $smtp = Mail::factory('smtp', array('host' => $host,
                'auth' => true,
                'username' => $username,
                'password' => $password));

    $mail = $smtp->send($to, $headers, $body);

    if (@PEAR::isError($mail))
        echo "Error: " . $mail->getMessage();
    else
        return true;
}

function start_alarm() {
    $file = "audio/emergency.mp3";



    echo "<embed src =\"$file\" hidden=\"true\" autostart=\"true\"></embed>";

   
}


?>
