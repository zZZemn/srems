<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


include('../query.php');
$query = new Query();

if (isset($_POST['REQUEST_TYPE'])) {
    $reqType = $_POST['REQUEST_TYPE'];

    if ($reqType == 'ADDSTUDENT') {
        echo $query->addStudent($_POST);
    } elseif ($reqType == 'EDITSTUDENT') {
        echo $query->editStudent($_POST);
    } elseif ($reqType == 'DEACTIVATE') {
        $id = $_POST['ID'];
        $status = $_POST['STATUS'];

        if ($status == 'ACTIVE') {
            $newStatus = 'INACTIVE';
        } else {
            $newStatus = 'ACTIVE';
        }

        echo $query->deactivateStudent($newStatus, $id);
    } else {
        echo 400;
    }
} elseif (isset($_GET['REQUEST_TYPE'])) {
    $reqType = $_GET['REQUEST_TYPE'];

    if ($reqType == 'GETSTUDENTUSINGCODE') {
        $getStudent = $query->getStudentByCode($_GET['STUDENT_CODE']);

        if ($getStudent->num_rows > 0) {
            $student = $getStudent->fetch_assoc();

            header('Content-Type: application/json');
            echo json_encode($student);
        } else {
            echo 400;
        }
    } elseif ($reqType == 'GETSTUDENTS') {
        $search = $_GET['search'];
        $status = $_GET['status'];

        $result = $query->getStudentsWSearch($status, $search);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
