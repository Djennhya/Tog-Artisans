<!-- mon profil utilisateur -->
<?php
require_once "admin/config.php";
include("header.php");
if (!isset($_SESSION['user_id'])) {
    echo "<div class='container my-5'><p class='text-center text-danger'>Vous devez être connecté pour voir votre profil.</p></div>";
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
// Récupérer les informations de l'utilisateur
$user_sql = "SELECT * FROM users WHERE id_user = $user_id";
$user_res = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_res);
if (!$user) {
    echo "<div class='container my-5'><p class='text-center text-danger'>Utilisateur introuvable.</p></div>";
    include("footer.php");
    exit;
}
?>
<div class="container my-5">
    <h2>Mon Profil</h2>
    <div class="card p-4 shadow-sm">
        <h4>Informations Personnelles</h4>
        <p><strong>Nom:</strong> <?php echo htmlspecialchars($user['nom_user']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email_user']); ?></p>
        <p><strong>Adresse:</strong> <?php echo htmlspecialchars($user['adresse_user']); ?></p>
        <p><strong>Téléphone:</strong> <?php echo htmlspecialchars($user['phone_user']); ?></p>
    </div>
</div>
<?php
mysqli_close($conn);
include("footer.php");
?>
