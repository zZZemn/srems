<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


include('../query.php');
$query = new Query();

if (isset($_POST['REQUEST_TYPE'])) {
    $reqType = $_POST['REQUEST_TYPE'];

    if ($reqType == "ADDCATEGORY") {
        echo $query->insertCategories($_POST);
    } elseif ($reqType == "DELETECATEGORY") {
        echo $query->deleteUsingId("categories", $_POST['ID']);
    }
} elseif (isset($_GET['REQUEST_TYPE'])) {
    $reqType = $_GET['REQUEST_TYPE'];

    if ($reqType == "GETCATEGORIES") {
        $result = $query->getAll("categories");

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
