<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


include('../query.php');
$query = new Query();

if (isset($_POST['REQUEST_TYPE'])) {
    $reqType = $_POST['REQUEST_TYPE'];

    if ($reqType == "ADDVENUE") {
        echo $query->insertVenue($_POST);
    } elseif ($reqType == "DELETEVENUE") {
        echo $query->deleteUsingId("venues", $_POST['ID']);
    }
} elseif (isset($_GET['REQUEST_TYPE'])) {
    $reqType = $_GET['REQUEST_TYPE'];

    if ($reqType == "GETVENUES") {
        $result = $query->getAll("venues");

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
