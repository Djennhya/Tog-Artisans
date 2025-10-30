<?php
session_start();
require_once __DIR__ . '/admin/config.php';
require_once __DIR__ . '/includes/google2fa.php';

if (empty($_SESSION['pending_ga_user'])) {
    header('Location: login.php');
    exit;
}

$uid = (int) $_SESSION['pending_ga_user'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code'] ?? '');

    // récupérer secret en DB
    $res = mysqli_query($link, "SELECT id_user, nom_user, role, ga_secret FROM users WHERE id_user = $uid LIMIT 1");
    $u = mysqli_fetch_assoc($res);
    if (!$u) {
        $message = "Utilisateur introuvable.";
    } else {
        $tfa = tfa_instance();
        $secret = $u['ga_secret'];
        if ($secret && $tfa->verifyCode($secret, $code, 1)) {
            // ok -> finaliser connexion
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $u['id_user'];
            $_SESSION['user_name'] = $u['nom_user'];
            $_SESSION['user_role'] = $u['role'];

            unset($_SESSION['pending_ga_user']);
            // redirection par role
            if ($u['role'] === 'artisan') header('Location: artisans/index.php');
            elseif ($u['role'] === 'livreur') header('Location: livreurs/index.php');
            else header('Location: index.php');
            exit;
        } else {
            $message = "Code invalide ou expiré.";
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Vérifier 2FA</title></head>
<body>
<div class="container" style="max-width:420px;margin-top:60px;">
  <h4>Vérification 2FA</h4>
  <?php if ($message) echo "<div class='alert alert-danger'>".htmlspecialchars($message)."</div>"; ?>
  <form method="post">
    <div class="form-group">
      <label>Code (6 chiffres)</label>
      <input name="code" class="form-control" maxlength="6" required autofocus>
    </div>
    <button class="btn btn-primary">Valider</button>
  </form>
  <p><a href="login.php">Retour</a></p>
</div>
</body>
</html>