<?php
session_start();
require_once "../admin/config.php";
// send_sms est dans /includes à la racine → remonter d'un niveau
require_once __DIR__ . '/../includes/send_sms.php';

// s'assurer de la connexion mysqli dans $link
if (!isset($link) || !$link) {
    $link = mysqli_connect('localhost', 'root', '', 'togartisans');
    if (!$link) die('DB connect error: ' . mysqli_connect_error());
}

$nom_user = $email_user = $phone_user = $password_user = $confirm_password = $adresse_user = $role = "";
$nom_err = $email_err = $phone_err = $password_err = $confirm_password_err = $adresse_err = $role_err = "";

// helper logs dir
$logDir = __DIR__ . '/../logs';
if (!is_dir($logDir)) mkdir($logDir, 0777, true);

// déterminer dynamiquement le nom de la colonne téléphone dans la table users
$phone_column = 'phone';
$res_check = mysqli_query($link, "SHOW COLUMNS FROM users LIKE 'phone'");
if (!$res_check || mysqli_num_rows($res_check) === 0) {
    $res_check2 = mysqli_query($link, "SHOW COLUMNS FROM users LIKE 'phone_user'");
    if ($res_check2 && mysqli_num_rows($res_check2) > 0) {
        $phone_column = 'phone_user';
    } else {
        // si aucune des deux n'existe, arrêter et logguer pour debugging
        file_put_contents(__DIR__ . '/../logs/register_error.log', date('c') . " ERROR: colonne phone introuvable dans users\n", FILE_APPEND);
        die('Configuration DB incorrecte : colonne téléphone introuvable. Vérifiez la table users.');
    }
}

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
        $phone_raw = trim($_POST["phone_user"]);
        $digits = preg_replace('/\D/', '', $phone_raw);
        if (strlen($digits) === 8) {
            $phone_norm = '+228' . $digits;
        } else {
            $phone_norm = (strpos($phone_raw, '+') === 0) ? $phone_raw : ('+' . $digits);
        }

        $sql = "SELECT id_user FROM users WHERE {$phone_column} = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $phone_norm);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) > 0) {
                $phone_err = "Ce numéro est déjà utilisé.";
            } else {
                $phone_user = $phone_norm;
            }
            mysqli_stmt_close($stmt);
        } else {
            file_put_contents(__DIR__ . '/../logs/register_error.log', date('c') . " PREPARE ERR on phone check: " . mysqli_error($link) . "\n", FILE_APPEND);
            $phone_err = "Erreur serveur (vérification téléphone).";
        }
    }

    // MOT DE PASSE
    if (empty(trim($_POST["password_user"]))) {
        $password_err = "Veuillez entrer un mot de passe.";
    } elseif (strlen(trim($_POST["password_user"])) < 6) {
        $password_err = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        $password_user = trim($_POST["password_user"]);
    }

    // CONFIRMATION MOT DE PASSE
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

    // ROLE
    if (empty($_POST["role"])) {
        $role_err = "Veuillez choisir un rôle.";
    } else {
        $role = $_POST["role"];
    }

    // Si pas d'erreurs → insertion
    if (empty($nom_err) && empty($email_err) && empty($phone_err) && empty($password_err) && empty($confirm_password_err) && empty($adresse_err) && empty($role_err)) {

        $cols = "nom_user, email_user, {$phone_column}, password_user, adresse_user, role";
        $sql = "INSERT INTO users ($cols) VALUES (?, ?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            $hashed = password_hash($password_user, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "ssssss", $nom_user, $email_user, $phone_user, $hashed, $adresse_user, $role);

            if (mysqli_stmt_execute($stmt)) {
                $new_user_id = mysqli_insert_id($link);

                // Normaliser le téléphone (utilise la valeur fournie)
                $phone_raw = $param_phone ?? $phone;
                $digits = preg_replace('/[^\d]/', '', $phone_raw);
                if (strlen($digits) === 8) {
                    $phone = '+228' . $digits;
                } elseif (strpos($phone_raw, '+') === 0) {
                    $phone = $phone_raw;
                } else {
                    $phone = '+' . $digits;
                }

                // Générer code 2FA
                $code_2fa = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $expires = date('Y-m-d H:i:s', time() + 300);

                // Mettre à jour la table users avec le code_2fa (si colonnes existent)
                $upd_sql = "UPDATE users SET code_2fa = ?, code_2fa_expires = ? WHERE id_user = ?";
                if ($upd_stmt = @mysqli_prepare($link, $upd_sql)) {
                    mysqli_stmt_bind_param($upd_stmt, "ssi", $code_2fa, $expires, $new_user_id);
                    mysqli_stmt_execute($upd_stmt);
                    mysqli_stmt_close($upd_stmt);
                }

                // Envoi du SMS via Africa's Talking
                if (!empty($phone)) {
                    $message = "Tog'Artisans - Votre code de vérification : $code_2fa";
                    $res = send_sms($phone, $message);
                    file_put_contents(__DIR__ . '/../logs/sms_send.log', date('c') . " REGISTER to={$phone} ok=" . ($res['ok'] ? '1' : '0') . " msg=" . json_encode($res['msg']) . PHP_EOL, FILE_APPEND);
                } else {
                    file_put_contents(__DIR__ . '/../logs/sms_send.log', date('c') . " REGISTER no phone for user {$new_user_id}\n", FILE_APPEND);
                }

                // Stocker pending 2FA en session et rediriger vers verification SMS
                $_SESSION['pending_2fa_user'] = $new_user_id;
                $_SESSION['pending_2fa_code'] = $code_2fa;
                $_SESSION['pending_2fa_expires'] = $expires;
                $_SESSION['pending_2fa_phone'] = $phone;
                $_SESSION['pending_2fa_name'] = $nom_user;
                $_SESSION['pending_2fa_role'] = $role;

                mysqli_stmt_close($stmt);
                mysqli_close($link);

                header("Location: ../verify_2fa.php");
                exit;
            } else {
                $error_msg = mysqli_error($link);
                file_put_contents($logDir . '/register_error.log', date('c') . " INSERT ERR: " . $error_msg . PHP_EOL, FILE_APPEND);
                echo "Erreur lors de l'inscription. Contactez l'administrateur.";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Erreur préparation requête.";
        }
    }
    // fermeture connexion
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - Tog'Artisans</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font: 14px sans-serif; }
        .wrapper {
            width: 650px;
            padding: 30px;
            margin: auto;
            margin-top: 80px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <h2><center>PAGE D'INSCRIPTION</center></h2>
    <p><center>Veuillez remplir ce formulaire pour créer un compte.</center></p>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

        <div class="form-group">
            <label>Nom :</label>
            <input type="text" name="nom_user" class="form-control" value="<?php echo htmlspecialchars($nom_user); ?>" required>
            <span class="text-danger"><?php echo $nom_err; ?></span>
        </div>

        <div class="form-group">
            <label>Email :</label>
            <input type="email" name="email_user" class="form-control" value="<?php echo htmlspecialchars($email_user); ?>" required>
            <span class="text-danger"><?php echo $email_err; ?></span>
        </div>

        <div class="form-group">
            <label>Téléphone :</label>
            <input type="text" name="phone_user" class="form-control" value="<?php echo htmlspecialchars($phone); ?>" required>
            <span class="text-danger"><?php echo $phone_err; ?></span>
        </div>

        <div class="form-group">
            <label>Mot de passe :</label>
            <input type="password" name="password_user" class="form-control" required>
            <span class="text-danger"><?php echo $password_err; ?></span>
        </div>

        <div class="form-group">
            <label>Confirmer le mot de passe :</label>
            <input type="password" name="confirm_password" class="form-control" required>
            <span class="text-danger"><?php echo $confirm_password_err; ?></span>
        </div>

        <div class="form-group">
            <label>Adresse :</label>
            <input type="text" name="adresse_user" class="form-control" value="<?php echo htmlspecialchars($adresse_user); ?>" required>
            <span class="text-danger"><?php echo $adresse_err; ?></span>
        </div>

        <div class="form-group">
            <label>Rôle :</label>
            <select name="role" class="form-control" required>
                <option value="">Sélectionnez votre rôle</option>
                <option value="client" <?php if($role=='client') echo 'selected'; ?>>Client</option>
                <option value="artisan" <?php if($role=='artisan') echo 'selected'; ?>>Artisan</option>
                <option value="livreur" <?php if($role=='livreur') echo 'selected'; ?>>Livreur</option>
            </select>
            <span class="text-danger"><?php echo $role_err; ?></span>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">S'inscrire</button>
            <button type="reset" class="btn btn-secondary ml-2">Réinitialiser</button>
        </div>

    </form>
</div>
</body>
</html>
