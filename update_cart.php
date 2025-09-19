<?php
session_start();
$id_product = isset($_POST['id_product']) ? intval($_POST['id_product']) : 0;
$action = isset($_POST['action']) ? $_POST['action'] : '';
if ($id_product > 0 && isset($_SESSION['cart'][$id_product])) {
    if ($action == 'increment') {
        $_SESSION['cart'][$id_product]++;
    } elseif ($action == 'decrement' && $_SESSION['cart'][$id_product] > 1) {
        $_SESSION['cart'][$id_product]--;
    } elseif ($action == 'delete') {
        unset($_SESSION['cart'][$id_product]);
    }
}
header('Location: cart.php');
exit;