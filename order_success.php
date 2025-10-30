<!-- order success.php -->
<?php
// démarrer session et config en haut
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/admin/config.php';
include __DIR__ . '/header.php';

// vérif utilisateur
if (empty($_SESSION['user_id'])) {
    echo "<div class='container my-5'><p class='text-center text-danger'>Vous devez être connecté pour voir cette page.</p></div>";
    include __DIR__ . '/footer.php';
    exit;
}
$user_id = (int) $_SESSION['user_id'];

// chemin logs
$log = __DIR__ . '/logs/order_debug.log';
if (!is_dir(dirname($log))) mkdir(dirname($log), 0777, true);

// Si le process_order a stocké des infos en session, les utiliser en priorité
$order = null;
$order_id = $_SESSION['last_order_id'] ?? null;
$shop_name = $_SESSION['last_order_shop_name'] ?? '';
$deliverer_name = $_SESSION['last_order_deliverer_name'] ?? '';
$order_total = $_SESSION['last_order_total'] ?? null;

$conn = $link ?? mysqli_connect('localhost', 'root', '', 'togartisans');
if (!$conn) {
    file_put_contents($log, date('c') . " DB connect error: " . mysqli_connect_error() . PHP_EOL, FILE_APPEND);
    die("Erreur de connexion à la base de données.");
}

if ($order_id) {
    // tenter de récupérer la commande par id_commande pour afficher infos plus complètes si besoin
    $sql = "SELECT * FROM commandes WHERE id_commande = ? AND id_user = ? LIMIT 1";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ii", $order_id, $user_id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $order = $res ? mysqli_fetch_assoc($res) : false;
        mysqli_stmt_close($stmt);
    }
} else {
    // pas d'info en session : récupérer la dernière commande de l'utilisateur
    $sql = "SELECT * FROM commandes WHERE id_user = ? ORDER BY date_commande DESC LIMIT 1";
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        $order = $res ? mysqli_fetch_assoc($res) : false;
        mysqli_stmt_close($stmt);
        if ($order) $order_id = $order['id_commande'];
    }
}

// Si on a une commande, essayer de récupérer les noms artisan/livreur si manquent
if ($order) {
    $order_total = $order_total ?? $order['total'] ?? null;

    // shop name
    if (empty($shop_name) && !empty($order['id_artisan'])) {
        $aid = (int)$order['id_artisan'];
        if ($stmt = mysqli_prepare($conn, "SELECT nom_user FROM users WHERE id_user = ? LIMIT 1")) {
            mysqli_stmt_bind_param($stmt, "i", $aid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $n);
            if (mysqli_stmt_fetch($stmt)) $shop_name = $n;
            mysqli_stmt_close($stmt);
        }
    }

    // deliverer name
    if (empty($deliverer_name) && !empty($order['id_livreur'])) {
        $lid = (int)$order['id_livreur'];
        if ($stmt = mysqli_prepare($conn, "SELECT nom_user FROM users WHERE id_user = ? LIMIT 1")) {
            mysqli_stmt_bind_param($stmt, "i", $lid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $n2);
            if (mysqli_stmt_fetch($stmt)) $deliverer_name = $n2;
            mysqli_stmt_close($stmt);
        }
    }
}

// Si aucune commande trouvée : log et message
if (!$order && !$order_id) {
    $msg = date('c') . " NO ORDER for user_id={$user_id}. SQL errno=" . mysqli_errno($conn) . " err=" . mysqli_error($conn) . PHP_EOL;
    file_put_contents($log, $msg, FILE_APPEND);
    echo "<div class='container my-5'><p class='text-center text-danger'>Aucune commande trouvée.</p></div>";
    include __DIR__ . '/footer.php';
    if (!isset($link)) mysqli_close($conn);
    exit;
}

// Affichage de la page de succès
?>
<div class="container my-5">
    <div class="alert alert-success text-center">
        <h2>Merci pour votre commande !</h2>
        <p>Votre commande numéro <strong><?php echo htmlspecialchars($order_id ?? ($order['id_commande'] ?? '—')); ?></strong>
           a été passée avec succès le <strong><?php echo htmlspecialchars($order['date_commande'] ?? ($order['date_commande'] ?? date('Y-m-d H:i:s'))); ?></strong>.</p>
        <?php if ($order_total !== null): ?>
            <p>Montant total : <strong><?php echo number_format((float)$order_total, 2); ?> €</strong></p>
        <?php endif; ?>

        <?php if (!empty($shop_name)): ?>
            <p>Boutique : <strong><?php echo htmlspecialchars($shop_name); ?></strong></p>
        <?php endif; ?>

        <?php if (!empty($deliverer_name)): ?>
            <p>Livreur assigné : <strong><?php echo htmlspecialchars($deliverer_name); ?></strong></p>
        <?php endif; ?>

        <a href="shops_list.php" class="btn btn-primary mt-3">Retour aux boutiques</a>
    </div>
</div>
<?php
// Nettoyer les sessions temporaires liées à la commande
unset($_SESSION['last_order_id'], $_SESSION['last_order_shop_name'], $_SESSION['last_order_deliverer_name'], $_SESSION['last_order_total']);

// fermer connexion si on l'a ouverte ici
if (!isset($link)) mysqli_close($conn);
include __DIR__ . '/footer.php';
?>