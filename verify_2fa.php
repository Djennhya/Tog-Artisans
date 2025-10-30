<?php
session_start();
require_once "admin/config.php";

if (!isset($_SESSION['pending_2fa_user'])) {
    header("Location: registrer.php");
    exit;
}

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $input_code = trim($_POST["code"]);

    if (
        $input_code == $_SESSION['pending_2fa_code'] &&
        time() < strtotime($_SESSION['pending_2fa_expires'])
    ) {
        $user_id = $_SESSION['pending_2fa_user'];
        $upd = mysqli_prepare($link, "UPDATE users SET is_verified = 1 WHERE id_user = ?");
        mysqli_stmt_bind_param($upd, "i", $user_id);
        mysqli_stmt_execute($upd);

        // ✅ On nettoie la session avant redirection
        session_unset();
        session_destroy();

        // ✅ Redirection vers la page de connexion
        header("Location: login.php?verified=1");
        exit;
    } else {
        $error = "❌ Code invalide ou expiré.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Vérification Email</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5" style="max-width: 500px;">
    <h3 class="text-center">Vérification du Code</h3>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php else: ?>
        <p>Un code a été envoyé à votre adresse email : <strong><?= htmlspecialchars($_SESSION['pending_2fa_email']) ?></strong></p>
        <form method="post">
            <input type="text" name="code" class="form-control mb-3" placeholder="Entrez le code à 6 chiffres" required>
            <button class="btn btn-success btn-block">Vérifier</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
