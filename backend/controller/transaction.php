<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);


include('../query.php');
$query = new Query();


function fileUpload($files)
{
    $targetDir = "../../returned-item-photos/";

    $today = date("Y-m-d H:i:s");
    $fileNameCode = 'SREMSRETURNEDITEMPHOTO-' . preg_replace('/[^A-Za-z0-9\-]/', '', $today);

    $fileTmpPath = $files['rtnItemImg']['tmp_name'];
    $fileName = $files['rtnItemImg']['name'];
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

    if ($reqType == 'INSERTNEWTRANSACTION') {
        $studCode = $_POST['STUDENT_CODE'];
        $dueDate = $_POST['DUE_DATE'];
        $itemsArray = json_decode($_POST['ITEMS'], true);
        $teacher = $_POST['TEACHER'];
        $venue = $_POST['VENUE'];
        $signature = $_POST['SIGNATURE'];

        $today = date("Y-m-d H:i:s");

        $transactionCode = 'SREMS-' . preg_replace('/[^A-Za-z0-9\-]/', '', $today);

        $userId = $_SESSION['id'];

        $student = $query->getStudentByCode($studCode);
        if ($student->num_rows > 0) {
            $studentDetails = $student->fetch_assoc();

            if ($studentDetails['STATUS'] != "ACTIVE") {
                echo 400;
                exit;
            }

            $studentId = $studentDetails['ID'];
        } else {
            echo 400;
            exit;
        }

        $qTransaction = $query->insertTransaction($transactionCode, $userId, $studentId, $today, $dueDate, $teacher, $venue, $signature);
        if ($qTransaction == 200) {
            foreach ($itemsArray as $item) {
                $id = $item['itemId'];
                $name = $item['itemName'];
                $avquantity = $item['itemQty'];
                $qty = $item['qty'];

                $query->insertTransactionDetails($transactionCode, $id, $qty);
            }

            echo 200;
        }
    } elseif ($reqType == 'RETURNTRANSCTION') {
        $id = $_POST['id'];

        if (isset($_POST['rtnRemarks'])) {
            if (isset($_FILES['rtnItemImg']) && $_FILES['rtnItemImg']['error'] === UPLOAD_ERR_OK) {

                $uploadFile = fileUpload($_FILES);
                $uploadResponse = json_decode($uploadFile, true);

                $img = $uploadResponse['file_name'];
                $remarks = $_POST['rtnRemarks'];

                $query->updateRemarksAndReturnedImage($id, $img, $remarks);

                if (isset($_POST['damage_qty'])) {
                    foreach ($_POST['damage_qty'] as $iid => $details) {
                        $itemName = $details['item_name'];
                        $qty = $details['qty'];

                        $query->updateTdDamagedQty($iid, $qty);
                    }
                }

                echo $query->changeStatus('transaction', $id, 'RETURNED');
                exit;
            } else {

                if (isset($_POST['damage_qty'])) {
                    foreach ($_POST['damage_qty'] as $iid => $details) {
                        $itemName = $details['item_name'];
                        $qty = $details['qty'];

                        $query->updateTdDamagedQty($iid, $qty);
                    }
                }

                echo $query->changeStatus('transaction', $id, 'RETURNED');
                exit;
            }
        } else {

            if (isset($_POST['damage_qty'])) {
                foreach ($_POST['damage_qty'] as $iid => $details) {
                    $itemName = $details['item_name'];
                    $qty = $details['qty'];

                    $query->updateTdDamagedQty($iid, $qty);
                }
            }

            echo $query->changeStatus('transaction', $id, 'RETURNED');
            exit;
        }
    } elseif ($reqType == "REPLACEITEMS") {
        echo $query->replaceItem($_POST);
    }
} elseif (isset($_GET['REQUEST_TYPE'])) {
    $reqType = $_GET['REQUEST_TYPE'];

    if ($reqType == 'GETTRANSACTIONS') {
        $search = $_GET['search'];
        $status = $_GET['status'];

        $result = $query->getTransctionsWSearch($status, $search);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
