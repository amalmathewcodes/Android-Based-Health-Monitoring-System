<?php
session_start();
//error_reporting(0);
require_once 'config.php';
require_once 'functions.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
          <script type="text/javascript" src="js/jquery-1.9.0.min.js"></script>
        <script type="text/javascript" src="js/script.js"></script>

    </head>
    
    <body onload="refresh_home_page()"><center>
        <script>
        


        </script>



        <?php
        if(isset($_GET["reset"])){
            
             if($_GET['reset']=="true"){
                  $_SESSION['reset_count']="false";
                   $_SESSION['emergency_count']= get_total_emergency();
             }else{
                  $_SESSION['reset_count']="true";
             }
             
        }
        
        
         if(isset($_POST["txtUname"] )){
        $sql = "SELECT * FROM `login` WHERE username='" . $_POST["txtUname"] . "'";
        $result_login1 = mysqli_query($connection,$sql) or die("Can't Authendicate </br></br>" . mysqli_error());
         }
         if (!empty($_POST)) {
            if ($row = mysqli_fetch_array($result_login1, MYSQL_ASSOC)) {
                if ($row["password"] == md5($_POST["txtPass"])) {
                    $_SESSION['username'] = $_POST["txtUname"];

                    $_SESSION['id'] = $row['id'];

                    $sql = "SELECT * FROM `user_details` WHERE uid='" . $row['id'] . "'";
                    $result_login2 = mysqli_query($connection,$sql) or die("Can't Authendicate </br></br>" . mysqli_error());
                    if ($row1 = mysqli_fetch_array($result_login2, MYSQL_ASSOC)) {
                        $_SESSION['usertype'] = $row1['user_type'];
                        $_SESSION['pid'] = $row1['id'];
                        $_SESSION['name'] = $row1['pname'];
                    }
                } else {

                    
                    ?>
                    <meta http-equiv="refresh" content="0;url=<?php echo hostname ?>index.php?error=Invalid Username or Password" />
                    <?php
                    exit();
                }
            } 
        }
        include 'header.php';
        
    
       if($_SESSION['usertype']!=ta){
       ?><div class="header">
            <p><font style="color: chocolate;font-size: 20px;">Download our Android app by clicking on the following link</font></p>
            <a href="<?php echo hostname ?>apk/Regimen-Client.apk">click here</a>
        </div>
        <?php }
       
        
       else{
             $sql = "SELECT * FROM `phi_report` where status='Emergency'and diag ='' order by time desc ";
             $result_report = mysqli_query($connection,$sql);
            echo '<div id="scrollit"><table id="repTable" border=1 cellpadding=1>
                <div style="position:absolute">        
                <tr><th>Report ID</th>
                <th>Patient ID</th>
                <th>Patient Name</th> 
                <th>Report Time</th>
                <th>Pulse Rate</br><i>(60-100)</i></th>
                <th>Blood Sugar</br><i>(70-130)</i></th>
                <th>Blood Pressure</br><i>(60-130)</i></th>
                <th>Temperature</br><i>(95-100°F)</i></th>
                <th>Priority</th>
                <th>Diagnosis</th></tr></div>';
        }
         
        while($row = mysqli_fetch_array($result_report))
            {
                 $qry1 = "SELECT * FROM `user_details` where id=".$row['pid']; 
                 $res1=  mysqli_query($connection,$qry1);
                 $personName="not found";
                 if($row1=  mysqli_fetch_array($res1)){
                     $personName=$row1['pname'];
                 }
               
                echo '<tr align="center" valign="center"><td>'.$row['id'].'</td>
                    <td>'.$row['pid'].'</td>
                    <td>'.$personName.'</td>
                    <td>'.$row['time'].'</td>';
                
                
                
                
               if(isPulseOK($row['pulse']))
                    echo '<td style="color: green">'.$row['pulse'].'</td>';
                else
                    echo '<td style="color: red">'.$row['pulse'].'</td>';
                
                if(isSugarOK($row['bs']))
                    echo '<td style="color: green">'.$row['bs'].'</td>';
                else
                    echo '<td style="color: red">'.$row['bs'].'</td>';
                
                if(isPressureOK($row['bp']))
                    echo '<td style="color: green">'.$row['bp'].'</td>';
                else
                    echo '<td style="color: red">'.$row['bp'].'</td>';
                
                if(isTemperatureOK($row['temp']))
                    echo '<td style="color: green">'.$row['temp'].'</td>';
                else
                    echo '<td style="color: red">'.$row['temp'].'</td>';
                
                
                if($row['status'] == "Normal")
                    echo '<td style="color: green">'.$row['status'].'</td>';
                else // state emergency
                    echo '<td style="color: red">'.$row['status'].'</td>';
                
  
                if ($row['diag'] == NULL || $row['diag'] == "") {
                 
                 echo '<td><a href=PHIReport.php?id=' . $row['id'] . '#sendDiag>Send</a></td>';
                }  else{
                    echo '<td><a href=PHIReport.php?id=' . $row['id'] . '#sendDiag>View</a></td>';
                }
            }
            echo "</div>";
           
