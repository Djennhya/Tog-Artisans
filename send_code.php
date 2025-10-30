<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // si tu utilises Composer

session_start();

// Fonction pour générer un code aléatoire
function generateVerificationCode($length = 6) {
    $chars = '0123456789';
    return substr(str_shuffle($chars), 0, $length);
}

if (isset($_POST['email'])) {
    $email = trim($_POST['email']);
    $code = generateVerificationCode();

    // Sauvegarde en session (tu peux aussi enregistrer en base de données)
    $_SESSION['verification_email'] = $email;
    $_SESSION['verification_code'] = $code;
    $_SESSION['code_expires_at'] = time() + (10 * 60); // 10 minutes

    $mail = new PHPMailer(true);

    try {
        // CONFIG SMTP (exemple Gmail)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'tonemail@gmail.com';
        $mail->Password = 'TON_MOT_DE_PASSE_APP'; // mot de passe d'application Gmail
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Destinataire
        $mail->setFrom('tonemail@gmail.com', 'Togartisans');
        $mail->addAddress($email);

        // Contenu dynamique
        $mail->isHTML(true);
        $mail->Subject = 'Votre code de vérification - Togartisans';
        $mail->Body = "
            <h2>Vérification de votre adresse email</h2>
            <p>Voici votre code de vérification :</p>
            <h3 style='color:#2c3e50;'>$code</h3>
            <p>Ce code expirera dans 10 minutes.</p>
            <hr>
            <small>Si vous n’êtes pas à l’origine de cette demande, ignorez ce message.</small>
        ";

        $mail->send();

        echo json_encode([
            'status' => 'success',
            'message' => 'Le code de vérification a été envoyé à votre adresse email.'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'status' => 'error',
            'message' => "Erreur lors de l'envoi du mail : " . $mail->ErrorInfo
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Aucune adresse email reçue.'
    ]);
}
?>
