<?php
session_start();
require_once __DIR__ . '/admin/config.php'; // contient $link

$log = __DIR__ . '/logs/order_debug.log';
if (!is_dir(dirname($log))) mkdir(dirname($log), 0777, true);

// === Vérif session utilisateur ===
if (empty($_SESSION['user_id'])) {
    file_put_contents($log, date('c') . " ERROR: no user session\n", FILE_APPEND);
    header('Location: login.php');
    exit;
}

$user_id = (int)$_SESSION['user_id'];

// === Vérif panier en session ===
if (empty($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    file_put_contents($log, date('c') . " ERROR: empty session cart for user {$user_id}\n", FILE_APPEND);
    header('Location: cart.php');
    exit;
}

$cart_items = $_SESSION['cart'];

// === Champs du formulaire ===
$mode_paiement = trim($_POST['mode_paiement'] ?? 'cash');
$adresse_livraison = trim($_POST['adresse_livraison'] ?? '');
$date_livraison = trim($_POST['date_livraison'] ?? null);
$id_livreur = isset($_POST['id_livreur']) ? (int)$_POST['id_livreur'] : 0;
$id_artisan = isset($_POST['id_artisan']) ? (int)$_POST['id_artisan'] : 0;
$statut = 'en_attente';

// === Calcul total et shop ===
$total = 0.0;
$id_shop = 0;

foreach ($cart_items as $product_id => $item) {
    $qty = (int)($item['quantite'] ?? $item['qty'] ?? 0);
    $price = (float)($item['prix_panier'] ?? $item['price'] ?? $item['prix'] ?? 0);
    $total += $qty * $price;

    if (!empty($item['id_shop'])) {
        $id_shop = (int)$item['id_shop'];
    }
}

if ($total <= 0) {
    file_put_contents($log, date('c') . " ERROR: total=0 for user {$user_id}\n", FILE_APPEND);
    header('Location: cart.php');
    exit;
}

// === Enregistrer la commande ===
try {
    mysqli_begin_transaction($link);

    $sql = "INSERT INTO commandes 
        (id_user, id_shop, total, statut, mode_paiement, adresse_livraison, date_livraison, date_commande, id_artisan, id_livreur)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "iidssssii",
        $user_id, $id_shop, $total, $statut, $mode_paiement,
        $adresse_livraison, $date_livraison, $id_artisan, $id_livreur
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Insert commande failed: " . mysqli_error($link));
    }

    $order_id = mysqli_insert_id($link);

    // Enregistrement des détails si tu veux créer une table commande_details
    $stmt_detail = mysqli_prepare($link, "INSERT INTO commande_details (id_commande, produit_id, quantite, prix_unitaire) VALUES (?, ?, ?, ?)");
    foreach ($cart_items as $product_id => $item) {
        $qty = (int)($item['quantite'] ?? $item['qty'] ?? 0);
        $price = (float)($item['prix_panier'] ?? $item['price'] ?? $item['prix'] ?? 0);
        mysqli_stmt_bind_param($stmt_detail, "iiid", $order_id, $product_id, $qty, $price);
        mysqli_stmt_execute($stmt_detail);
    }
    mysqli_stmt_close($stmt_detail);

    mysqli_commit($link);

    // Vider le panier
    unset($_SESSION['cart']);
    $_SESSION['last_order_id'] = $order_id;
    $_SESSION['last_order_total'] = $total;

    header('Location: order_success.php');
    exit;

} catch (Throwable $e) {
    mysqli_rollback($link);
    file_put_contents($log, date('c') . " ORDER ERROR user={$user_id} err=" . $e->getMessage() . "\n", FILE_APPEND);
    echo "Erreur lors de l'enregistrement de la commande. Veuillez réessayer plus tard.";
    exit;
}
?>
