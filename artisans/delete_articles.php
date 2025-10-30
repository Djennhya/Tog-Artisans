<!-- Suppression d'un produit -->
<?php include("header.php"); ?>
<?php include("sidebar.php"); ?>
<?php
// Inclure le fichier de configuration
require_once "config.php";
// Vérifier si l'ID du produit est passé en paramètre
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = intval($_GET['id']);
    // Préparer la requête de suppression
    $sql = "DELETE FROM products WHERE id_product = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        if (mysqli_stmt_execute($stmt)) {
            // Rediriger vers la page des articles avec un message de succès
            echo "<script>alert('Produit supprimé avec succès.'); window.location.href='articles.php';</script>";
            exit();
        } else {
            echo "<script>alert('Erreur lors de la suppression du produit.'); window.location.href='articles.php';</script>";
            exit();
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Erreur lors de la préparation de la requête.'); window.location.href='articles.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID de produit invalide.'); window.location.href='articles.php';</script>";
    exit();
}
mysqli_close($link);
?>
<?php include("footer.php"); ?>
