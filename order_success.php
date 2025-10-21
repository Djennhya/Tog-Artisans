<!-- order success.php -->
<?php
require_once __DIR__ . '/admin/config.php';
include("header.php");
if (!isset($_SESSION['user_id'])) {
    echo "<div class='container my-5'><p class='text-center text-danger'>Vous devez être connecté pour voir cette page.</p></div>";
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
// Récupérer la dernière commande de l'utilisateur
$order_sql = "SELECT * FROM commandes WHERE id_user = $user_id ORDER BY date_commande DESC LIMIT 1";
$order_res = mysqli_query($conn, $order_sql);
$order = mysqli_fetch_assoc($order_res);
if (!$order) {
    echo "<div class='container my-5'><p class='text-center text-danger'>Aucune commande trouvée.</p></div>";
    include("footer.php");
    exit;
}
?>
<div class="container my-5">
    <div class="alert alert-success text-center">
        <h2>Merci pour votre commande !</h2>
        <p>Votre commande numéro <strong><?php echo (int)$order['id_commande']; ?></strong> a été passée avec succès le <strong><?php echo htmlspecialchars($order['date_commande']); ?></strong>.</p>
        <p>Montant total : <strong><?php echo number_format($order['total'], 2); ?> €</strong></p>
        <a href="shops_list.php" class="btn btn-primary mt-3">Retour aux boutiques</a>
    </div>
</div>
<?php
mysqli_close($conn);
include("footer.php");
?>