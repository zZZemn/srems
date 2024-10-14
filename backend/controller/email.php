<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require('../PHPMailer/src/PHPMailer.php');
require('../PHPMailer/src/SMTP.php');
require('../PHPMailer/src/Exception.php');


$messageBody = '';


if (isset($_POST['REQUEST_TYPE'])) {

    if ($_POST['REQUEST_TYPE'] == 'SENDEMAILBARROWED' && isset($_POST['items'], $_POST['name'], $_POST['dueDate'])) {
        // Decode the items JSON
        $itemsArray = json_decode($_POST['items'], true);

        // Initialize the items list
        $itemsList = "<ul>";

        // Loop through each item and build the items list
        foreach ($itemsArray as $item) {
            $itemName = htmlspecialchars($item['itemName'], ENT_QUOTES, 'UTF-8');
            $itemQty = htmlspecialchars($item['itemQty'], ENT_QUOTES, 'UTF-8');
            $itemsList .= "<li>{$itemName}: {$itemQty}</li>";
        }

        $itemsList .= "</ul>";

        // Get the due date from POST data and format it
        $dueDate = htmlspecialchars($_POST['dueDate'], ENT_QUOTES, 'UTF-8');

        // Create the email message body
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
    }





    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        $name = $_POST['name'];
        $message = $_POST['message'];

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
        $mail->Body = $messageBody;

        if ($mail->send()) {
            echo 200;
        } else {
            echo 400;
        }
    }
}
