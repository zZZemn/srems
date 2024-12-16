<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


include('../query.php');
$query = new Query();

if (isset($_POST['REQUEST_TYPE'])) {
    $reqType = $_POST['REQUEST_TYPE'];

    if ($reqType == 'ADDSTUDENT') {

        $checkCode = $query->getByField('students_list', 'STUDENT_CODE', $_POST['studentCode']);
        $checkEmail = $query->getByField('students_list', 'EMAIL', $_POST['studentEmail']);
        $checkName = $query->getByField('students_list', 'NAME', $_POST['studentName']);
        $checkNo = $query->getByField('students_list', 'CONTACT_NO', $_POST['studentContactNo']);

        if ($checkCode->num_rows > 0) {
            echo 'CODE_EXIST';
        } elseif ($checkEmail->num_rows > 0) {
            echo 'EMAIL_EXIST';
        } elseif ($checkName->num_rows > 0) {
            echo 'NAME_EXIST';
        } elseif ($checkNo->num_rows > 0) {
            echo 'CONTACTNO_EXIST';
        } else {
            echo $query->addInStudentList($_POST);
        }
    } elseif ($reqType == 'EDITSTUDENT') {

        $getStudentInfo = $query->getById('students_list', $_POST['ID']);
        if ($getStudentInfo->num_rows > 0) {
            $student = $getStudentInfo->fetch_assoc();

            if ($student['STUDENT_CODE'] != $_POST['studentCode']) {
                $checkCode = $query->getByField('students_list', 'STUDENT_CODE', $_POST['studentCode']);

                if ($checkCode->num_rows > 0) {
                    echo 'CODE_EXIST';
                    exit;
                }
            }

            if ($student['EMAIL'] != $_POST['studentEmail']) {
                $checkEmail = $query->getByField('students_list', 'EMAIL', $_POST['studentEmail']);

                if ($checkEmail->num_rows > 0) {
                    echo 'EMAIL_EXIST';
                    exit;
                }
            }

            if ($student['NAME'] != $_POST['studentName']) {
                $checkName = $query->getByField('students_list', 'NAME', $_POST['studentName']);

                if ($checkName->num_rows > 0) {
                    echo 'NAME_EXIST';
                    exit;
                }
            }

            if ($student['CONTACT_NO'] != $_POST['studentContactNo']) {
                $checkNo = $query->getByField('students_list', 'CONTACT_NO', $_POST['studentContactNo']);

                if ($checkNo->num_rows > 0) {
                    echo 'CONTACTNO_EXIST';
                    exit;
                }
            }

            echo $query->editInStudentList($_POST);
        } else {
            echo '400';
            exit;
        }
    } elseif ($reqType == 'DELETE') {
        $id = $_POST['ID'];

        echo $query->deleteUsingId("students_list", $id);
    } else {
        echo 400;
    }
} elseif (isset($_GET['REQUEST_TYPE'])) {
    $reqType = $_GET['REQUEST_TYPE'];

    if ($reqType == 'GETSTUDENTUSINGCODE') {
        $getStudent = $query->getInStudentListByCode($_GET['STUDENT_CODE']);

        if ($getStudent->num_rows > 0) {
            $student = $getStudent->fetch_assoc();

            header('Content-Type: application/json');
            echo json_encode($student);
        } else {
            echo 400;
        }
    } elseif ($reqType == 'GETSTUDENTS') {
        $result = $query->getAll('students_list');

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
