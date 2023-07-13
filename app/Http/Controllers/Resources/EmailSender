<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailSender
{
    /**
     * Send email using PHPMailer library
     *
     * @param Request $request The HTTP request containing form data
     *
     * @throws Exception
     */
    static function sendEmail($email, $invitedBy, $teamAdditionSubject, $workSpaceName)
    {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME');
            $mail->Password   = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION');
            $mail->Port       = env('MAIL_PORT');
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = $teamAdditionSubject . " " . $workSpaceName;
            $mail->Body    = "<h1>You have been added to a new team '" . $workSpaceName . "' by: " . $invitedBy . "</h1>"
                . "<p>Please log in to see your projects and tasks.</p>";
            $mail->AltBody = " You have been added to a new team '" . $workSpaceName . "' by: " . $invitedBy . "Please log in to see your projects and tasks.";
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }


    static function sendEmailToRegister($email, $password, $invitedBy, $teamAdditionSubject, $workSpaceName)
    {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host       = env('MAIL_HOST');
            $mail->SMTPAuth   = true;
            $mail->Username   = env('MAIL_USERNAME');
            $mail->Password   = env('MAIL_PASSWORD');
            $mail->SMTPSecure = env('MAIL_ENCRYPTION');
            $mail->Port       = env('MAIL_PORT');
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = $teamAdditionSubject . " " . $workSpaceName . " On LookUp";
            $mail->Body    = "<h1>You have been added to a new team '" . $workSpaceName . "' by: " . $invitedBy . "</h1>"
                . "<p>Please log in with the below credentials to complete your account setup and access your projects and tasks:</p></br>"
                . "<p>Email: " . $email . "</p></br>"
                . "<p>Password: " . $password . "</p></br>";
            $mail->AltBody = "You have been added to a new team '" . $workSpaceName . "' by: " . $invitedBy
                . "Please log in with the below credentials to complete your account setup and access your projects and tasks:"
                . "Email: " . $email
                . "Password: " . $password;
            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
