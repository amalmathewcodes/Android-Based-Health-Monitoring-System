<html>
    <head>
<link rel="stylesheet" href="style.css">
            <script type="text/javascript" src="js/jquery-1.9.0.min.js"></script>
        <script type="text/javascript" src="js/script.js"></script>
        <script type="text/javascript">
$(document).ready(function() {
	$("#searchField").keyup(function (e) {
	
		//removes spaces from username
		$(this).val($(this).val().replace(/\s/g, ''));
		
		var username = $(this).val();
		
			//$("#checkuser").html('<img width="30" src="images/refresh.gif" />');
			$.post('search_user_ajax.php', {'search':username}, function(data) {
                            if(username!=''){
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
    include 'header.php';
	
    require_once 'config.php';
    ?>
	<center>
	
	
            <input id="searchField" autofocus="true" type="input" value="<?php echo $_GET['search']?>"  />
</center>

<center>
<h3 id="tabCap" style="color: black">All Medical Users</h3>
<?php
if (!empty($_GET)) {
                if ($_GET['message'] != '') {

                    echo "<div id='error1' style='color:green'><h2>" . $_GET['message'] . "</h2></div>";
                }
                if ($_GET['delete'] != '') {
                    $sql1="delete from activation where uid=(select uid from user_details where id=".$_GET['delete'].")";                    
                    $sql3="delete from keys where uid=(select uid from user_details where id=".$_GET['delete'].")";
                    $sql4="delete from location where uid=(select uid from user_details where id=".$_GET['delete'].")";
                    $sql5="delete from login where id=(select uid from user_details where id=".$_GET['delete'].")";
                    $sql6="delete from phi_report where pid=".$_GET['delete'];
                    $sql7="delete from rejected where uid=(select uid from user_details where id=".$_GET['delete'].")";
                    $sql2="delete from user_details where id=".$_GET['delete'];
                    mysqli_query($connection,$sql1);mysqli_query($connection,$sql2);mysqli_query($connection,$sql3);mysqli_query($connection,$sql4);
                    mysqli_query($connection,$sql5);mysqli_query($connection,$sql6);mysqli_query($connection,$sql7);
                   ?> <meta http-equiv="refresh" content="10;url=<?php echo hostname ?>user.php?message=Deletion successful" />
                        <img style="width:100px;z-index: 9999;" src="images/refresh.gif" />      
                        <?php
                }
}

$sql = "SELECT * FROM `user_details`";
$result_report = mysqli_query($connection,$sql);
        
echo '<table id="repTable" border=1 cellpadding=1>
                <div style="position:absolute">        
                <th>Patient ID</th>
                <th>Patient Name</th>                
                <th>Mobile Number</th>
               
                <th>Email</th></tr></div>';
            

while( $row = mysqli_fetch_assoc($result_report) )
{
			 echo '<tr align="center" valign="center"><td><a href="userInfo.php?id='.$row['id'].'">'.$row['id'].'</a></td>
                    <td>'.$row['pname'].'</td>
                   
					<td>'.$row['mobno'].'</td>
					
					<td>'.$row['email'].'</td>';
                    }

				mysqli_close();
			
        ?>
		