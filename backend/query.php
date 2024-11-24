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

    public function getByField($table, $field, $value)
    {
        $query = $this->conn->prepare("SELECT * FROM `$table` WHERE `$field` = '$value'");

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

    public function deleteUsingId($table, $id)
    {
        $table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
        $query = $this->conn->prepare("DELETE FROM `$table` WHERE ID = ?");

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
        if (isset($post['image_path'])) {
            $query = $this->conn->prepare("INSERT INTO `students` (`STUDENT_CODE`, `NAME`, `EMAIL`, `CONTACT_NO`, `YEAR`, `SECTION`, `IMG`, `DATE_ADDED`, `STATUS`) VALUES (?, ?, ?, ?, ?, ?, ?, CURDATE(),'ACTIVE')");
        } else {
            $query = $this->conn->prepare("INSERT INTO `students` (`STUDENT_CODE`, `NAME`, `EMAIL`, `CONTACT_NO`, `YEAR`, `SECTION`, `IMG`, `DATE_ADDED`,`STATUS`) VALUES (?, ?, ?, ?, ?, ?, 'default.png', CURDATE(),'ACTIVE')");
        }

        if ($query) {
            if (isset($post['image_path'])) {
                $query->bind_param("ssssiss", $post['studentCode'], $post['studentName'], $post['studentEmail'], $post['studentContactNo'], $post['studentYear'], $post['studentSection'], $post['image_path']);
            } else {
                $query->bind_param("ssssis", $post['studentCode'], $post['studentName'], $post['studentEmail'], $post['studentContactNo'], $post['studentYear'], $post['studentSection']);
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

    public function editStudent($post)
    {
        if (isset($post['image_path'])) {
            $query = $this->conn->prepare("UPDATE `students` SET `STUDENT_CODE` = ?, `NAME` = ?, `EMAIL` = ?, `CONTACT_NO` = ?, `YEAR` = ?, `SECTION` = ?, `IMG` = ? WHERE `ID` = ?");
        } else {
            $query = $this->conn->prepare("UPDATE `students` SET `STUDENT_CODE` = ?, `NAME` = ?, `EMAIL` = ?, `CONTACT_NO` = ?, `YEAR` = ?, `SECTION` = ? WHERE `ID` = ?");
        }

        if ($query) {
            if (isset($post['image_path'])) {
                $query->bind_param("ssssissi", $post['studentCode'], $post['studentName'], $post['studentEmail'], $post['studentContactNo'], $post['studentYear'], $post['studentSection'], $post['image_path'], $post['ID']);
            } else {
                $query->bind_param("ssssisi", $post['studentCode'], $post['studentName'], $post['studentEmail'], $post['studentContactNo'], $post['studentYear'], $post['studentSection'], $post['ID']);
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

    public function countStudentAddedThisMonth()
    {
        $query = $this->conn->prepare("
        SELECT COUNT(*) as student_count 
        FROM `students` 
        WHERE `STATUS` = 'ACTIVE' 
        AND MONTH(`DATE_ADDED`) = MONTH(CURDATE()) 
        AND YEAR(`DATE_ADDED`) = YEAR(CURDATE())
    ");

        $query->execute();

        $result = $query->get_result();
        $studentRes = $result->fetch_assoc();
        return $studentRes['student_count'];
    }


    public function getStudentCountsPerMonth()
    {
        $query = $this->conn->prepare("
        SELECT YEAR(DATE_ADDED) AS year, MONTH(DATE_ADDED) AS month, COUNT(*) AS student_count
        FROM students
        WHERE YEAR(DATE_ADDED) = YEAR(CURDATE())
        GROUP BY YEAR(DATE_ADDED), MONTH(DATE_ADDED)
        ORDER BY year, month
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
                $query->bind_param("ssiss", $post['inventoryCode'], $post['inventoryItem'], $post['inventoryQty'], $post['inventoryCategory'], $post['image_path']);
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
        $CONST_INACTIVE = "INACTIVE";

        $searchItem = '%' . $search . '%';

        if ($category == 'ALL') {
            $query = $this->conn->prepare("SELECT * FROM `inventory` WHERE `STATUS` = 'ACTIVE' AND (`ITEM_NAME` LIKE ? OR `INV_CODE` LIKE ?) ORDER BY `ID` ASC");
        } elseif ($category == 'Deleted') {
            $query = $this->conn->prepare("SELECT * FROM `inventory` WHERE `STATUS` = ? AND (`ITEM_NAME` LIKE ? OR `INV_CODE` LIKE ?) ORDER BY `ID` ASC");
        } else {
            $query = $this->conn->prepare("SELECT * FROM `inventory` WHERE `CATEGORY` = ? AND (`ITEM_NAME` LIKE ? OR `INV_CODE` LIKE ?) ORDER BY `ID` ASC");
        }

        if ($query) {
            if ($category == 'ALL') {
                $query->bind_param('ss', $searchItem, $searchItem);
            } elseif ($category == 'Deleted') {
                $query->bind_param('sss', $CONST_INACTIVE, $searchItem, $searchItem);
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
    public function insertTransaction($code, $uId, $sId, $date, $dueDate, $teacher, $venue)
    {
        $query = $this->conn->prepare("INSERT INTO `transaction`(`TRANSACTION_CODE`, `CUSTODIAN_ID`, `STUDENT_ID`, `DATE`, `DUEDATE`, `VENUE`, `TEACHER`,`SENT_EMAIL_BARROWED`, `SENT_EMAIL_RETURNED`, `SENT_EMAIL_OVERDUE`, `STATUS`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'BORROWED')");

        if ($query) {
            $sentEmailBorrowed = 1;
            $sentEmailReturned = 0;
            $sentEmailOverdue = 0;

            $query->bind_param('siissssiii', $code, $uId, $sId, $date, $dueDate, $venue, $teacher, $sentEmailBorrowed, $sentEmailReturned, $sentEmailOverdue);

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
            ORDER BY t.ID DESC
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
            AND t.STATUS != 'RETURNED'
            ORDER BY t.ID DESC
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
            ORDER BY t.ID DESC
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


    public function updateRemarksAndReturnedImage($id, $img, $remarks)
    {
        $query = $this->conn->prepare("UPDATE `transaction` SET `IMG`='?',`REMARKS`='?' WHERE `ID` = '$id'");

        if ($query) {
            $query->bind_param('ss', $img, $remarks);

            if ($query->execute()) {
                return 200;
            } else {
                die("Execution failed: " . $query->error);
            }
        } else {
            die("Preparation failed: " . $this->conn->error);
        }
    }

    public function countTransactionThisMonth()
    {
        $query = $this->conn->prepare("
        SELECT COUNT(*) as transaction_count 
        FROM `transaction` 
        WHERE MONTH(`DATE`) = MONTH(CURDATE()) 
        AND YEAR(`DATE`) = YEAR(CURDATE())
    ");

        $query->execute();

        $result = $query->get_result();
        $transactionRes = $result->fetch_assoc();
        return $transactionRes['transaction_count'];
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



    // Teachers
    public function insertTeachers($post)
    {
        $query = $this->conn->prepare("INSERT INTO `teachers`(`NAME`, `CONTACT_NO`) VALUES (?, ?)");

        if ($query) {
            $query->bind_param('ss', $post['name'], $post['contactNo']);

            if ($query->execute()) {
                return 200;
            } else {
                die("Execution failed: " . $query->error);
            }
        } else {
            die("Preparation failed: " . $this->conn->error);
        }
    }


    // Categories
    public function insertCategories($post)
    {
        $query = $this->conn->prepare("INSERT INTO `categories`(`NAME`) VALUES (?)");

        if ($query) {
            $query->bind_param('s', $post['name']);

            if ($query->execute()) {
                return 200;
            } else {
                die("Execution failed: " . $query->error);
            }
        } else {
            die("Preparation failed: " . $this->conn->error);
        }
    }
}
