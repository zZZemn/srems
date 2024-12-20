<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


include('../query.php');
$query = new Query();

function fileUpload($files)
{
    $targetDir = "../../items-photos/";

    $today = date("Y-m-d H:i:s");
    $fileNameCode = 'SREMSINVPHOTO-' . preg_replace('/[^A-Za-z0-9\-]/', '', $today);

    $fileTmpPath = $files['inventoryImage']['tmp_name'];
    $fileName = $files['inventoryImage']['name'];
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

    if ($reqType == 'ADDINVENTORY') {
        if (isset($_FILES['inventoryImage']) && $_FILES['inventoryImage']['error'] === UPLOAD_ERR_OK) {

            $uploadFile = fileUpload($_FILES);
            $uploadResponse = json_decode($uploadFile, true);

            if ($uploadResponse['status'] == 200) {
                $_POST['image_path'] = $uploadResponse['file_name'];
                echo $query->addInventory($_POST);
            } else {
                echo "File upload error: " . $uploadResponse['message'];
            }
        } else {
            echo $query->addInventory($_POST);
        }
    } elseif ($reqType == 'EDITINVENTORY') {
        if (isset($_FILES['inventoryImage']) && $_FILES['inventoryImage']['error'] === UPLOAD_ERR_OK) {

            $uploadFile = fileUpload($_FILES);
            $uploadResponse = json_decode($uploadFile, true);

            if ($uploadResponse['status'] == 200) {
                $_POST['image_path'] = $uploadResponse['file_name'];
                echo $query->editInventory($_POST);
            } else {
                echo "File upload error: " . $uploadResponse['message'];
            }
        } else {
            echo $query->editInventory($_POST);
        }
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
                'BARCODE' => $inv['BARCODE'],
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

            $damagedQty = 0;
            $replacedQty = 0;

            $getTransactionDetailsUsingInvId = $query->getTransactionDetailsUsingInvId($invId);
            if ($getTransactionDetailsUsingInvId->num_rows > 0) {
                while ($transactionDetails = $getTransactionDetailsUsingInvId->fetch_assoc()) {
                    $getTransactionDetails = $query->getTransactionUsingTransactionCode($transactionDetails['TRANS_CODE']);
                    if ($getTransactionDetails->num_rows > 0) {
                        $tDetails = $getTransactionDetails->fetch_assoc();

                        $getReplacedItemQty = $query->getReplacedItemQty($transactionDetails['ID']);
                        if($getReplacedItemQty->num_rows > 0)
                        {
                            $replacedDetails = $getReplacedItemQty->fetch_assoc();
                            $replacedQty += $replacedDetails['replaced_qty'];
                        }

                        if ($tDetails['STATUS'] != 'RETURNED') {
                            $barrowedQty += $transactionDetails['QTY'];
                        }

                        if ($transactionDetails['DAMAGED_QTY'] > 0) {
                            $damagedQty += $transactionDetails['DAMAGED_QTY'];
                        }
                    }
                }
            }

            $totalInvDeduction = $barrowedQty + $damagedQty;

            $data[] = [
                'ID' => $invId,
                'BARCODE' => $inv['BARCODE'],
                'INV_CODE' => $inv['INV_CODE'],
                'ITEM_NAME' => $inv['ITEM_NAME'],
                'QTY' => $invQty,
                'REPLACED_QTY' => $replacedQty,
                'REMAINING_QTY' => ($invQty + $replacedQty) - $totalInvDeduction,
                'CATEGORY' => $inv['CATEGORY'],
                'IMG' => $inv['IMG'],
                'STATUS' => $inv['STATUS']
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    } elseif ($reqType == "GETINVUSINGBARCODE") {
        $barCode = $_GET['BARCODE'];

        $result = $query->getInventoryUsingBarCode($barCode);

        if ($result->num_rows > 0) {
            $inv = $result->fetch_assoc();

            $barrowedQty = 0;
            $invId = $inv['ID'];
            $invQty = $inv['QTY'];

            $damagedQty = 0;
            $replacedQty = 0;

            $getTransactionDetailsUsingInvId = $query->getTransactionDetailsUsingInvId($invId);
            if ($getTransactionDetailsUsingInvId->num_rows > 0) {
                while ($transactionDetails = $getTransactionDetailsUsingInvId->fetch_assoc()) {
                    $getTransactionDetails = $query->getTransactionUsingTransactionCode($transactionDetails['TRANS_CODE']);
                    if ($getTransactionDetails->num_rows > 0) {
                        $tDetails = $getTransactionDetails->fetch_assoc();

                        $getReplacedItemQty = $query->getReplacedItemQty($tDetails['ID']);
                        if($getReplacedItemQty->num_rows > 0)
                        {
                            $replacedDetails = $getReplacedItemQty->fetch_assoc();
                            $replacedQty += $replacedDetails['replaced_qty'];
                        }

                        if ($tDetails['STATUS'] != 'RETURNED') {
                            $barrowedQty += $transactionDetails['QTY'];
                        }

                        if ($transactionDetails['DAMAGED_QTY'] > 0) {
                            $damagedQty += $transactionDetails['DAMAGED_QTY'];
                        }
                    }
                }
            }

            

            $totalInvDeduction = $barrowedQty + $damagedQty;

            $data = [
                'ID' => $invId,
                'BARCODE' => $inv['BARCODE'],
                'INV_CODE' => $inv['INV_CODE'],
                'ITEM_NAME' => $inv['ITEM_NAME'],
                'QTY' => $invQty,
                'REPLACED_QTY' => $replacedQty,
                'REMAINING_QTY' => ($invQty + $replacedQty) - $totalInvDeduction,
                'CATEGORY' => $inv['CATEGORY'],
                'IMG' => $inv['IMG'],
                'STATUS' => $inv['STATUS']
            ];

            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            echo 400;
        }
    } else {
        echo 400;
    }
}
