<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require('../PHPMailer/src/PHPMailer.php');
require('../PHPMailer/src/SMTP.php');
require('../PHPMailer/src/Exception.php');


if (isset($_POST['email'], $_POST['name'], $_POST['message'])) {
    $email = $_POST['email'];
    $name = $_POST['name'];
    $message = $_POST['message'];

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'ugabane0516@gmail.com';
    $mail->Password = 'owwj dmzb hypq lsfu';
    $mail->Port = 465;
    $mail->SMTPSecure = 'ssl';

    $mail->setFrom('ugabane0516@gmail.com', 'SCREMS');
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = 'SCREMS Notification!';
    $mail->Body = '<div>
                    Hello ' . $name . '!
                    <br>' . 
                    $message . '
                   </div>';

    if ($mail->send()) {
        echo 200;
    } else {
        echo 400;
    }
}
