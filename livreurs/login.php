<!-- page login artisan -->
<?php
require_once "../admin/config.php";

// connexion DB si non fournie par config
if (!isset($link) || !$link) {
    $db_host = $host ?? 'localhost';
    $db_user = $username ?? 'root';
    $db_pass = $password ?? '';
    $db_name = $dbname ?? 'togartisans';
    $link = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
    if (!$link) die('DB connect error: ' . mysqli_connect_error());
}

$username = $password = "";
$username_err = $password_err = $login_err = "";

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    // déjà connecté : redirection selon role si présent
    $role = $_SESSION['user_role'] ?? 'user';
    if ($role === 'artisan') header("Location: artisans/index.php");
    elseif ($role === 'livreur') header("Location: livreur/index.php");
    else header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $identifier = trim($_POST["username"] ?? '');
    $password = trim($_POST["password"] ?? '');

    if (empty($identifier)) $username_err = "Veuillez entrer votre email ou nom d'utilisateur.";
    if (empty($password)) $password_err = "Veuillez entrer votre mot de passe.";

    if (empty($username_err) && empty($password_err)) {
        // chercher d'abord par email, puis par username (pour compatibilité)
        $user = null;
        $sql = "SELECT id_user, nom_user, email_user, username, password_user, phone, role FROM users WHERE email_user = ? LIMIT 1";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $identifier);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $id_user, $nom_user, $email_user, $db_username, $hashed_password, $phone, $role);
            if (mysqli_stmt_fetch($stmt)) {
                $user = [
                    'id_user' => $id_user,
                    'nom_user' => $nom_user,
                    'email_user' => $email_user,
                    'username' => $db_username,
                    'password_user' => $hashed_password,
                    'phone' => $phone,
                    'role' => $role
                ];
            }
            mysqli_stmt_close($stmt);
        }
        if (!$user) {
            // essayer par username si la colonne existe
            $sql2 = "SELECT id_user, nom_user, email_user, username, password_user, phone, role FROM users WHERE username = ? LIMIT 1";
            if ($stmt2 = mysqli_prepare($link, $sql2)) {
                mysqli_stmt_bind_param($stmt2, "s", $identifier);
                mysqli_stmt_execute($stmt2);
                mysqli_stmt_bind_result($stmt2, $id_user, $nom_user, $email_user, $db_username, $hashed_password, $phone, $role);
                if (mysqli_stmt_fetch($stmt2)) {
                    $user = [
                        'id_user' => $id_user,
                        'nom_user' => $nom_user,
                        'email_user' => $email_user,
                        'username' => $db_username,
                        'password_user' => $hashed_password,
                        'phone' => $phone,
                        'role' => $role
                    ];
                }
                mysqli_stmt_close($stmt2);
            }
        }

        if (!$user) {
            $login_err = "Nom d'utilisateur ou mot de passe invalide.";
        } else {
            // vérifier le mot de passe
            if (password_verify($password, $user['password_user'])) {
                // générer code 2FA
                $code2 = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
                $expires = date('Y-m-d H:i:s', time() + 300); // 5 minutes

                // tenter de stocker en base (si colonnes existent)
                $updated_db = false;
                $upd_sql = "UPDATE users SET code_2fa = ?, code_2fa_expires = ? WHERE id_user = ?";
                if ($upd_stmt = @mysqli_prepare($link, $upd_sql)) {
                    mysqli_stmt_bind_param($upd_stmt, "ssi", $code2, $expires, $user['id_user']);
                    if (mysqli_stmt_execute($upd_stmt)) $updated_db = true;
                    mysqli_stmt_close($upd_stmt);
                }

                // toujours mettre en session en fallback
                $_SESSION['pending_2fa_user'] = $user['id_user'];
                $_SESSION['pending_2fa_code'] = $code2;
                $_SESSION['pending_2fa_expires'] = $expires;
                $_SESSION['pending_2fa_name'] = $user['nom_user'];
                $_SESSION['pending_2fa_phone'] = $user['phone'];
                $_SESSION['pending_2fa_role'] = $user['role'];

                // envoyer SMS (fonction ci-dessous)
                if (!empty($user['phone'])) {
                    send_sms($user['phone'], "Tog'Artisans - Votre code de vérification est le suivant : {$code2}");
                } else {
                    // log si pas de numéro
                    file_put_contents(__DIR__ . '/sms_send.log', date('c') . " - no phone for user {$user['id_user']}\n", FILE_APPEND);
                }

                header("Location: verify_2fa.php");
                exit;
            } else {
                $login_err = "Nom d'utilisateur ou mot de passe invalide.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Tog'Artisans</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style> body{ font:14px sans-serif; } .wrapper{ width:600px; padding:30px; margin:auto; margin-top:100px; border:1px solid #ccc; border-radius:10px; } </style>
</head>
<body>
<div class="wrapper">
    <h1 class="text-center">PAGE DE CONNEXION</h1>
    <p>Veuillez entrer vos identifiants s'il vous plait.</p>
    <?php if (!empty($login_err)) echo '<div class="alert alert-danger">'.htmlspecialchars($login_err).'</div>'; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" novalidate>
        <div class="form-group">
            <label>Email ou nom d'utilisateur</label>
            <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($username); ?>">
            <span class="invalid-feedback"><?php echo $username_err; ?></span>
        </div>
        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Se connecter">
        </div>
        <p>Pas de compte ? <a href="register.php">Inscrivez-vous</a>.</p>
    </form>
</div>
</body>
</html>
<?php
// simple send_sms util (Twilio example). Remplace par AfricasTalking si tu utilises AT
function send_sms($to, $message){
    // if no config, bail out
    $sid = getenv('TWILIO_SID');
    $token = getenv('TWILIO_TOKEN');
    $from = getenv('TWILIO_FROM');
    if (!$sid || !$token || !$from) {
        // log fallback
        file_put_contents(__DIR__.'/sms_send.log', date('c') . " - TWILIO not configured. to={$to} msg={$message}\n", FILE_APPEND);
        return false;
    }
    $url = "https://api.twilio.com/2010-04-01/Accounts/$sid/Messages.json";
    $data = http_build_query(['To' => $to, 'From' => $from, 'Body' => $message]);
    $opts = ["http" => ["method" => "POST", "header" => "Authorization: Basic " . base64_encode("$sid:$token") . "\r\n" . "Content-Type: application/x-www-form-urlencoded\r\n", "content" => $data]];
    $context = stream_context_create($opts);
    $result = @file_get_contents($url, false, $context);
    file_put_contents(__DIR__.'/sms_send.log', date('c') . " to={$to} result=" . var_export($result, true) . PHP_EOL, FILE_APPEND);
    return $result !== false;
}
?>