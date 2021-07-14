<?php

define("hostname","http://localhost/Mobile_Healthcare/");

define("db_host", "localhost");
define("db_user", "root");
define("db_pass", "");
define("db_name", "mobile_health_care");
define("ta", "ta");
define("healthcare_location","12.929168480585224, 77.68737364263335");
define('MYSQL_BOTH',MYSQLI_BOTH);
define('MYSQL_NUM',MYSQLI_NUM);
define('MYSQL_ASSOC',MYSQLI_ASSOC);

$connection = mysqli_connect("localhost", "root", "", "mobile_health_care");
    if (!$connection) {
        die("Database connection failed: " . mysqli_connect_error());
    }


    // Selecting a database 

    mysqli_select_db($connection, "mobile_health_care");

?>