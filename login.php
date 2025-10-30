<!-- page login artisan -->
<?php
session_start();
require_once "admin/config.php";
require_once __DIR__ . '/includes/google2fa.php';

// s'assurer d'avoir $link (mysqli) depuis admin/config.php
if (!isset($link) || !$link) {
    $link = mysqli_connect('localhost', 'root', '', 'togartisans');
    if (!$link) die('DB connect error: ' . mysqli_connect_error());
}

$identifier = $password = "";
$identifier_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $identifier = trim($_POST["identifier"] ?? '');
    $password = trim($_POST["password"] ?? '');

    if (empty($identifier)) $identifier_err = "Veuillez entrer votre nom ou email.";
    if (empty($password)) $password_err = "Veuillez entrer votre mot de passe.";

    if (empty($identifier_err) && empty($password_err)) {
        $user = null;

        // Rechercher l'utilisateur (par email_user puis nom_user)
        $sqls = [
            "SELECT id_user, nom_user, email_user, password_user, phone_user, role, ga_enabled FROM users WHERE email_user = ? LIMIT 1",
            "SELECT id_user, nom_user, email_user, password_user, phone_user, role, ga_enabled FROM users WHERE nom_user = ? LIMIT 1"
        ];
        foreach ($sqls as $sql) {
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "s", $identifier);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) === 1) {
                    mysqli_stmt_bind_result($stmt, $id_user, $nom_user, $email_user, $hashed_password, $phone, $role, $ga_enabled);
                    mysqli_stmt_fetch($stmt);
                    $user = [
                        'id_user' => $id_user,
                        'nom_user' => $nom_user,
                        'email_user' => $email_user,
                        'password_user' => $hashed_password,
                        'phone_user' => $phone,
                        'role' => $role,
                        'ga_enabled' => $ga_enabled
                    ];
                    mysqli_stmt_close($stmt);
                    break;
                }
                mysqli_stmt_close($stmt);
            }
        }

        if (!$user) {
            $login_err = "Nom ou email ou mot de passe invalide.";
        } else {
            if (password_verify($password, $user['password_user'])) {
                // Si Google Authenticator activé -> rediriger vers verify_ga.php
                if (!empty($user['ga_enabled'])) {
                    $_SESSION['pending_ga_user'] = $user['id_user'];
                    header('Location: verify_ga.php');
                    exit;
                }

                // pas de 2FA -> connexion normale
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['user_name'] = $user['nom_user'];
                $_SESSION['user_role'] = $user['role'];

                // redirection selon rôle
                if ($user['role'] === 'artisan') header("Location: artisans/index.php");
                elseif ($user['role'] === 'livreur') header("Location: livreurs/index.php");
                else header("Location: index.php");
                exit;
            } else {
                $login_err = "Nom ou email ou mot de passe invalide.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Connexion - Tog'Artisans</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container" style="max-width:520px;margin-top:80px;">
    <h3 class="mb-3">Connexion</h3>
    <?php if (!empty($login_err)) echo '<div class="alert alert-danger">'.htmlspecialchars($login_err).'</div>'; ?>
    <form method="post" action="">
        <div class="form-group">
            <label>Nom ou email</label>
            <input type="text" name="identifier" class="form-control <?php echo (!empty($identifier_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($identifier); ?>">
            <div class="invalid-feedback"><?php echo $identifier_err; ?></div>
        </div>
        <div class="form-group">
            <label>Mot de passe</label>
            <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
            <div class="invalid-feedback"><?php echo $password_err; ?></div>
        </div>
        <button class="btn btn-primary">Se connecter</button>
    </form>
</div>
</body>
</html>