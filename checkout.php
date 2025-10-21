<!-- page de valider la commande -->


<?php include("admin/config.php"); ?>
<?php
include("header.php");
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
    <h2>Valider la Commande</h2>
    <div class="card p-4 shadow-sm">
        <h4>Informations de Livraison</h4>
        <form action="process_order.php" method="post">
            <div class="form-group">
                <label for="nom_user">Nom Complet</label>
                <input type="text" class="form-control" id="nom_user" name="nom_user" value="<?php echo htmlspecialchars($user['nom_user']); ?>" required>
            </div>
            <div class="form-group">
                <label for="adresse_user">Adresse de Livraison</label>
                <input type="text" class="form-control" id="adresse_user" name="adresse_user" value="<?php echo htmlspecialchars($user['adresse_user']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_user">Téléphone</label>
                <input type="text" class="form-control" id="phone_user" name="phone_user" value="<?php echo htmlspecialchars($user['phone_user']); ?>" required>
            </div>
            <button type="submit" class="btn btn-success mt-3">Confirmer la Commande</button>
        </form>
    </div>
</div>
<?php
mysqli_close($conn);
include("footer.php");
?>