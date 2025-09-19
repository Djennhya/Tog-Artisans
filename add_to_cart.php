<?php
session_start();
$id_product = isset($_POST['id_product']) ? intval($_POST['id_product']) : 0;
$quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
$prix_panier = isset($_POST['prix_panier']) ? floatval($_POST['prix_panier']) : 0;

if ($id_product > 0) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    // Conversion si ancien format (nombre seul)
    if (isset($_SESSION['cart'][$id_product]) && !is_array($_SESSION['cart'][$id_product])) {
        $_SESSION['cart'][$id_product] = [
            'quantity' => $_SESSION['cart'][$id_product],
            'prix' => $prix_panier
        ];
    }
    if (isset($_SESSION['cart'][$id_product])) {
        $_SESSION['cart'][$id_product]['quantity'] += $quantity;
        // On garde le prix promo si déjà présent, sinon on met à jour
        $_SESSION['cart'][$id_product]['prix'] = $prix_panier;
    } else {
        $_SESSION['cart'][$id_product] = [
            'quantity' => $quantity,
            'prix' => $prix_panier
        ];
    }
}
header('Location: cart.php');
exit;