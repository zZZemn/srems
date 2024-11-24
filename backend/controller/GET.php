<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


include('../query.php');
$query = new Query();

if (isset($_GET['REQUEST_TYPE'])) {
    $reqType = $_GET['REQUEST_TYPE'];

    if ($reqType == "GETLIST" && isset($_GET['table'])) {

        $tableList = $query->getAll($_GET['table']);

        $data = [];
        while ($row = $tableList->fetch_assoc()) {
            $data[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    } elseif ($reqType == "GETBIDASHBOARDDATA") {
        $studentData = [];
        $biData = [];

        $getData = $query->getBINumbersPerMonth();
        $biLabels = [];
        $biNumbers = [];

        foreach ($getData as $row) {
            $month = $row['month'];
            $transaction_count = $row['transaction_count'];

            $dateObj = DateTime::createFromFormat('!m', $month);
            $monthName = $dateObj->format('F');

            $biLabels[] = $monthName;
            $biNumbers[] = $transaction_count;
        }

        $biData = [
            "labels" => $biLabels,
            "numbers" => $biNumbers
        ];

        // Get Student counts per month
        $getStudentData = $query->getStudentCountsPerMonth();
        $studentLabels = [];
        $studentNumbers = [];

        foreach ($getStudentData as $row) {
            $month = $row['month'];
            $transaction_count = $row['student_count'];

            $dateObj = DateTime::createFromFormat('!m', $month);
            $monthName = $dateObj->format('F');

            $studentLabels[] = $monthName;
            $studentNumbers[] = $transaction_count;
        }

        $studentData = [
            "labels" => $studentLabels,
            "numbers" => $studentNumbers
        ];

        $data = [
            "bi" => $biData,
            "student" => $studentData
        ];

        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
