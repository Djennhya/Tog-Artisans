<!-- process-order.php -->
<?php
require_once __DIR__ . '/admin/config.php';
// Vérifs basiques
if (!isset($_SESSION['user_id'])) {
    die('Vous devez être connecté.');
}
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    die('Panier vide.');
}

$user_id = (int) $_SESSION['user_id'];
$frais_livraison = 500; // ou calcul dynamique

// Connexion (adapte si tu utilises config.php pour ça)
$conn = mysqli_connect('localhost', 'root', '', 'togartisans');
if (!$conn) {
    error_log('DB connect error: ' . mysqli_connect_error());
    die('Erreur DB.');
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    mysqli_begin_transaction($conn);

    // Calcul total à partir du panier (utiliser prix stocké en session si existant)
    $total_general = 0;
    foreach ($_SESSION['cart'] as $id_product => $item) {
        if (is_int($item) || !is_array($item)) {
            $quantity = (int)$item;
            // si pas de prix stocké, récupérer le prix en DB
            $prix_unitaire = 0;
            $q = mysqli_query($conn, "SELECT prix_product FROM products WHERE id_product = " . (int)$id_product);
            if ($r = mysqli_fetch_assoc($q)) $prix_unitaire = (float)$r['prix_product'];
        } else {
            $quantity = (int)$item['quantity'];
            $prix_unitaire = (float)($item['prix'] ?? 0);
            if ($prix_unitaire <= 0) {
                $q = mysqli_query($conn, "SELECT prix_product FROM products WHERE id_product = " . (int)$id_product);
                if ($r = mysqli_fetch_assoc($q)) $prix_unitaire = (float)$r['prix_product'];
            }
        }
        $total_general += $quantity * $prix_unitaire;
    }

    // récupération mode paiement / adresse (provenir du formulaire checkout)
    $mode_paiement = isset($_POST['mode_paiement']) ? mysqli_real_escape_string($conn, $_POST['mode_paiement']) : 'inconnu';
    $adresse_livraison = isset($_POST['adresse_livraison']) ? mysqli_real_escape_string($conn, $_POST['adresse_livraison']) : '';

    // si adresse manquante, tenter de la prendre depuis le profil user
    if (empty($adresse_livraison)) {
        $q = mysqli_query($conn, "SELECT adresse_user FROM users WHERE id_user = $user_id LIMIT 1");
        if ($r = mysqli_fetch_assoc($q)) {
            $adresse_livraison = $r['adresse_user'] ?? '';
        }
    }

    $statut = 'pending';

    // Insert commande (adapté à ta table commandes)
    $stmt = $conn->prepare("INSERT INTO commandes (id_user, total, statut, mode_paiement, adresse_livraison, date_commande) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param('idsss', $user_id, $total_general, $statut, $mode_paiement, $adresse_livraison);
    $stmt->execute();
    $order_id = $conn->insert_id;
    $stmt->close();

    // vider panier
    unset($_SESSION['cart']);

    // affichage succès (ou redirection)
    header('Location: order_success.php?order_id=' . $order_id);
    exit;
} catch (Exception $e) {
    mysqli_rollback($conn);
    error_log("Order error: " . $e->getMessage());
    // Afficher message d'erreur pour debug (supprimer en prod)
    echo "<div class='container text-center'><img src='assets/images/fail.png' alt='Échec'><p>Erreur: " . htmlspecialchars($e->getMessage()) . "</p></div>";
    exit;
}
?>