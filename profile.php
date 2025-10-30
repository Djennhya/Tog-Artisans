<!-- mon profil utilisateur et modification et mes historiques de commandes -->
<?php
require_once "admin/config.php";
require_once __DIR__ . '/includes/google2fa.php';
include("header.php");

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = (int) $_SESSION['user_id'];
$res = mysqli_query($link, "SELECT ga_secret, ga_enabled, nom_user FROM users WHERE id_user = $user_id");
$user = mysqli_fetch_assoc($res);

// génération du secret temporaire si non présent
if (empty($user['ga_secret'])) {
    $tfa = tfa_instance();
    $secret = $tfa->createSecret(160);
    $_SESSION['ga_pending_secret'] = $secret;
} else {
    $secret = $user['ga_secret'];
}

$qrImage = '';
if (!empty($secret)) {
    $tfa = tfa_instance();
    $label = ($user['nom_user'] ?? 'user') . '@TogArtisans';
    $qrImage = $tfa->getQRCodeImageAsDataUri($label, $secret);
}

// Traitement activation/desactivation
$msg = $err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirm_ga'])) {
        $code = trim($_POST['code'] ?? '');
        $secretToVerify = $_SESSION['ga_pending_secret'] ?? $secret;
        $tfa = tfa_instance();
        if ($tfa->verifyCode($secretToVerify, $code, 1)) {
            $secret_esc = mysqli_real_escape_string($link, $secretToVerify);
            mysqli_query($link, "UPDATE users SET ga_secret = '{$secret_esc}', ga_enabled = 1 WHERE id_user = $user_id");
            unset($_SESSION['ga_pending_secret']);
            $msg = "2FA activé avec succès.";
            // rafraîchir
            $user['ga_enabled'] = 1;
            $user['ga_secret'] = $secretToVerify;
        } else {
            $err = "Code invalide. Réessayez.";
        }
    } elseif (isset($_POST['disable_ga'])) {
        // optionnel: vérifier mot de passe avant désactivation
        mysqli_query($link, "UPDATE users SET ga_secret = NULL, ga_enabled = 0 WHERE id_user = $user_id");
        $msg = "2FA désactivé.";
        $user['ga_enabled'] = 0;
        $user['ga_secret'] = null;
    }
}
?>
<div class="container my-5">
    <h1>Mon Profil</h1>
    <div class="card p-4 shadow-sm">
        <h1>Informations Personnelles</h1>
        <form action="process_profile.php" method="post">
            <div class="form-group">
                <label for="nom_user">Nom Complet</label>
                <input type="text" class="form-control" id="nom_user" name="nom_user" value="<?php echo htmlspecialchars($user['nom_user']); ?>" required>
            </div>
            <div class="form-group">
                <label for="adresse_user">Adresse</label>
                <input type="text" class="form-control" id="adresse_user" name="adresse_user" value="<?php echo htmlspecialchars($user['adresse_user']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_user">Téléphone</label>
                <input type="text" class="form-control" id="phone_user" name="phone_user" value="<?php echo htmlspecialchars($user['phone_user']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Mettre à Jour</button>
        </form>
    </div>
    <div class="mt-5">
        <h1>Historique des Commandes</h1>
        <?php
        // Récupérer les commandes de l'utilisateur
        $order_sql = "SELECT * FROM commandes WHERE id_user = $user_id ORDER BY date_commande DESC";
        $order_res = mysqli_query($conn, $order_sql);
        if ($order_res && mysqli_num_rows($order_res) > 0):
        ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Commande</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = mysqli_fetch_assoc($order_res)): ?>
                    <tr>
                        <td><?php echo (int)$order['id_commande']; ?></td>
                        <td><?php echo htmlspecialchars($order['date_commande']); ?></td>
                        <td><?php echo htmlspecialchars($order['total']); ?> €</td>
                        <td><?php echo htmlspecialchars($order['statut']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune commande passée pour le moment.</p>
        <?php endif; ?>
    </div>
    <div class="mt-5">
        <h1>Authentification à deux facteurs (Google Authenticator)</h1>
        <?php if ($msg) echo "<div class='alert alert-success'>".htmlspecialchars($msg)."</div>"; ?>
        <?php if ($err) echo "<div class='alert alert-danger'>".htmlspecialchars($err)."</div>"; ?>

        <div class="card">
            <div class="card-header">Configurer 2FA</div>
            <div class="card-body">
                <?php if (!empty($user['ga_enabled'])): ?>
                    <p>2FA activé pour votre compte.</p>
                    <form method="post" onsubmit="return confirm('Confirmer la désactivation ?');">
                        <button type="submit" name="disable_ga" class="btn btn-danger">Désactiver 2FA</button>
                    </form>
                <?php else: ?>
                    <p>Scannez ce QR dans Google Authenticator ou entrez la clé manuellement, puis saisissez le code :</p>
                    <?php if ($qrImage): ?>
                        <img src="<?= $qrImage ?>" alt="QR Code" style="max-width:200px;">
                        <p>Clé secrète : <strong><?= htmlspecialchars($secret) ?></strong></p>
                    <?php endif; ?>
                    <form method="post">
                        <div class="form-group">
                            <label>Code généré par l'application</label>
                            <input type="text" name="code" class="form-control" maxlength="6" required>
                        </div>
                        <button type="submit" name="confirm_ga" class="btn btn-primary">Activer 2FA</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php
mysqli_close($conn);
include("footer.php");
?>