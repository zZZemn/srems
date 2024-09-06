<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


include('../query.php');
$query = new Query();

if (isset($_POST['REQUEST_TYPE'])) {
    $reqType = $_POST['REQUEST_TYPE'];

    if ($reqType == 'ADDINVENTORY') {

        echo $query->addInventory($_POST);
    } elseif ($reqType == 'EDITINVENTORY') {

        echo $query->editInventory($_POST);
    } elseif ($reqType == 'DEACTIVATE') {
        $id = $_POST['ID'];
        $status = $_POST['STATUS'];

        if ($status == 'ACTIVE') {
            $newStatus = 'INACTIVE';
        } else {
            $newStatus = 'ACTIVE';
        }

        echo $query->deactivateInventory($newStatus, $id);
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
    } elseif ($reqType == 'GETINVENTORYLIST') {

        $getList = $query->getAll('inventory');
        $inventoryList = [];
        while ($inv = $getList->fetch_assoc()) {
            $barrowedQty = 0;
            $invId = $inv['ID'];
            $invQty = $inv['QTY'];

            $getTransactionDetailsUsingInvId = $query->getTransactionDetailsUsingInvId($invId);
            if ($getTransactionDetailsUsingInvId->num_rows > 0) {
                while ($transactionDetails = $getTransactionDetailsUsingInvId->fetch_assoc()) {
                    $barrowedQty += $transactionDetails['QTY'];
                }
            }

            $inventoryList[] = [
                'ID' => $invId,
                'INV_CODE' => $inv['INV_CODE'],
                'ITEM_NAME' => $inv['ITEM_NAME'],
                'QTY' => $invQty,
                'REMAINING_QTY' => $invQty - $barrowedQty,
                'CATEGORY' => $inv['CATEGORY'],
                'STATUS' => $inv['STATUS']
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($inventoryList);
    } elseif ($reqType == 'GETINVENTORY') {

        $search = $_GET['search'];
        $category = $_GET['category'];

        $result = $query->getInventoryWSearch($category, $search);

        $data = [];
        while ($inv = $result->fetch_assoc()) {
            $barrowedQty = 0;
            $invId = $inv['ID'];
            $invQty = $inv['QTY'];

            $getTransactionDetailsUsingInvId = $query->getTransactionDetailsUsingInvId($invId);
            if ($getTransactionDetailsUsingInvId->num_rows > 0) {
                while ($transactionDetails = $getTransactionDetailsUsingInvId->fetch_assoc()) {
                    $getTransactionDetails = $query->getTransactionUsingTransactionCode($transactionDetails['TRANS_CODE']);
                    if ($getTransactionDetails->num_rows > 0) {
                        $tDetails = $getTransactionDetails->fetch_assoc();

                        if ($tDetails['STATUS'] != 'RETURNED') {
                            $barrowedQty += $transactionDetails['QTY'];
                        }
                    }
                }
            }

            $data[] = [
                'ID' => $invId,
                'INV_CODE' => $inv['INV_CODE'],
                'ITEM_NAME' => $inv['ITEM_NAME'],
                'QTY' => $invQty,
                'REMAINING_QTY' => $invQty - $barrowedQty,
                'CATEGORY' => $inv['CATEGORY'],
                'STATUS' => $inv['STATUS']
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    } else {
        echo 400;
    }
}
