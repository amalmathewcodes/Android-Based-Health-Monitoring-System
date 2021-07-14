<?php


if(!empty($_POST))
{
    require_once 'config.php';
    $sql = "SELECT * FROM `login` WHERE username='".$_POST["uname"]."'";
     $sql1='INSERT INTO `queries`(`query`) 
        VALUES ("'.$sql.'")';
    mysqli_query($connection,$sql1);
    $result_login_mu = mysqli_query($connection,$sql) or die("Can't Authendicate".  mysqli_error());
    if($row = mysqli_fetch_array($result_login_mu, MYSQL_ASSOC))
    {
        if($row["password"] == md5($_POST["pass"]))
            echo $row['id'];
        else
            echo "WrPass";
    }
    else
        echo "WrUname";
    mysqli_close($connection);
}
else
    echo 'NoParams';

?>
