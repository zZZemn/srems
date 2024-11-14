<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


include('../query.php');
$query = new Query();

if (isset($_POST['REQUEST_TYPE'])) {
    $reqType = $_POST['REQUEST_TYPE'];

    if ($reqType == 'LOGIN') {

        if (isset($_POST['username'], $_POST['password'])) {

            $username = $_POST['username'];
            $password = $_POST['password'];

            $login = $query->login($username);

            if ($login->num_rows > 0) {

                $loginResult = $login->fetch_assoc();
                $loginPassword = $loginResult['PASSWORD'];
                if (password_verify($password, $loginPassword)) {

                    session_start();
                    $_SESSION['id'] = $loginResult['ID'];
                    echo 200;
                } else {
                    echo 400;
                }
            } else {
                echo 400;
            }
        } else {
            echo 400;
        }
    } else {
        echo 400;
    }
}
