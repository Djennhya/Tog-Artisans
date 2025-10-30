<!-- Suppression d'un artisan -->
<?php include("header.php"); ?>
<?php include("sidebar.php"); ?>
<?php
// Inclure le fichier de configuration
require_once "config.php";
// Vérifier si l'ID de l'artisan est passé en paramètre
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $artisan_id = intval($_GET['id']);
    // Préparer la requête de suppression
    $sql = "DELETE FROM artisans WHERE id_artisan = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $artisan_id);
        if (mysqli_stmt_execute($stmt)) {
            // Rediriger vers la page des artisans avec un message de succès
            echo "<script>alert('Artisan supprimé avec succès.'); window.location.href='artisans.php';</script>";
            exit();
        } else {
            echo "<script>alert('Erreur lors de la suppression de l\'artisan.'); window.location.href='artisans.php';</script>";
            exit();
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>alert('Erreur lors de la préparation de la requête.'); window.location.href='artisans.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('ID d\'artisan invalide.'); window.location.href='artisans.php';</script>";
    exit();
}
mysqli_close($link);
?>
<?php include("footer.php"); ?>