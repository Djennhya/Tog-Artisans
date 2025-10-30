<?php
session_start();
require_once __DIR__ . '/admin/config.php';

$message = '';
if (empty($_SESSION['pending_2fa_user'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code'] ?? '');
    $uid = (int) $_SESSION['pending_2fa_user'];

    // Utiliser le code stocké en session ou en DB
    $expected = $_SESSION['pending_2fa_code'] ?? null;
    $expires = $_SESSION['pending_2fa_expires'] ?? null;

    $res = mysqli_query($link, "SELECT id_user, nom_user, role, code_2fa, code_2fa_expires FROM users WHERE id_user = $uid LIMIT 1");
    if ($res && $row = mysqli_fetch_assoc($res)) {
        $expected = $row['code_2fa'] ?: $expected;
        $expires = $row['code_2fa_expires'] ?: $expires;
        $user_name = $row['nom_user'];
        $user_role = $row['role'];
    }

    if ($expected && $code === $expected && strtotime($expires) >= time()) {
        // validation OK -> créer session minimale puis rediriger vers login pour connexion normale
        // (on ne met pas loggedin ici si tu veux forcer l'utilisateur à se reconnecter)
        $_SESSION['2fa_confirmed_user'] = $uid;
        $_SESSION['2fa_confirmed_name'] = $user_name ?? ($_SESSION['pending_2fa_name'] ?? '');
        $_SESSION['2fa_confirmed_role'] = $user_role ?? ($_SESSION['pending_2fa_role'] ?? 'client');

        // effacer code en DB si possible
        if (isset($link)) {
            $upd = mysqli_prepare($link, "UPDATE users SET code_2fa = NULL, code_2fa_expires = NULL WHERE id_user = ?");
            if ($upd) { mysqli_stmt_bind_param($upd, "i", $uid); mysqli_stmt_execute($upd); mysqli_stmt_close($upd); }
        }

        // cleanup pending
        unset($_SESSION['pending_2fa_user'], $_SESSION['pending_2fa_code'], $_SESSION['pending_2fa_expires'], $_SESSION['pending_2fa_phone'], $_SESSION['pending_2fa_name'], $_SESSION['pending_2fa_role']);

        // mettre un message flash simple
        $_SESSION['flash_success'] = "Votre numéro a été vérifié avec succès. Vous pouvez maintenant vous connecter.";

        // rediriger vers la page de login
        header('Location: login.php');
        exit;
    } else {
        $message = "Code invalide ou expiré.";
    }
}
?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Vérification du code</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container" style="max-width:480px;margin-top:80px;">
  <h4>Vérification du code</h4>
  <?php if (!empty($message)) echo "<div class='alert alert-danger'>".htmlspecialchars($message)."</div>"; ?>
  <p>Entrez le code reçu par SMS sur votre téléphone.</p>
  <form method="post">
    <div class="form-group">
      <input type="text" name="code" class="form-control" maxlength="6" required autofocus>
    </div>
    <button class="btn btn-primary">Valider</button>
  </form>
</div>
</body>
</html>