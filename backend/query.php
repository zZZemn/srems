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


    // Inventory
    public function addInventory($post)
    {
        $query = $this->conn->prepare("INSERT INTO `inventory`(`INV_CODE`, `ITEM_NAME`, `QTY`, `CATEGORY`, `STATUS`) VALUES (?, ?, ?, ?, 'ACTIVE')");

        if ($query) {

            $query->bind_param("ssis", $post['inventoryCode'], $post['inventoryItem'], $post['inventoryQty'], $post['inventoryCategory']);

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
        $query = $this->conn->prepare("UPDATE `inventory` SET `INV_CODE`= ?, `ITEM_NAME`= ?, `QTY`= ?, `CATEGORY`= ? WHERE `ID` = ?");

        if ($query) {
            $query->bind_param("ssisi", $post['inventoryCode'], $post['inventoryItem'], $post['inventoryQty'], $post['inventoryCategory'], $post['ID']);

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


    // Transaction details
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
}
