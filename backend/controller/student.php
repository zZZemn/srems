<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


include('../query.php');
$query = new Query();


function fileUpload($files)
{
    $targetDir = "../../student-photos/";

    $today = date("Y-m-d H:i:s");
    $fileNameCode = 'SREMSSTUDENTPHOTO-' . preg_replace('/[^A-Za-z0-9\-]/', '', $today);

    $fileTmpPath = $files['studentImage']['tmp_name'];
    $fileName = $files['studentImage']['name'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    $newFileName = md5(time() . $fileNameCode) . '.' . $fileExtension;

    $allowedfileExtensions = array('jpg', 'jpeg', 'png');

    if (in_array($fileExtension, $allowedfileExtensions)) {
        $dest_path = $targetDir . $newFileName;
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            return json_encode(array('status' => 200, 'file_name' => $newFileName, 'message' => 'Success.'));
        } else {
            return json_encode(array('status' => 400, 'message' => 'There was an error moving the uploaded file.'));
        }
    } else {
        return json_encode(array('status' => 400, 'message' => 'Invalid file extension. Only jpg, jpeg, png, and gif are allowed.'));
    }
}


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

            if (isset($_FILES['studentImage']) && $_FILES['studentImage']['error'] === UPLOAD_ERR_OK) {

                $uploadFile = fileUpload($_FILES);
                $uploadResponse = json_decode($uploadFile, true);

                if ($uploadResponse['status'] == 200) {
                    $_POST['image_path'] = $uploadResponse['file_name'];
                    echo $query->addStudent($_POST);
                } else {
                    echo "File upload error: " . $uploadResponse['message'];
                }
            } else {
                echo $query->addStudent($_POST);
            }
        }
    } elseif ($reqType == 'ADDSTUDENTV2') {
        $code = $_POST['STUDENT_CODE'];

        $getStudent = $query->getInStudentListByCode($code);
        if ($getStudent->num_rows > 0) {

            $studentDetails = $getStudent->fetch_assoc();

            $checkCode = $query->getByField('students', 'STUDENT_CODE', $studentDetails['STUDENT_CODE']);
            $checkEmail = $query->getByField('students', 'EMAIL', $studentDetails['EMAIL']);
            $checkName = $query->getByField('students', 'NAME', $studentDetails['NAME']);
            $checkNo = $query->getByField('students', 'CONTACT_NO', $studentDetails['CONTACT_NO']);

            if ($checkCode->num_rows > 0) {
                echo 'CODE_EXIST';
            } elseif ($checkEmail->num_rows > 0) {
                echo 'EMAIL_EXIST';
            } elseif ($checkName->num_rows > 0) {
                echo 'NAME_EXIST';
            } elseif ($checkNo->num_rows > 0) {
                echo 'CONTACTNO_EXIST';
            } else {
                $studentData = [
                    'studentCode' => $studentDetails['STUDENT_CODE'],
                    'studentName' => $studentDetails['NAME'],
                    'studentEmail' => $studentDetails['EMAIL'],
                    'studentContactNo' => $studentDetails['CONTACT_NO'],
                    'studentYear' => $studentDetails['YEAR'],
                    'studentSection' => $studentDetails['SECTION'],
                ];

                echo $query->addStudentV2($studentData);
            }
        } else {
            echo 400;
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


            if (isset($_FILES['studentImage']) && $_FILES['studentImage']['error'] === UPLOAD_ERR_OK) {

                $uploadFile = fileUpload($_FILES);
                $uploadResponse = json_decode($uploadFile, true);

                if ($uploadResponse['status'] == 200) {
                    $_POST['image_path'] = $uploadResponse['file_name'];
                    echo $query->editStudent($_POST);
                } else {
                    echo "File upload error: " . $uploadResponse['message'];
                }
            } else {
                echo $query->editStudent($_POST);
            }
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

            $checkOverdue = $query->getByField('overdue_records', 'STUDENT_ID', $student['ID']);

            if ($checkOverdue->num_rows > 0) {
                $overDueDetails = $checkOverdue->fetch_assoc();
                if (isset($overDueDetails['DATE'])) {
                    $dueDate = new DateTime($overDueDetails['DATE']);
                    $currentDate = new DateTime();
                    $interval = $currentDate->diff($dueDate);

                    if ($interval->days < 7) {
                        echo 400;
                        exit;
                    }
                }
            }

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
