<?php

include "backup.php";

$servername = "localhost";
$username = "root";
$password = "";
$db_name = "SREMS";

backDb($servername, $username, $password, $db_name);

echo "Completed!!";

echo "<script>window.close();</script>";
