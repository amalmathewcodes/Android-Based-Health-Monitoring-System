<html>
    <head>
        <!--<link rel="stylesheet" href="style.css">-->

        <script type="text/javascript" src="js/jquery-1.9.0.min.js"></script>
        <script type="text/javascript" src="js/script.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#searchField").keyup(function (e) {

                   
                    $(this).val($(this).val().replace(/\s/g, ''));

                    var username = $(this).val();

                    
                    $.post('search_report_ajax.php', {'search': username}, function (data) {
                        if (username != '') {
                            $("#repTable").empty();
                            $("#repTable").html(data);
                        }
                    });

                });

            });



        </script>
    </head>

    <?php
error_reporting(0);



    require_once 'config.php';
   // require_once 'functions.php';
    include 'header.php';
    ?><style>
<?php include 'style.css'; ?>
    </style>     

   
    <body >
    <center>



        <input id="searchField" autofocus="true" type="input" value="<?php if(isset($_GET['search'])){ echo $_GET['search'];} ?>"  />
        <h3 id="tabCap" style="color: green">PHI Report of All Medical Users</h3><?php
        
            

        $sql = "SELECT * FROM `phi_report` ORDER BY `phi_report`.`id` DESC ";
      //  }
        
        $result_report = mysqli_query($connection, $sql);

        //generating header

        echo '<div id="scrollit"><table id="repTable" border=1 cellpadding=1>
                    
                <tr><th>Report ID</th>
                <th>Patient ID</th>
                <th>Patient Name</th>
                <th>Report Time</th>
                <th>Pulse Rate</br><i>60-100</i></th>
                <th>Blood Sugar</br><i>70-130</i></th>
                <th>Blood Pressure</br><i>60-130</i></th>
                <th>Temperature</br><i>95-100°F</i></th>
                <th>Priority</th>
                <th>Diagnosis</th></tr> <!--</div> -->';
        
        while ($row = mysqli_fetch_array($result_report)) {
        $qry1 = "SELECT * FROM `user_details` where id=" . $row['pid'];
        
        $res1 = mysqli_query($connection, $qry1);
        
        if ($row1 = mysqli_fetch_array($res1)) {
        $personName = $row1['pname'];
        }

        echo '<tr align="center" valign="center"><td>' . $row['id'] . '</td>
                    <td>' . $row['pid'] . '</td>
                    <td>' . $personName . '</td>
                    <td>' . $row['time'] . '</td>';




        if (isPulseOK($row['pulse']))
        echo '<td style="color: green">' . $row['pulse'] . '</td>';
        else
        echo '<td style="color: red">' . $row['pulse'] . '</td>';

        if (isSugarOK($row['bs']))
        echo '<td style="color: green">' . $row['bs'] . '</td>';
        else
        echo '<td style="color: red">' . $row['bs'] . '</td>';

        if (isPressureOK($row['bp']))
        echo '<td style="color: green">' . $row['bp'] . '</td>';
        else
        echo '<td style="color: red">' . $row['bp'] . '</td>';

        if (isTemperatureOK($row['temp']))
        echo '<td style="color: green">' . $row['temp'] . '</td>';
        else
        echo '<td style="color: red">' . $row['temp'] . '</td>';

       
        if ($row['status'] == "Normal")
        echo '<td style="color: green">' . $row['status'] . '</td>';
        else 
        echo '<td style="color: red">' . $row['status'] . '</td>';

        
        if ($row['diag'] == NULL || $row['diag'] == "") 
        echo '<td><a href=PHIReport.php?id=' . $row['id'] . '#sendDiag>Send</a></td>';
        else
        echo '<td><a href=PHIReport.php?id=' . $row['id'] . '#sendDiag>View</a></td>';
        
        }
        echo "</div>";

        ?>
        <div id="sendDiag" class="modalDialogFixed tableUI"><div>
                <a href="#close" title="Close" class="close">X</a>


                <form method="POST" action="PHIReport.php" onsubmit="return validate()">
                    <div style="float:left">
                        <input  type="hidden" value="<?php echo $_GET['id']?>"  name="reportId"></input>
                        <textarea style=""  class="textAreaClass" type="text" name="diag"  placeholder="Mention diagnostics"><?php if(isset($_GET['id'])){ echo  getDiagnostics($_GET['id']); }?></textarea>
                        <br><br><br><label>Ambulance required </label>&nbsp;&nbsp;<input style="" width="20px" height="30px" type="checkbox" name="isAmbulanceRequired"></input>

                        <br><br><input class="btn-submit"  type="submit" value="Save"></input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="btn-submit" id="clear" type="reset" value="Clear"></input>
                    </div>
                </table></div>
            </form>


    </center><div>
    </div>
</center></body></html>
<?php



require_once 'config.php';
require_once  'header.php';

if (!empty($_POST['diag'])) {

$diag = $_POST['diag'];
$reportId = $_POST['reportId'];
updateDiagnosticsAndSendEmailToUser($diag,$reportId);

if(isset($_POST['isAmbulanceRequired'])){
  $sql = "select * from user_details where user_type='ad'";
  
    $result = mysqli_query($connection, $sql);
    
    $row_user_detail = mysqli_fetch_array($result);
     
    $body= getEmailBodyForReport($_POST['reportId']);
    
   sendMail( $row_user_detail['email'], "Pick user immediately", $body);
    
}

 



?>
<meta http-equiv="refresh" content="0;url=<?php echo hostname ?>PHIReport.php?message=Diagnosis sent successfully." 
<?php
}        
 
  mysqli_close($connection);
  ?>


   
                           
         