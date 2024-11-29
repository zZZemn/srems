<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


function BackToIndex()
{
    header("Location: ../index.php");
    exit;
}

session_start();

if (!$_SESSION['id']) {
    BackToIndex();
} else {

    include("../backend/query.php");
    $query = new Query();

    $login_id = $_SESSION['id'];

    $account = $query->getById('account', $login_id);

    if ($account->num_rows > 0) {
        $userInfo = $account->fetch_assoc();
    } else {
        BackToIndex();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../node_modules/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.1/papaparse.min.js"></script>
    <title>SREMS</title>
</head>

<body>

    <style>
        nav {
            height: 50px;
        }

        nav a {
            text-decoration: none;
            color: white;
            font-size: 14px;
            margin: 0px 10px;
        }

        .side-bar {
            width: 250px;
            height: calc(100vh - 50px);
            box-shadow: 2px 2px 2px rgba(128, 128, 128, 0.405);
            transition: 0.5s;
        }

        .side-bar a {
            color: black;
            text-decoration: none;
            font-size: 1rem;
            margin: 3px;
            height: 50px;
            display: flex;
            align-items: center;
            padding-left: 15px;
            transition: 0.2s;
            border-radius: 5px;
        }

        .side-bar a:hover {
            color: white;
            background-color: var(--bs-dark);
        }

        .side-bar a i {
            margin-right: 10px;
            font-size: 20px;
        }

        .main-container {
            position: fixed;
            top: 50px;
            left: 250px;
            width: calc(100% - 250px);
            height: calc(100vh - 50px);
            overflow-y: auto;
            transition: 0.5s;
        }


        /* ---------- */

        .nav-dropdown {
            position: relative;
        }

        .nav-profile-button {
            border-radius: 50%;
            border: none;
            background-color: rgba(255, 255, 255, 0);
            color: white;
            font-size: 30px;
        }

        .nav-dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            z-index: 1000;
            background-color: rgb(233, 233, 233);
            color: black;
            list-style: none;
            padding: 10px;
            font-size: 15px;
            border-radius: 5px;

            display: none;
        }
    </style>

    <div
        id="AlertComponent"
        class="alert"
        style="position: fixed; top: 10px; left: 50%; transform: translateX(-50%); z-index: 100000; pointer-events: none">
        <span id="AlertMessage"></span>
    </div>

    <nav class="d-flex justify-content-between align-items-center px-3 py-2 bg-dark  text-light">
        <div class="d-flex align-items-center">
            <button class="btn p-0 text-light me-2" id="btn-toggle-side-bar" style="font-size: 30px;"><i class="bi bi-list"></i></button>
            <a href="Dashboard.php">
                <h5 class="m-0">SREMS</h5>
            </a>
        </div>

        <div class="nav-dropdown d-flex align-items-center">
            <span class="p-0 fw-semibold me-2" style="font-size: 12px;">Hello, <?= ucwords(strtolower($userInfo['USERNAME'])) ?></span>

            <button class="nav-profile-button" id="navDropdownMenuButton" type="button"><i class="bi bi-person-circle"></i></button>
            <ul class="nav-dropdown-menu" id="navDropdownMenu">
                <li><a class="dropdown-item" href="Settings.php" id="settings">Settings</a></li>
                <li><a class="dropdown-item" href="../backend/controller/logout.php" id="logout">Logout</a></li>
            </ul>
        </div>


    </nav>

    <aside class="side-bar d-flex flex-column" id="side-bar">
        <a href="Dashboard.php"><i class="bi bi-bar-chart"></i> Dashboard</a>
        <a href="Transaction.php"><i class="bi bi-person-up"></i> Transaction</a>
        <a href="Students.php"><i class="bi bi-person-badge"></i> Students</a>
        <a href="Inventory.php"><i class="bi bi-hourglass"></i> Inventory</a>
        <a href="Backup.php"><i class="bi bi-database-gear"></i> Back up</a>
    </aside>

    <div class="main-container container-fluid pt-2" id="main-container">