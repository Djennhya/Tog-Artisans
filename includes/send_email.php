<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function send_email($to, $subject, $body) {
    $mail = new PHPMailer(true);
    try {
        // Serveur SMTP (Gmail par exemple)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'sanzedje@gmail.com'; // ton email
        $mail->Password = 'jpfk obnv zjso shgi'; // mot de passe d’application
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Expéditeur / destinataire
        $mail->setFrom('sanzedje@gmail.com', 'Tog\'Artisans');
        $mail->addAddress($to);

        // Contenu
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        file_put_contents(__DIR__ . '/../logs/email_error.log', date('c') . " ERR: {$mail->ErrorInfo}\n", FILE_APPEND);
        return false;
    }
}
