<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


include('../query.php');
$query = new Query();

if (isset($_POST['REQUEST_TYPE'])) {
    $reqType = $_POST['REQUEST_TYPE'];
} elseif (isset($_GET['REQUEST_TYPE'])) {
    $reqType = $_GET['REQUEST_TYPE'];

    if($reqType == 'GET') {
        
    }
} 
