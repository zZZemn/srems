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
        $labels = [];
        $numbers = [];

        $getData = $query->getBINumbersPerMonth();

        foreach ($getData as $row) {
            $month = $row['month'];
            $transaction_count = $row['transaction_count'];

            $dateObj   = DateTime::createFromFormat('!m', $month);
            $monthName = $dateObj->format('F');

            $labels[] = $monthName;
            $numbers[] = $transaction_count;
        }

        $data = [
            "labels" => $labels,
            "numbers" => $numbers
        ];

        header('Content-Type: application/json');
        echo json_encode($data);
    }
}
