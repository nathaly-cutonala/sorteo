<?php
require_once __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

function send_email( $email, $code, $name, $color ){
    $mail = new PHPMailer(true);
    
    // Specify the path and upload your .env file 
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();

    $sender_email = $_ENV['SENDER_EMAIL'];
    $sender_password = $_ENV['SENDER_PASSWORD'];

    try {
        $mail->SMTPDebug = 0;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   // Enable SMTP authentication
        $mail->Username = $sender_email;                     // SMTP username
        $mail->Password = $sender_password;                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        $mail->CharSet = 'UTF-8';
    
        // Sender and recipient settings
        $mail->setFrom($sender_email, 'Centro Universitario de Tonalá');
        $mail->addAddress( $email, $name ); // Add a recipient
    
        // Email content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Sorteo a Rally "Gestores Ambientales"';
        $mail->Body = "¡Felicidades " . $name . "! ¡Perteneces al equipo " . $color . "!<br><br>";
        $mail->Body .= 'Has sido registrado (a) con éxito al Rally "Gestores Ambientales". <br>';
        $mail->Body .="Tu código de estudiante: ". $code  . " <br>";
        $mail->Body .= "Tu correo institucional: " . $email . "<br><br>";
       // $mail->Body .= "Trae una prenda de color " . $color . " (playera, pantalón, cachucha, etc).<br>";
        $mail->Body .='¡Mucho éxito!';
        //$mail->AltBody = '';
    
        // Send email
        $mail->send();
       //echo 'Message has been sent successfully';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

?>