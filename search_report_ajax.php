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
	
        $query_phi_join_userdetails="SELECT a.id,a.pid,b.pname,a.time,a.pulse,a.bs,a.bp,a.temp,a.status,a.diag FROM phi_report a,user_details b WHERE (b.pname like '%$searchString%' OR a.pid like '%$searchString%' OR a.id like '%$searchString%' OR a.time like '%$searchString%' OR a.status like '%$searchString%') and   a.pid=b.id";
	$results = mysqli_query($connection,$query_phi_join_userdetails);
        $header='<table id="repTable" border=1 cellpadding=1>
                <div style="position:absolute">        
                <tr><th>Report ID</th>
                <th>Patient ID</th>
                <th>Patient Name</th>
                <th>Report Time</th>
                <th>Pulse Rate</br><i>Normal 60-100</i></th>
                <th>Blood Sugar</br><i>Normal 70-130</i></th>
                <th>Blood Pressure</br><i>Normal 60-130</i></th>
                <th>Temperature</br><i>Normal 95-100°F</i></th>
                <th>Priority</th>
                <th>Diagnosis</th></tr></div>';;
        $data="";

        $i=1;
        while($row=  mysqli_fetch_array($results)){
            $data2="";
               if($row['status'] == "Normal")
                    $data2="<td style='color: green'>".$row['status']."</td>";
                        
                else // state emergency
                    $data2="<td style='color: red'>".$row['status']."</td>";
                 if ($row['diag'] == NULL || $row['diag'] == "") 
                   $data2=$data2.'<td><a href=PHIReport.php?id=' . $row['id'] . '#sendDiag>Send</a></td>';
                 else
                     $data2=$data2.'<td><a href=PHIReport.php?id=' . $row['id'] . '#sendDiag>View</a></td>';
        
//             if($row['diag'] == NULL || $row['diag'] == "") 
//                    $data2=$data2. '<td><button style="color: red" tag="send" name="'.$row['id'].'" onclick=diagClick(this)>Send</button></td>';
//                else
//                    $data2=$data2. '<td><button style="color: blue" tag="view" name="'.$row['id'].'" onclick=diagClick(this)>View</button></td>';
//            
            
            $data =$data."<tr>"
                    . "<td>".$row['id']."</td>"
                    . "<td>".$row['pid']."</td>"
                    . "<td>".$row['pname']."</td>"
                    . "<td>".$row['time']."</td>"
                    ."<td>".$row['pulse']."</td>"
                    ."<td>".$row['bs']."</td>"
                    ."<td>".$row['bp']."</td>"
                    ."<td>".$row['temp']."</td>".$data2             
                    ."</tr>";
          
                
               
               
            
             
             
       }
       
        echo $header.$data."</table>";
   
	
	mysqli_close($connecDB);
}
?>

