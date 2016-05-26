<?php
if(!defined('IN_SCRIPT')) die("Oops! Nothing here");
//Send email confirmation link
    $mail = new PHPMailer;

    $mail->CharSet = 'UTF-8';                             // Unicode character encode
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'dang.viet.son.hp4@gmail.com';                 // SMTP username
    $mail->Password = 'haiphong@!#123';                           // SMTP password
    $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 587;                                    // TCP port to connect to

    $mail->setFrom('info@vieclambanthoigian.com.vn', 'Mailer');
    $mail->addAddress('dang.viet.son.hp@gmail.com', '');     // Add a recipient

    $mail->Subject = $email_subject;
    $mail->Body    = $email_body;


    if(!$mail->send()) {
        echo 'Message could not be sent.';
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    }