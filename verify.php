<?php
//error_reporting(0);
if (!isset($_SESSION)) {
session_start();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <script type="text/javascript" src="js/script.js"></script>
        <script type="text/javascript" src="js/jquery-1.9.0.min.js"></script>
        <script>
            function resetError() {
                document.getElementById("pass1").style = "none";
                document.getElementById("pass2").style = "none";
            }
            function validate() {
                var pass1 = document.getElementById("pass1").value;
                var pass2 = document.getElementById("pass2").value;
                var ok = true;
                if (pass1 == "") {
                    document.getElementById("pass1").style.borderColor = "#E34234";
                    ok = false;
                }
                if (pass2 == "") {
                    document.getElementById("pass2").style.borderColor = "#E34234";
                    ok = false;
                }
                if (pass1 != pass2) {
                    
                    document.getElementById("pass1").style.borderColor = "#E34234";
                    document.getElementById("pass2").style.borderColor = "#E34234";
                    ok = false;
                }

                return ok;
            }

        </script>
        <?php
        require_once 'config.php';
         
        if(isset($_SESSION['id'])&&$_SESSION['id']!=null  && !isset($_SESSION['username'])){
            ?>
              <div id="openModal"  class="modalDialog tableUI"><div>
                        <a href="#close" title="Close" class="close">X</a>
                        <center>

                            <form method="POST" action="verify.php" onsubmit="return validate()" >
                                <table>
                                    <tr><td>Password</td><td> <input onchange="resetError()" id="pass1" class="inputClass" type="password" value="" name="password1" ></input> </td></tr>
                                    <tr><td>Confirm password</td><td> <input onchange="resetError()" id="pass2" class="inputClass" type="password" value="" name="password2" ></input> </td></tr>
                                    <tr><td colspan="2" align="center"><input class="btn-submit"  type="submit" value="Save"></input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="btn-submit" id="clear" type="reset" value="Clear"></input></tr>
                                </table>
                            </form>
                                            
                            <?php
        }
        if (!empty($_GET)) {
            if(isset($_SESSION['username'])){
                $_SESSION['username']=null;
            }
            $activationcode = $_GET['q1s33Rwsl3K350slsWsls9iiRSSFDPOKSS'];
            $qry = "select * from activation where code='" . $activationcode . "'";

            $res = mysqli_query($connection,$qry);
            if ($row = mysqli_fetch_array($res)) {
                if ($row['status'] == 0) {
                    $_SESSION['id'] = $row['uid'];
                    
                    ?>
                  <meta http-equiv="refresh"  content="0;url=<?php echo hostname ?>verify.php#openModal" />
              


                            <?php
                        } else {
                            ?> 


                            <meta http-equiv="refresh" content="0;url=<?php echo hostname ?>index.php?error=This email id is already activated ,please login" />
                            <?php
                        }
                    } else {
                        ?> 
                        <meta http-equiv="refresh" content="0;url=<?php echo hostname ?>index.php?error=Invalid Activation code,Please try again" />
                        <?php
                    }
                }
                if (!empty($_POST)) {
                    $pass = $_POST['password1'];

                    $pass = md5($pass);

                    $qry = "update login set password='" . $pass . "' where id=" . $_SESSION['id'];
                    mysqli_query($connection,$qry);

                    if (mysqli_errno($connection) == 0) {

                        $qry1 = "SELECT * FROM `login` WHERE id='" . $_SESSION['id'] . "'";


                        $result_login1 = mysqli_query($connection,$qry1) or die("Can't Authenticate </br></br>" . mysqli_error());
                        if ($row = mysqli_fetch_array($result_login1, MYSQL_ASSOC)) {
                            $_SESSION['username'] = $row['username'];
                            $sql = "SELECT * FROM `user_details` WHERE uid='" . $row['id'] . "'";
                            $result_login2 = mysqli_query($connection,$sql) or die("Can't Authenticate </br></br>" . mysqli_error());
                            if ($row1 = mysqli_fetch_array($result_login2, MYSQL_ASSOC)) {
                                $_SESSION['usertype'] = $row1['user_type'];
                                $_SESSION['pid'] = $row1['id'];
                                $_SESSION['name'] = $row1['pname'];
                                $qryUpdate = "update activation set status=1 where uid=" . $_SESSION['id'];
                                mysqli_query($connection,$qryUpdate);
                                
                                ?> <meta http-equiv="refresh" content="0;url=<?php echo hostname ?>home.php">
                                <?php
                            }
                        }
                    }
                }
                ?>