<!-- forgot password -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - Tog'Artisans</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            border: none;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container" style="max-width:400px;">
        <h2>Mot de passe oublié</h2>
        <form action="process_forgot_password.php" method="post">
            <label for="email">Entrez votre adresse email :</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Réinitialiser le mot de passe</button>
        </form>
    </div>
</body>
</html>
<!-- profile.php -->
<?php
// Gestion de la 2FA
if (empty($user['ga_secret']) && !empty($_POST['enable_ga'])) {
    // Générer un nouveau secret
    $tfa = tfa_instance();
    $secret = $tfa->createSecret(160);
    $_SESSION['ga_pending_secret'] = $secret;
} elseif (!empty($user['ga_secret']) && !empty($_POST['disable_ga'])) {
    // Désactiver la 2FA
    mysqli_query($link, "UPDATE users SET ga_secret = NULL, ga_enabled = 0 WHERE id_user = $user_id");
    $user['ga_enabled'] = 0;
    $user['ga_secret'] = null;
}
?>
<!-- login.php -->
<?php
// Initialiser les variables
$username = $password = "";
$username_err = $password_err = $login_err = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Valider le nom d'utilisateur
    if (empty(trim($_POST["username"]))) {
        $username_err = "Veuillez entrer votre nom d'utilisateur.";
    } else {
        $username = trim($_POST["username"]);
    }
    // Valider le mot de passe
    if (empty(trim($_POST["password"]))) {
        $password_err = "Veuillez entrer votre mot de passe.";
    } else {
        $password = trim($_POST["password"]);
    }
    // Vérifier les identifiants
    if (empty($username_err) && empty($password_err)) {
        $sql = "SELECT id_user, nom_user, password_hash, role, ga_enabled FROM users WHERE nom_user = ? LIMIT 1";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $username;
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id_user, $nom_user, $hashed_password, $role, $ga_enabled);
                    if (mysqli_stmt_fetch($stmt)) {
                        if (password_verify($password, $hashed_password)) {
                            // Vérifier si 2FA est activé
                            if (!empty($ga_enabled)) {
                                $_SESSION['pending_ga_user'] = $id_user;
                                header("Location: verify_ga.php");
                                exit;
                            }
                            // Connexion réussie sans 2FA
                            session_start();
                            $_SESSION['loggedin'] = true;
                            $_SESSION['user_id'] = $id_user;
                            $_SESSION['user_name'] = $nom_user;
                            $_SESSION['user_role'] = $role;
                            header("Location: dashboard.php");
                        } else {
                            $login_err = "Nom d'utilisateur ou mot de passe invalide.";
                        }
                    }
                } else {
                    $login_err = "Nom d'utilisateur ou mot de passe invalide.";
                }
            } else {
                echo "Oups! Quelque chose a mal tourné. Veuillez réessayer plus tard.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
}
?>
<!-- sidebar.php -->
<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$displayName = 'Artisan';
if (!empty($_SESSION['role']) && $_SESSION['role'] === 'artisan') {
    if (!empty($_SESSION['nom_user'])) {
        $displayName = $_SESSION['nom_user'];
    } elseif (!empty($_SESSION['id_user'])) {
        require_once __DIR__ . '/../admin/config.php';
        $uid = (int) $_SESSION['id_user'];
        $res = mysqli_query($conn, "SELECT nom_user FROM users WHERE id_user = $uid LIMIT 1");
        if ($res && $r = mysqli_fetch_assoc($res)) {
            $displayName = $r['nom_user'];
        }
    }
}