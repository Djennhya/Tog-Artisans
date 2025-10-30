<?php
// ...existing code...
require_once __DIR__ . '/includes/google2fa.php';

$user_id = (int) $_SESSION['user_id'];

// récupère la ligne user
$res = mysqli_query($link, "SELECT ga_secret, ga_enabled, nom_user FROM users WHERE id_user = $user_id");
$user = mysqli_fetch_assoc($res);

// générer secret temporaire si pas encore en DB (ne pas écraser existant tant qu'on n'a pas validé)
if (empty($user['ga_secret'])) {
    $tfa = tfa_instance();
    $secret = $tfa->createSecret(160); // secret base32
    // stocker le secret temporairement en session avant confirmation
    $_SESSION['ga_pending_secret'] = $secret;
} else {
    $secret = $user['ga_secret'];
}

$qrImage = '';
if (!empty($secret)) {
    $tfa = tfa_instance();
    // label: nom utilisateur + site
    $label = ($user['nom_user'] ?? 'user') . '@TogArtisans';
    $qrImage = $tfa->getQRCodeImageAsDataUri($label, $secret);
}

// Traitement du formulaire de confirmation (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_ga'])) {
    $code = trim($_POST['code'] ?? '');
    // utiliser secret en session (généré juste avant)
    $secretToVerify = $_SESSION['ga_pending_secret'] ?? $secret;
    $tfa = tfa_instance();
    if ($tfa->verifyCode($secretToVerify, $code, 1)) { // tolérance 1 (30s)
        // sauvegarder définitivement le secret en DB et activer
        $secret_esc = mysqli_real_escape_string($link, $secretToVerify);
        mysqli_query($link, "UPDATE users SET ga_secret = '{$secret_esc}', ga_enabled = 1 WHERE id_user = $user_id");
        unset($_SESSION['ga_pending_secret']);
        $msg = "2FA activé avec succès.";
        // rafraîchir user
        $user['ga_enabled'] = 1;
        $user['ga_secret'] = $secretToVerify;
    } else {
        $err = "Code invalide. Réessayez.";
    }
}

// affichage (insérer dans la page profil à l'endroit souhaité)
?>
<div class="card mb-4">
  <div class="card-header">Authentification à deux facteurs (Google Authenticator)</div>
  <div class="card-body">
    <?php if (!empty($msg)): ?><div class="alert alert-success"><?=htmlspecialchars($msg)?></div><?php endif; ?>
    <?php if (!empty($err)): ?><div class="alert alert-danger"><?=htmlspecialchars($err)?></div><?php endif; ?>

    <?php if (!empty($user['ga_enabled'])): ?>
      <p>2FA activé pour votre compte.</p>
      <form method="post" onsubmit="return confirm('Désactiver 2FA ?');">
        <button name="disable_ga" class="btn btn-danger">Désactiver 2FA</button>
      </form>
      <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['disable_ga'])) {
            // optionnel : demander mot de passe/TOTP pour désactiver
            mysqli_query($link, "UPDATE users SET ga_secret = NULL, ga_enabled = 0 WHERE id_user = $user_id");
            echo "<script>location.reload();</script>";
            exit;
        }
      ?>
    <?php else: ?>
      <p>Scannez ce QR dans Google Authenticator (ou entrez la clé manuellement) puis saisissez le code ci-dessous pour activer :</p>
      <?php if ($qrImage): ?>
        <img src="<?= $qrImage ?>" alt="QR Code" style="max-width:200px;">
        <p>Clé secrète : <strong><?= htmlspecialchars($secret) ?></strong></p>
      <?php endif; ?>
      <form method="post">
        <div class="form-group">
          <label>Code généré par l'application</label>
          <input type="text" name="code" class="form-control" maxlength="6" required>
        </div>
        <button name="confirm_ga" class="btn btn-primary">Activer 2FA</button>
      </form>
    <?php endif; ?>
  </div>
</div>
<?php
// ...existing code...