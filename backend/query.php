<?php
include('db.php');
date_default_timezone_set('Asia/Manila');

class Query extends db_connect
{
    public function __construct()
    {
        $this->connect();
    }


    // Reusable
    public function getAll($table)
    {
        $query = $this->conn->prepare("SELECT * FROM `$table` ORDER BY `ID` ASC");
        if ($query->execute()) {
            $result = $query->get_result();
            return $result;
        } else {
            die("Execution failed: " . $query->error);
        }
    }


    public function getById($table, $id)
    {
        $query = $this->conn->prepare("SELECT * FROM `$table` WHERE `ID` = ?");

        if ($query) {

            $query->bind_param('i', $id);

            if ($query->execute()) {
                $result = $query->get_result();
                return $result;
            } else {
                die("Execution failed: " . $query->error);
            }
        } else {
            die("Preparation failed: " . $this->conn->error);
        }
    }

    public function changeStatus($table, $id, $value)
    {
        $query = $this->conn->prepare("UPDATE `$table` SET `STATUS`='$value' WHERE `ID` = ?");

        if ($query) {
            $query->bind_param("i", $id);

            if ($query->execute()) {
                return 200;
            } else {
                die("Execution failed: " . $query->error);
            }
        } else {
            die("Preparation failed: " . $this->conn->error);
        }
    }



    // --

    public function login($username)
    {
        $query = $this->conn->prepare("SELECT * FROM `account` WHERE `USERNAME` = ?");

        if ($query) {

            $query->bind_param('s', $username);

            if ($query->execute()) {
                $result = $query->get_result();
                return $result;
            } else {
                die("Execution failed: " . $query->error);
            }
        } else {
            die("Preparation failed: " . $this->conn->error);
        }
    }


    // Students
    public function addStudent($post)
    {
        $query = $this->conn->prepare("INSERT INTO `students` (`STUDENT_CODE`, `NAME`, `EMAIL`, `CONTACT_NO`, `STATUS`) VALUES (?, ?, ?, ?, 'ACTIVE')");

        if ($query) {

            $query->bind_param("ssss", $post['studentCode'], $post['studentName'], $post['studentEmail'], $post['studentContactNo']);

            if ($query->execute()) {
                return 200;
            } else {
                die("Execution failed: " . $query->error);
            }
        } else {
            die("Preparation failed: " . $this->conn->error);
        }
    }

    public function editStudent($post)
    {
        $query = $this->conn->prepare("UPDATE `students` SET `STUDENT_CODE` = ?, `NAME` = ?, `EMAIL` = ?, `CONTACT_NO` = ? WHERE `ID` = ?");

        if ($query) {
            $query->bind_param("ssssi", $post['studentCode'], $post['studentName'], $post['studentEmail'], $post['studentContactNo'], $post['ID']);

            if ($query->execute()) {
                return 200;
            } else {
                die("Execution failed: " . $query->error);
            }
        } else {
            die("Preparation failed: " . $this->conn->error);
        }
    }

    public function deactivateStudent($status, $id)
    {
        $query = $this->conn->prepare("UPDATE `students` SET `STATUS` = ? WHERE `ID` = ?");

        if ($query) {
            $query->bind_param("si", $status, $id);

            if ($query->execute()) {
                return 200;
            } else {
                die("Execution failed: " . $query->error);
            }
        } else {
            die("Preparation failed: " . $this->conn->error);
        }
    }

    public function getStudentByCode($code)
    {
        $query = $this->conn->prepare("SELECT * FROM `students` WHERE `STUDENT_CODE` = ?");

        if ($query) {

            $query->bind_param('s', $code);

            if ($query->execute()) {
                $result = $query->get_result();
                return $result;
            } else {
                die("Execution failed: " . $query->error);
            }
        } else {
            die("Preparation failed: " . $this->conn->error);
        }
    }


    public function getStudentsWSearch($status, $search)
    {
        $searchItem = '%' . $search . '%';

        if ($status == 'ALL') {
            $query = $this->conn->prepare("SELECT * FROM `students` WHERE `STUDENT_CODE` LIKE ? OR `NAME` LIKE ? OR `EMAIL` LIKE ? OR `CONTACT_NO` LIKE ? ORDER BY `NAME` ASC");
        } else {
            $query = $this->conn->prepare("SELECT * FROM `students` WHERE `STATUS` = ? AND (`STUDENT_CODE` LIKE ? OR `NAME` LIKE ? OR `EMAIL` LIKE ? OR `CONTACT_NO` LIKE ?) ORDER BY `NAME` ASC");
        }

        if ($query) {
            if ($status == 'ALL') {
                $query->bind_param('ssss', $searchItem, $searchItem, $searchItem, $searchItem);
            } else {
                $query->bind_param('sssss', $status, $searchItem, $searchItem, $searchItem, $searchItem);
            }

            if ($query->execute()) {
                $result = $query->get_result();
                return $result;
            } else {
                die("Execution failed: " . $query->error);
            }
        } else {
            die("Preparation failed: " . $this->conn->error);
        }
    }

    public function countStudent()
    {
        $query = $this->conn->prepare("SELECT COUNT(*) as student_count FROM `students` WHERE STATUS = 'ACTIVE'");

        $query->execute();

        $result = $query->get_result();
        $studentRes = $result->fetch_assoc();
        return $studentRes['student_count'];
    }



    // Inventory
    public function addInventory($post)
    {
        if (isset($post['image_path'])) {
            $query = $this->conn->prepare("INSERT INTO `inventory`(`INV_CODE`, `ITEM_NAME`, `QTY`, `CATEGORY`, `IMG`, `STATUS`) VALUES (?, ?, ?, ?, ?, 'ACTIVE')");
        } else {
            $query = $this->conn->prepare("INSERT INTO `inventory`(`INV_CODE`, `ITEM_NAME`, `QTY`, `CATEGORY`, `IMG`,`STATUS`) VALUES (?, ?, ?, ?, 'default.jpg','ACTIVE')");
        }

        if ($query) {
            if (isset($post['image_path'])) {
                $query->bind_param("ssiss", $post['inventoryCode'], $post['inventoryItem'], $post['inventoryQty'], $post['image_path'], $post['inventoryCategory']);
            } else {
                $query->bind_param("ssis", $post['inventoryCode'], $post['inventoryItem'], $post['inventoryQty'], $post['inventoryCategory']);
            }

            if ($query->execute()) {
                return 200;
            } else {
                die("Execution failed: " . $query->error);
            }
        } else {
            die("Preparation failed: " . $this->conn->error);
        }
    }

    public function editInventory($post)
    {
        if (isset($post['image_path'])) {
            $query = $this->conn->prepare("UPDATE `inventory` SET `INV_CODE`= ?, `ITEM_NAME`= ?, `QTY`= ?, `CATEGORY`= ?, `IMG` = ? WHERE `ID` = ?");
        } else {
            $query = $this->conn->prepare("UPDATE `inventory` SET `INV_CODE`= ?, `ITEM_NAME`= ?, `QTY`= ?, `CATEGORY`= ? WHERE `ID` = ?");
        }

        if ($query) {
            if (isset($post['image_path'])) {
                $query->bind_param("ssissi", $post['inventoryCode'], $post['inventoryItem'], $post['inventoryQty'], $post['inventoryCategory'], $post['image_path'], $post['ID']);
            } else {
                $query->bind_param("ssisi", $post['inventoryCode'], $post['inventoryItem'], $post['inventoryQty'], $post['inventoryCategory'], $post['ID']);
            }

            if ($query->execute()) {
                return 200;
            } else {
                die("Execution failed: " . $query->error);
            }
        } else {
            die("Preparation failed: " . $this->conn->error);
        }
    }

    public function deactivateInventory($status, $id)
    {
        $query = $this->conn->prepare("UPDATE `inventory` SET `STATUS` = ? WHERE `ID` = ?");

        if ($query) {
            $query->bind_param("si", $status, $id);

            if ($query->execute()) {
                return 200;
            } else {
                die("Execution failed: " . $query->error);
            }
        } else {
            die("Preparation failed: " . $this->conn->error);
        }
    }


    public function getInventoryWSearch($category, $search)
    {
        $searchItem = '%' . $search . '%';

        if ($category == 'ALL') {
            $query = $this->conn->prepare("SELECT * FROM `inventory` WHERE `ITEM_NAME` LIKE ? OR `INV_CODE` LIKE ? ORDER BY `ITEM_NAME` ASC");
        } else {
            $query = $this->conn->prepare("SELECT * FROM `inventory` WHERE `CATEGORY` = ? AND (`ITEM_NAME` LIKE ? OR `INV_CODE` LIKE ?) ORDER BY `ITEM_NAME` ASC");
        }

        if ($query) {
            if ($category == 'ALL') {
                $query->bind_param('ss', $searchItem, $searchItem);
            } else {
                $query->bind_param('sss', $category, $searchItem, $searchItem);
            }

            if ($query->execute()) {
                $result = $query->get_result();
                return $result;
            } else {
                die("Execution failed: " . $query->error);
            }
        } else {
            die("Preparation failed: " . $this->conn->error);
        }
    }

    public function countInventory()
    {
        $query = $this->conn->prepare("SELECT COUNT(*) as inv_count FROM `inventory` WHERE STATUS = 'ACTIVE'");

        $query->execute();

        $result = $query->get_result();
        $invRes = $result->fetch_assoc();
        return $invRes['inv_count'];
    }


    // Transaction
    public function insertTransaction($code, $uId, $sId, $date, $dueDate)
    {
        $query = $this->conn->prepare("INSERT INTO `transaction`(`TRANSACTION_CODE`, `CUSTODIAN_ID`, `STUDENT_ID`, `DATE`, `DUEDATE`, `STATUS`) VALUES (?, ?, ?, '$date', '$dueDate', 'BARROWED')");

        if ($query) {
            $query->bind_param('sii', $code, $uId, $sId);

            if ($query->execute()) {
                return 200;
            } else {
                die("Execution failed: " . $query->error);
            }
        } else {
            die("Preparation failed: " . $this->conn->error);
        }
    }

    public function getTransactionUsingTransactionCode($tCode)
    {
        $query = $this->conn->prepare("SELECT * FROM `transaction` WHERE `TRANSACTION_CODE` = ?");

        if ($query) {

            $query->bind_param('s', $tCode);

            if ($query->execute()) {
                $result = $query->get_result();
                return $result;
            } else {
                die("Execution failed: " . $query->error);
            }
        } else {
            die("Preparation failed: " . $this->conn->error);
        }
    }


    public function getTransctionsWSearch($status, $search)
    {
        $searchItem = '%' . $search . '%';

        if ($status == 'ALL') {
            $query = $this->conn->prepare("
            SELECT t.*, s.NAME, acc.USERNAME
            FROM `transaction` AS t 
            JOIN `students` AS s ON t.STUDENT_ID = s.ID 
            JOIN `account` AS acc ON t.CUSTODIAN_ID = acc.ID 
            WHERE t.TRANSACTION_CODE LIKE ? 
            OR s.student_code LIKE ? 
            OR s.NAME LIKE ?
        ");
        } elseif ($status == 'OVERDUE') {
            $query = $this->conn->prepare("
            SELECT t.*, s.NAME, acc.USERNAME
            FROM `transaction` AS t 
            JOIN `students` AS s ON t.STUDENT_ID = s.ID 
            JOIN `account` AS acc ON t.CUSTODIAN_ID = acc.ID 
            WHERE (t.TRANSACTION_CODE LIKE ? 
            OR s.student_code LIKE ? 
            OR s.NAME LIKE ?)
            AND t.DUEDATE < CURDATE()
        ");
        } else {
            $query = $this->conn->prepare("
            SELECT t.*, s.NAME, acc.USERNAME 
            FROM `transaction` AS t 
            JOIN `students` AS s ON t.STUDENT_ID = s.ID 
            JOIN `account` AS acc ON t.CUSTODIAN_ID = acc.ID 
            WHERE (t.TRANSACTION_CODE LIKE ? 
            OR s.student_code LIKE ? 
            OR s.NAME LIKE ?)
            AND t.STATUS = ?
        ");
        }

        if ($query) {
            if ($status == 'ALL' || $status == 'OVERDUE') {
                $query->bind_param('sss', $searchItem, $searchItem, $searchItem);
            } else {
                $query->bind_param('ssss', $searchItem, $searchItem, $searchItem, $status);
            }

            if ($query->execute()) {
                $result = $query->get_result();
                return $result;
            } else {
                die("Execution failed: " . $query->error);
            }
        } else {
            die("Preparation failed: " . $this->conn->error);
        }
    }

    public function getBINumbersPerMonth()
    {
        $query = $this->conn->prepare("
        SELECT YEAR(`DATE`) AS year, MONTH(`DATE`) AS month, COUNT(*) AS transaction_count
        FROM `transaction`
        WHERE YEAR(`DATE`) = YEAR(CURDATE()) -- Filter to get current year data
        GROUP BY MONTH(`DATE`)
        ORDER BY month
        ");

        if ($query) {
            if ($query->execute()) {
                $result = $query->get_result();
                return $result;
            } else {
                die("Execution failed: " . $query->error);
            }
        } else {
            die("Preparation failed: " . $this->conn->error);
        }
    }



    // Transaction details

    public function getTransactionDetailsUsingTransactionCode($tCode)
    {
        $query = $this->conn->prepare("SELECT * FROM `transaction_details` WHERE `TRANS_CODE` = ?");

        if ($query) {

            $query->bind_param('s', $tCode);

            if ($query->execute()) {
                $result = $query->get_result();
                return $result;
            } else {
                die("Execution failed: " . $query->error);
            }
        } else {
            die("Preparation failed: " . $this->conn->error);
        }
    }

    public function getTransactionDetailsUsingInvId($invId)
    {
        $query = $this->conn->prepare("SELECT * FROM `transaction_details` WHERE `INV_ID` = ?");

        if ($query) {

            $query->bind_param('i', $invId);

            if ($query->execute()) {
                $result = $query->get_result();
                return $result;
            } else {
                die("Execution failed: " . $query->error);
            }
        } else {
            die("Preparation failed: " . $this->conn->error);
        }
    }

    public function insertTransactionDetails($tId, $invId, $qty)
    {
        $query = $this->conn->prepare("INSERT INTO `transaction_details`(`TRANS_CODE`, `INV_ID`, `QTY`) VALUES (?, ?, ?)");

        if ($query) {
            $query->bind_param('sii', $tId, $invId, $qty);

            if ($query->execute()) {
                return 200;
            } else {
                die("Execution failed: " . $query->error);
            }
        } else {
            die("Preparation failed: " . $this->conn->error);
        }
    }

    public function getTransactionDetailsUsingStudentId($sId)
    {
        $query = $this->conn->prepare("SELECT td.*, t.ID as tId, t.DATE FROM `transaction_details` AS td 
        JOIN `transaction` AS t ON td.TRANS_CODE = t.TRANSACTION_CODE 
        JOIN `students` AS s ON t.STUDENT_ID = s.ID 
        WHERE s.ID = ?");

        if ($query) {
            $query->bind_param('i', $sId);

            if ($query->execute()) {
                $result = $query->get_result();
                return $result;
            } else {
                die("Execution failed: " . $query->error);
            }
        } else {
            die("Preparation failed: " . $this->conn->error);
        }
    }
}
