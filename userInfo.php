<center><?php
  error_reporting(0);
    include 'header.php';
    require_once 'config.php';

    if ($_SESSION['usertype'] == 'ta') {
        $pid = $_GET['id'];
        $sql1 = "SELECT * FROM location where uid in (select uid from user_details where id=$pid)";
        $result1 = mysqli_query($connection,$sql1);
        if ($row1 = mysqli_fetch_array($result1)) {

            $long = $row1['longitude'];
            $lat = $row1['latitude'];
        }else{
            $long='';
            $lat='';
        }
    } else
        $pid = $_SESSION['pid'];
    ?>
    <form method="POST" action="userInfo.php?id=<?php echo $pid; ?>">
        <table class="tableUI">
    <?php
    if (!empty($_POST)) {

        $sql = "update `user_details` set pname='" . $_POST["name"] . "',dob='" . $_POST["dob"] . "',address='" . $_POST["address"] . "',mobno='" . $_POST["mobile"] . "',email='" . $_POST["email"] . "' where id=" . $pid;
       
        mysqli_query($connection,$sql);
        
        if (mysqli_errno($connection) == 0) {
            $_SESSION['name'] = $_POST['name'];
            
            ?><tr><td colspan="2">
                            <font style="color:green;">Updated Successfully</font>
                        </td></tr><?php
        } else {
            ?><tr><td colspan="2"><font style="color:red;">Updation Failed</font></td></tr><?php
                }
            }
           
            
            $sql = "SELECT * FROM user_details a,login b where a.uid=b.id and a.id=" . $pid;

            $result_report = mysqli_query($connection,$sql);

            if ($row = mysqli_fetch_array($result_report)) {
                ?>

                <tr><td>Username</td><td> <input type="text" value="<?php echo $row['username'] ?>" name="username" disabled="true"></input> </td></tr>
                <tr><td>Person Name</td><td> <input type="text" value="<?php echo $row['pname'] ?>" name="name" ></input> </td></tr>
                <tr><td>DOB</td><td> <input type="text" value="<?php echo $row['dob'] ?>" name="dob" ></input> </td></tr>
                <tr><td>Address</td><td> <textarea type="text" name="address" ><?php echo $row['address'] ?></textarea> </td></tr>
                <tr><td>Mobile</td><td> <input type="text" value="<?php echo $row['mobno'] ?>" name="mobile" ></input> </td></tr>
                <tr><td>Email</td><td> <input type="text" value="<?php echo $row['email'] ?>" name="email" ></input> </td></tr>
                <tr>
    <?php
    if ($_SESSION['usertype'] == 'ta' && $long != '') {
        ?><td colspan="2" align="right"><a  href=""><input type="submit" value="Save"></input></a>
            <a target="blank" href="https://www.google.com/maps/dir/<?php echo healthcare_location."/".$lat.','.$long; ?>"><input type="button" value="View Location">
                            <a href="user.php?delete=<?php echo $_GET['id']; ?>"><input type="button" value="Delete"></input></a>
                                </input></a>
                    <?php
                    } else if ($_SESSION['usertype'] == 'ta') {
                        ?>
                        <td colspan="2" align="right"><a  href=""><input type="submit" value="Save"></input></a>
                            <a href="user.php?delete=<?php echo $_GET['id']; ?>"><input type="button" value="Delete"></input></a>
                        <?php
                        } else {
                            ?><td colspan="2" align="center"><a  href=""><input type="submit" value="Save"></input></a><?php
                        }
                        ?> </td>
                </tr>
            </table>
        </form>
                                <?php
                            }
                            ?></center>