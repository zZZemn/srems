<?php
// Database configuration
include("../db.php");

$db = new db_connect();

$host = $db->host;
$dbname = $db->name;
$username = $db->user;
$password = $db->pass;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);

    if (!is_array($data)) {
        throw new Exception("Invalid JSON data received.");
    }

    $stmt = $pdo->prepare("
        INSERT INTO `inventory`(`BARCODE`, `INV_CODE`, `ITEM_NAME`, `QTY`, `CATEGORY`, `IMG`, `STATUS`) 
        VALUES (:BARCODE, :INV_CODE, :ITEM_NAME, :QTY, :CATEGORY, :IMG, :STATUS)
    ");

    foreach ($data as $row) {
        $barCode = substr(md5(uniqid(rand(), true)), 0, 13);

        $stmt->execute([
            ':BARCODE' => $barCode,
            ':INV_CODE' => $row['INV_CODE'] ?? "",
            ':ITEM_NAME' => $row['ITEM_NAME'] ?? "",
            ':QTY' => $row['QTY'] ?? 0,
            ':CATEGORY' => $row['CATEGORY'] ?? "",
            ':IMG' => "default.jpg",
            ':STATUS' => "ACTIVE",
        ]);
    }

    echo json_encode(['message' => 'Data successfully inserted.']);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
