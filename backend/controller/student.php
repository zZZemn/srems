<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


include('../query.php');
$query = new Query();

if (isset($_POST['REQUEST_TYPE'])) {
    $reqType = $_POST['REQUEST_TYPE'];

    if ($reqType == 'ADDSTUDENT') {

        $checkCode = $query->getByField('students', 'STUDENT_CODE', $_POST['studentCode']);
        $checkEmail = $query->getByField('students', 'EMAIL', $_POST['studentEmail']);
        $checkName = $query->getByField('students', 'NAME', $_POST['studentName']);
        $checkNo = $query->getByField('students', 'CONTACT_NO', $_POST['studentContactNo']);

        if ($checkCode->num_rows > 0) {
            echo 'CODE_EXIST';
        } elseif ($checkEmail->num_rows > 0) {
            echo 'EMAIL_EXIST';
        } elseif ($checkName->num_rows > 0) {
            echo 'NAME_EXIST';
        } elseif ($checkNo->num_rows > 0) {
            echo 'CONTACTNO_EXIST';
        } else {
            echo $query->addStudent($_POST);
        }
    } elseif ($reqType == 'EDITSTUDENT') {

        $getStudentInfo = $query->getById('students', $_POST['ID']);
        if ($getStudentInfo->num_rows > 0) {
            $student = $getStudentInfo->fetch_assoc();

            if ($student['STUDENT_CODE'] != $_POST['studentCode']) {
                $checkCode = $query->getByField('students', 'STUDENT_CODE', $_POST['studentCode']);

                if ($checkCode->num_rows > 0) {
                    echo 'CODE_EXIST';
                    exit;
                }
            }

            if ($student['EMAIL'] != $_POST['studentEmail']) {
                $checkEmail = $query->getByField('students', 'EMAIL', $_POST['studentEmail']);

                if ($checkEmail->num_rows > 0) {
                    echo 'EMAIL_EXIST';
                    exit;
                }
            }

            if ($student['NAME'] != $_POST['studentName']) {
                $checkName = $query->getByField('students', 'NAME', $_POST['studentName']);

                if ($checkName->num_rows > 0) {
                    echo 'NAME_EXIST';
                    exit;
                }
            }

            if ($student['CONTACT_NO'] != $_POST['studentContactNo']) {
                $checkNo = $query->getByField('students', 'CONTACT_NO', $_POST['studentContactNo']);

                if ($checkNo->num_rows > 0) {
                    echo 'CONTACTNO_EXIST';
                    exit;
                }
            }

            echo $query->editStudent($_POST);
        } else {
            echo '400';
            exit;
        }
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
