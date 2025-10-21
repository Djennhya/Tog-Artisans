<!-- process-order.php -->
<?php
require_once __DIR__ . '/admin/config.php'; // s'assure que la config (et session_start) est chargée en premier
include("header.php");
?>
<?php
if (!isset($_SESSION['user_id'])) {
    echo "<div class='container my-5'><p class='text-center text-danger'>Vous devez être connecté pour passer une commande.</p></div>";
    include("footer.php");
    exit;
}
$user_id = $_SESSION['user_id'];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "togartisans";
$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_user = $_POST['nom_user'] ?? '';
    $adresse_user = $_POST['adresse_user'] ?? '';
    $phone_user = $_POST['phone_user'] ?? '';

    // Mettre à jour les informations de l'utilisateur
    $update_sql = "UPDATE users SET nom_user = '$nom_user', adresse_user = '$adresse_user', phone_user = '$phone_user' WHERE id_user = $user_id";
    if (mysqli_query($conn, $update_sql)) {
        echo "<div class='container my-5 text-center'>
                <img src='assets/img/success.jpg' alt='Succès' style='max-width:150px;margin-bottom:15px;'>
                <p class='text-success'>Votre commande a été passée avec succès !</p>
              </div>";
    } else {
        echo "<div class='container my-5 text-center'>
                <img src='assets/img/fail.png' alt='Échec' style='max-width:150px;margin-bottom:15px;'>
                <p class='text-danger'>Erreur lors de la mise à jour des informations : " . htmlspecialchars(mysqli_error($conn)) . "</p>
              </div>";
    }
} else {
    echo "<div class='container my-5 text-center'>
            <img src='assets/images/fail.png' alt='Échec' style='max-width:150px;margin-bottom:15px;'>
            <p class='text-danger'>Méthode de requête invalide.</p>
          </div>";
}
mysqli_close($conn);
include("footer.php");
?>