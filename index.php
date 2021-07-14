<?php
//error_reporting(0);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN">
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <script type="text/javascript" src="js/jquery-1.9.0.min.js"></script>
        <script type="text/javascript" src="js/script.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#username").keyup(function(e) {

                    
                    $(this).val($(this).val().replace(/\s/g, ''));

                    var username = $(this).val();
                    if (username.length < 3) {
                        $("#checkuser").html('');
                        document.getElementById("username").style.borderColor = "#E34234";
                        return;
                    }

                    if (username.length >= 3) {
                        $("#checkuser").html('<img width="30" src="images/refresh.gif" />');
                        $.post('check_username_ajax.php', {'username': username}, function(data) {
                            $("#checkuser").html(data);
                        });
                        document.getElementById("username").style.borderColor = "black";
                    }
                    
                });
            });
        </script>
        <title>Login</title>
    </head>
    <body>
        <script type="text/javascript">

            function loginTA()
            {
                var uname = document.forms["logform"]["txtUname"].value;
                var pass = document.forms["logform"]["txtPass"].value;
                var status = document.getElementById("error");
                if (uname == "")
                {
                    status.innerHTML = "<h2>Username Can't be empty</h2>";
                    return false;
                }
                else if (pass == "")
                {
                    status.innerHTML = "<h2>Password Can't be empty</h2>";
                    return false;
                }
            }

            function resetError()
            {
                var status = document.getElementById("error");
                status.innerHTML = "";
            }
            function resetError1()
            {
                var status = document.getElementById("error1");
                status.innerHTML = "";
            }
            function validate() {

                var username = document.getElementById("username").value;
                var user_stat_obj = document.getElementById("user_stat");
                if (user_stat_obj == null) {
                    document.getElementById("username").style.borderColor = "#E34234";
                    return false;
                }
                var user_stat = document.getElementById("user_stat").getAttribute("src");

                if (user_stat == "images/cross.png") {

                  
                    return false;
                }
                return true;
            }

        </script>
        <center>
            <?php
            if (!empty($_GET)) {
                if (isset($_GET['error'] )) {

                    echo "<div id='error1' style='color:red'><h2>" . $_GET['error'] . "</h2></div>";
                }
                if (isset($_GET['message'])) {

                    echo "<div id='error1' style='color:green'><h2>" . $_GET['message'] . "</h2></div>";
                }
            }
            ?>
            <div id="error" style="color: red"></div>
            <div id="indexDiv" style="float:right">


                <h1>Welcome to Regimen Healthcare</h1>
                <form  method="POST" name="logform" action="home.php" onsubmit="return loginTA()">

                    UserName <input class="inputClass" type="text" id="txtUname" name="txtUname" onchange="resetError()" placeholder="username" /></br></br>
                    Password <input class="inputClass" type="password" id="txtPass" name="txtPass" onchange="resetError()" placeholder="password"/></br></br>
                    <input class="btn-submit" type="submit" value="Login">
                    <input class="btn-submit" type="reset" value="Clear">

                </form>
                <a href="#openModal">No Login? Sign up here</a>


        </center>
    </div>
    <div style="clear:both"></div>
    <center>
        <?php
        if (!empty($_POST)) {
            
                require_once 'config.php';
                require_once 'functions.php';
                if ($_POST['transid'] == "a1s2d3f4") {
                $pass = md5(rand(0, 10000));
                $sql1 = "insert into login (username,password) values('" . $_POST['username'] . "','" . $pass . "')";
                mysqli_query($connection,$sql1);

                if (mysqli_errno($connection) == 0) {
                    $id = get_max_id_login();
                    $sql2 = "insert into user_details(pname,gender,address,mobno,dob,emerg,email,uid,user_type)values('" . $_POST['name'] . "','" . $_POST['gender'] . "','" . $_POST['address'] . "','" . $_POST['mobile'] . "','" . $_POST['dob'] . "','" . $_POST['emerg'] . "','" . $_POST['email'] . "'," . $id . ",'user')";
                    mysqli_query($connection,$sql2);
                    if (mysqli_errno($connection) == 0) {
                        $email = $_POST['email'];

                        $status = 0;
                        $activationcode = md5($email . time());
                        $url = hostname."verify.php?q1s33Rwsl3K350slsWsls9iiRSSFDPOKSS=" . $activationcode;
                        $qry = "insert into activation(code,uid,status) values('" . $activationcode . "','" . $id . "'," . $status . ") ";
                        mysqli_query($connection,$qry);
                        $body = 'Hi ' . $_POST['name'] . ', <br/> <br/> Please verify your email address by clicking on the following link. <br/> <br/> '
                                . '<a href="' . $url . '">' . $url . '</a>';



                        sendMail($email, "Email verification", $body);
                        ?>
                        <meta http-equiv="refresh" content="0;url=<?php echo hostname ?>index.php?message=Registration successful, please activate email." />
                        <img style="width:100px;z-index: 9999;" src="images/refresh.gif" />                                        
                        <?php
                        
                    }
                } else {
                    ?><meta http-equiv="refresh" content="0;url=<?php echo hostname ?>index.php?error=Registration Failed,Please try again" />
                    <img style="width:100px;z-index: 9999;" src="images/refresh.gif" />
                    <?php
                }
            } else {
                ?><meta http-equiv="refresh" content="0;url=<?php echo hostname ?>index.php?error=Registration Failed,Invalid Transaction id" />
                <img style="width:100px;z-index: 9999;" src="images/refresh.gif" />
                <?php
            }

            exit;
        }
        ?>
        <div id="openModal" class="modalDialog tableUI"><div>
                <a href="#close" title="Close" class="close">X</a>


                <form method="POST" action="index.php#openModal" onsubmit="return validate()">
                    <table >
                        <tr><td>Username</td><td><div><input style="float:left;" onchange="resetError1();" id="username" class="inputClass" type="text" value="" name="username"  placeholder="Min 3 characters"></input>&nbsp;&nbsp;<span id="checkuser" style="float:right;"/></span></div></td></tr>
                        <tr><td>Person Name</td><td> <input class="inputClass" type="text" value="" name="name" ></input> </td></tr>
                        <tr><td>Gender</td><td> <input checked="true"  type="radio" value="male" name="gender" >Male<input type="radio" value="female" name="gender" ></input> Female</td></tr>
                        <tr><td>DOB</td><td> <input class="inputClass" type="text" value="" name="dob" ></input> </td></tr>
                        <tr><td>Address</td><td> <textarea type="text" name="address" ></textarea> </td></tr>
                        <tr><td>Mobile</td><td> <input class="inputClass" type="text" value="" name="mobile" ></input> </td></tr>
                        <tr><td>Email</td><td> <input class="inputClass" type="text" value="" name="email" ></input> </td></tr>
                        <tr><td>Emergency No</td><td> <input class="inputClass" type="text" value="" name="emerg" ></input> </td></tr>
                        <tr><td>Transaction id</td><td> <input class="inputClass" type="text" value="" name="transid" ></input> </td></tr>
                        <tr><td colspan="2" align="center"><input class="btn-submit"  type="submit" value="Save"></input>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="btn-submit" id="clear" type="reset" value="Clear"></input></tr>

                    </table>
                </form>
                </center><div>
                </div>

                </body>
                </html>
