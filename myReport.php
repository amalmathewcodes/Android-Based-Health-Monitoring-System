<center>
<?php
 error_reporting(0);
include 'header.php';
require_once 'config.php';

        
$sql = "SELECT * FROM `phi_report` where pid=".$_SESSION['pid'];
          
            $result_report = mysqli_query($connection,$sql);
          
            
            echo '<div id="scrollit"><table id="repTable" border=1 cellpadding=1>
                <tr><th>Report ID</th>
                
                
                <th>Report Time</th>
                <th>Pulse Rate</br><i>60-100</i></th>
                <th>Blood Sugar</br><i>70-130</i></th>
                <th>Blood Pressure</br><i>60-130</i></th>
                <th>Temperature</br><i>95-100°F</i></th>
                <th>Priority</th>
                <th>Diagnosis</th></tr>';
            
           
            require_once 'functions.php';
            
            while($row = mysqli_fetch_array($result_report))
            {
                echo '<tr align="center" valign="center"><td>'.$row['id'].'</td>
                    
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
                else 
                    echo '<td style="color: red">'.$row['status'].'</td>';
                 echo '<td style="max-width: 160px;color:   ">'.$row['diag'].'</td>';
                
            }
            
        echo "</div>";
        mysqli_close();
?>
</center>