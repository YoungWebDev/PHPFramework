<?php

namespace app\Model;


class Mail {

    public static function send($data)
    {

        $mail = new \PHPMailer;

        $mail->isSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->Host = '';
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = '';
        $mail->Password = '';
        $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
        $mail->Port = 465;                                    // TCP port to connect to

        $mail->From = '';
        $mail->FromName = '';
        $mail->addAddress($data['email'], $data['fullname']);

        $mail->isHTML(true);                                  // Set email format to HTML

        $mail->Subject =  $data['subject'];

        $mail->Body =  $data['templete'];



        //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

        if(!$mail->send()) {
            throw new \Exception('Mailer Error: ' . $mail->ErrorInfo);
        } else {
            return true;
        }
    }

}