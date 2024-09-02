<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);


include('../query.php');
$query = new Query();

if (isset($_POST['REQUEST_TYPE'])) {
    $reqType = $_POST['REQUEST_TYPE'];

    if ($reqType == 'INSERTNEWTRANSACTION') {
        $studCode = $_POST['STUDENT_CODE'];
        $dueDate = $_POST['DUE_DATE'];
        $itemsArray = json_decode($_POST['ITEMS'], true);

        $today = date("Y-m-d H:i:s");

        $transactionCode = 'SREMS-' . preg_replace('/[^A-Za-z0-9\-]/', '', $today);

        $userId = $_SESSION['id'];

        $student = $query->getStudentByCode($studCode);
        if ($student->num_rows > 0) {
            $studentDetails = $student->fetch_assoc();
            $studentId = $studentDetails['ID'];
        } else {
            echo 400;
            exit;
        }

        $qTransaction = $query->insertTransaction($transactionCode, $userId, $studentId, $today, $dueDate);
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
        echo $query->changeStatus('transaction', $id, 'RETURNED');
    }
} elseif (isset($_GET['REQUEST_TYPE'])) {
    $reqType = $_GET['REQUEST_TYPE'];

    if ($reqType == 'GET') {
    }
}
