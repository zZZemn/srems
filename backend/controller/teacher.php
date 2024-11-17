<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


include('../query.php');
$query = new Query();

if (isset($_POST['REQUEST_TYPE'])) {
    $reqType = $_POST['REQUEST_TYPE'];

    if ($reqType == "ADDTEACHER") {
        echo $query->insertTeachers($_POST);
    } elseif ($reqType == "DELETETEACHER") {
        echo $query->deleteUsingId("teachers", $_POST['ID']);
    }
} elseif (isset($_GET['REQUEST_TYPE'])) {
    $reqType = $_GET['REQUEST_TYPE'];

    if ($reqType == "GETTEACHERS") {
        $result = $query->getAll("teachers");

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
