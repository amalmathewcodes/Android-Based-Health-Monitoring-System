<?php
error_reporting(0);
session_start();


?>
<link rel="stylesheet" href="style.css">
<script type="text/javascript" src="js/script.js"></script>
<script>
function gotoemergency(){
    window.location="<?php echo $hostname ?>home.php?reset=<?php if(!isset($_SESSION['reset_count'])){ echo "true";} else{ echo $_SESSION['reset_count']; }?>";
}
</script>

    <?php
require_once 'functions.php';
    if(!validateSession()){
        
        ?>  <meta http-equiv="refresh" content="0;url=<?php echo hostname ?>index.php?error=Please login to continue" /> <?php
    }else{
        
        ?>
        <table id="userHeader" width="100%" cellspacing="0" cellpadding="0" border="0" >
            <tr>
                <td width="800" height="62" >
                 <ul id="menu">
        <li><a class="btn" href="home.php">Home</a></li>
        <?php        
        if($_SESSION['usertype']==ta){
        ?>
        
  
		<li><a class="btn" href="user.php">Users</a></li>
        <li><a class="btn" href="PHIReport.php">PHI Report</a></li>
        <?php 
        }else{
        ?>
        <li><a class="btn" href="<?php echo hostname ?>userInfo.php">My Info</a></li>
        <li><a class="btn" href="<?php echo hostname ?>myReport.php">My Reports</a></li>
        <?php }?>
        <li><a  class="btn" href="<?php echo hostname ?>logout.php">Logout</a></li>
    </ul>
            </td>
            <td valign="top"> 
                <div class="wrap">
                <ul id="user-header">
            <li>
                 <h4><?php  echo "Logged in as: "?><font style="color:green;"><?php echo $_SESSION['name'] ?></font></h4>
                 
      
            </li>
            
             <?php    
             
        if($_SESSION['usertype']==ta){
        ?>
            <li>
                <div onclick="gotoemergency()" id="notification" class="header-notification">
                    <?php if(isset($_SESSION['reset_count'])&&$_SESSION['reset_count']=="true"){?>
                    <div class="badge" style="opacity: 1; display: block;"><?php }else{ ?>
                    <div class="badge" style="opacity: 1; display: none;"><?php }
                 //  echo "sessioncount:".$_SESSION['emergency_count'];
                 //  echo "emergency count:".get_total_emergency();
                    if(!isset($_SESSION['emergency_count'])){
                        $_SESSION['emergency_count']= get_total_emergency();
                    }
                     if($_SESSION['emergency_count']<  get_total_emergency()){
                            start_alarm ();
                            
                        }
                        $count=get_total_emergency()-$_SESSION['emergency_count'];
                         echo $count;
                        
                         ?>
                    </div>
                </div>
            </li>
            
                </div>
                </ul></td>
                <td width="140" height="40"> <div style="margin: 0px 44px" >
                        <div onclick="refresh()" id="refresh" > <img style="width:45px;cursor: pointer;" src="images/refresh.jpg" /></div>
                       
                        <img onclick="stoprefresh()" id="ref" style="display: none;  width:45px;cursor: pointer;" src="images/refresh.gif"  />
                </div></td>
                <?php 
        }
       if (isset($_GET['message'])) {
           ?>
                </tr>
                <tr>
                    <td colspan="2">

                 <?php   echo "<center><div id='error1' style='color:green;background-color:white;width:400px'><h2>" . $_GET['message'] . "</h2></div></center>";
                
                ?></td>
                </tr>
       <?php }?>
            </table>
        
     
        <center>
            
  

   
    <div class="header">
    
    <h1 style="color: brown">ANDROID BASED REMOTE HEALTHCARE MONITORING SYSTEM</h1>
    
    
    </div>

    </center>
 <?php } ?>