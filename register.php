<?php
session_start();
require_once "admin/config.php";
require_once __DIR__ . '/includes/send_email.php'; // notre fonction d'envoi

if (!isset($link) || !$link) {
    $link = mysqli_connect('localhost', 'root', '', 'togartisans');
    if (!$link) die('DB connect error: ' . mysqli_connect_error());
}

$nom_user = $email_user = $phone_user = $password_user = $confirm_password = $adresse_user = $role = "";
$nom_err = $email_err = $phone_err = $password_err = $confirm_password_err = $adresse_err = $role_err = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // NOM
    if (empty(trim($_POST["nom_user"]))) {
        $nom_err = "Veuillez entrer votre nom.";
    } else {
        $nom_user = trim($_POST["nom_user"]);
    }

    // EMAIL
    if (empty(trim($_POST["email_user"]))) {
        $email_err = "Veuillez entrer votre email.";
    } else {
        $email_user = trim($_POST["email_user"]);
        $sql = "SELECT id_user FROM users WHERE email_user = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $email_user);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) > 0) {
                $email_err = "Cet email est déjà utilisé.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    // TÉLÉPHONE
    if (empty(trim($_POST["phone_user"]))) {
        $phone_err = "Veuillez entrer votre numéro de téléphone.";
    } else {
        $phone_user = trim($_POST["phone_user"]);
    }

    // MOT DE PASSE
    if (empty(trim($_POST["password_user"]))) {
        $password_err = "Veuillez entrer un mot de passe.";
    } elseif (strlen(trim($_POST["password_user"])) < 6) {
        $password_err = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        $password_user = trim($_POST["password_user"]);
    }

    // CONFIRMATION
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Veuillez confirmer le mot de passe.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password_user !== $confirm_password)) {
            $confirm_password_err = "Les mots de passe ne correspondent pas.";
        }
    }

    // ADRESSE
    if (empty(trim($_POST["adresse_user"]))) {
        $adresse_err = "Veuillez entrer votre adresse.";
    } else {
        $adresse_user = trim($_POST["adresse_user"]);
    }

    // RÔLE
    if (empty($_POST["role"])) {
        $role_err = "Veuillez choisir un rôle.";
    } else {
        $role = $_POST["role"];
    }

    // Si pas d'erreurs
    if (empty($nom_err) && empty($email_err) && empty($phone_err) && empty($password_err) && empty($confirm_password_err) && empty($adresse_err) && empty($role_err)) {

        $sql = "INSERT INTO users (nom_user, email_user, phone_user, password_user, adresse_user, role) VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            $hashed = password_hash($password_user, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "ssssss", $nom_user, $email_user, $phone_user, $hashed, $adresse_user, $role);

            if (mysqli_stmt_execute($stmt)) {
                $new_user_id = mysqli_insert_id($link);

                // Générer un code aléatoire
                $code_2fa = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $expires = date('Y-m-d H:i:s', time() + 300);

                // Enregistrer le code en base
                $upd_sql = "UPDATE users SET code_2fa = ?, code_2fa_expires = ? WHERE id_user = ?";
                $upd_stmt = mysqli_prepare($link, $upd_sql);
                mysqli_stmt_bind_param($upd_stmt, "ssi", $code_2fa, $expires, $new_user_id);
                mysqli_stmt_execute($upd_stmt);

                // Envoi du mail dynamique
                $subject = "Code de validation sur Tog'Artisans";
                $body = "
                <h2>Bonjour $nom_user,</h2>
                <p>Merci de vous être inscrit sur <strong>Tog'Artisans</strong>.</p>
                <p>Voici votre code de confirmation :</p>
                <h1 style='color:#2b6cb0;'>$code_2fa</h1>
                <p>Ce code expirera dans 5 minutes.</p>
                ";
                send_email($email_user, $subject, $body);

                // Session
                $_SESSION['pending_2fa_user'] = $new_user_id;
                $_SESSION['pending_2fa_email'] = $email_user;
                $_SESSION['pending_2fa_code'] = $code_2fa;
                $_SESSION['pending_2fa_expires'] = $expires;

                header("Location: verify_2fa.php");
                exit;
            } else {
                echo "Erreur lors de l'inscription.";
            }
            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - Tog'Artisans</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5" style="max-width: 600px;">
    <h2 class="text-center">Inscription Tog'Artisans</h2>
    <form method="post" action="">
        <div class="form-group">
            <label>Nom</label>
            <input type="text" name="nom_user" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email_user" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Téléphone</label>
            <input type="text" name="phone_user" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="password_user" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Confirmer</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Adresse</label>
            <input type="text" name="adresse_user" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Rôle</label>
            <select name="role" class="form-control" required>
                <option value="">Choisissez...</option>
                <option value="client">Client</option>
                <option value="artisan">Artisan</option>
                <option value="livreur">Livreur</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
    </form>
</div>
</body>
</html>
