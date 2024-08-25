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
        $query = $this->conn->prepare("SELECT * FROM `$table`");
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
}
