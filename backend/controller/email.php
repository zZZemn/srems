<?php
session_start();

include('../query.php');
$query = new Query();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require('../PHPMailer/src/PHPMailer.php');
require('../PHPMailer/src/SMTP.php');
require('../PHPMailer/src/Exception.php');


function sendEmail($email, $name, $message)
{
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'sremshmdept@gmail.com';
    $mail->Password = 'kcaq xzqu rqpj jmdb';
    $mail->Port = 465;
    $mail->SMTPSecure = 'ssl';

    $mail->setFrom('ugabane0516@gmail.com', 'SREMS');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'SREMS Notification!';
    $mail->Body = $message;

    if ($mail->send()) {
        echo 200;
    } else {
        echo 400;
    }
}


$messageBody = '';


if (isset($_POST['REQUEST_TYPE'])) {

    if ($_POST['REQUEST_TYPE'] == 'SENDEMAILBARROWED' && isset($_POST['items'], $_POST['name'], $_POST['dueDate'], $_POST['email'])) {

        $itemsArray = json_decode($_POST['items'], true);

        $itemsList = "<ul>";

        foreach ($itemsArray as $item) {
            $itemName = htmlspecialchars($item['itemName'], ENT_QUOTES, 'UTF-8');
            $itemQty = htmlspecialchars($item['itemQty'], ENT_QUOTES, 'UTF-8');
            $itemsList .= "<li>{$itemName}: {$itemQty}</li>";
        }

        $itemsList .= "</ul>";

        $dueDate = htmlspecialchars($_POST['dueDate'], ENT_QUOTES, 'UTF-8');

        $messageBody = "
        Dear " . htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8') . ",
        <br>
        <br>
        We are pleased to inform you that you have successfully borrowed items from the laboratory. Below is the list of items you have borrowed:
        <br>
        <br>
        " . $itemsList . "
        <br>
        <br>
        Please ensure the proper care and timely return of these items by the due date: <strong>{$dueDate}</strong>.
        <br>
        <br>
        If you have any questions or need further assistance, feel free to reach out to us.
        <br>
        Thank you for your cooperation.
        <br>
        <br>
        Best regards,
        <br>
        Srems HM Department";


        sendEmail($_POST['email'], $_POST['name'], $messageBody);
    } elseif ($_POST['REQUEST_TYPE'] == 'SENDEMAILRETURNED' && isset($_POST['name'], $_POST['dot'], $_POST['tId'], $_POST['email'])) {
        $transactionId = htmlspecialchars($_POST['tId'], ENT_QUOTES, 'UTF-8');
        $dot = htmlspecialchars($_POST['dot'], ENT_QUOTES, 'UTF-8');

        $messageBody = "
        Dear " . htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8') . ",
        <br>
        <br>
        We are pleased to inform you that you have successfully returned the items borrowed from the laboratory.
        <br>
        The date of this transaction is: <strong>{$dot}</strong>.
        <br>
        Your transaction code is: <strong>{$transactionId}</strong>.
        <br>
        <br>
        If you have any questions or need further assistance, feel free to reach out to us.
        <br>
        Thank you for your cooperation.
        <br>
        <br>
        Best regards,
        <br>
        Srems HM Department";

        sendEmail($_POST['email'], $_POST['name'], $messageBody);
    } elseif ($_POST['REQUEST_TYPE'] == 'SENDEMAILOVERDUE') {
        $getOD = $query->getTransctionsWSearch('OVERDUE', '');

        if ($getOD->num_rows > 0) {
            while ($od = $getOD->fetch_assoc()) {
                $getStudent = $query->getById('students', $od['STUDENT_ID']);

                if ($getStudent->num_rows > 0) {
                    $student = $getStudent->fetch_assoc();

                    $dueDate = $od['DUEDATE'];
                    $transactionDate = $od['DATE'];
                    $transactionCode = $od['TRANSACTION_CODE'];

                    $messageBody = "
                        Dear " . htmlspecialchars($student['NAME'], ENT_QUOTES, 'UTF-8') . ",
                        <br>
                        <br>
                        This is a reminder that you have overdue items borrowed from the laboratory.
                        <br>
                        The due date for these items was: <strong>{$dueDate}</strong>.
                        <br>
                        The date of your transaction was: <strong>{$transactionDate}</strong>.
                        <br>
                        Your transaction code is: <strong>{$transactionCode}</strong>.
                        <br>
                        <br>
                        Please return the items as soon as possible to avoid any penalties.
                        <br>
                        If you have any questions or need further assistance, feel free to reach out to us.
                        <br>
                        Thank you for your cooperation.
                        <br>
                        <br>
                        Best regards,
                        <br>
                        Srems HM Department";

                    sendEmail($student['EMAIL'], $student['NAME'], $messageBody);
                }
            }
        }
    }
}
