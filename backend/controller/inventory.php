<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


include('../query.php');
$query = new Query();

if (isset($_POST['REQUEST_TYPE'])) {
    $reqType = $_POST['REQUEST_TYPE'];

    if ($reqType == 'ADDINVENTORY') {

        echo $query->addInventory($_POST);
    } else {
        echo 400;
    }
} elseif (isset($_GET['REQUEST_TYPE'])) {
    $reqType = $_GET['REQUEST_TYPE'];

    if ($reqType == 'GETBARROWEDQTY') {
        $invId = $_GET['ID'];
        $totalQtyBarrowed = 0;

        $getTransactionDetails = $query->getTransactionDetailsUsingInvId($invId);
        while ($transaction = $getTransactionDetails->fetch_assoc()) {
            $totalQtyBarrowed += $transaction['QTY'];
        }

        echo $totalQtyBarrowed;
    } else {
        echo 400;
    }
}
