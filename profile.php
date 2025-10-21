<!-- mon profil utilisateur et modification et mes historiques de commandes -->
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
    <h1>Mon Profil</h1>
    <div class="card p-4 shadow-sm">
        <h1>Informations Personnelles</h1>
        <form action="process_profile.php" method="post">
            <div class="form-group">
                <label for="nom_user">Nom Complet</label>
                <input type="text" class="form-control" id="nom_user" name="nom_user" value="<?php echo htmlspecialchars($user['nom_user']); ?>" required>
            </div>
            <div class="form-group">
                <label for="adresse_user">Adresse</label>
                <input type="text" class="form-control" id="adresse_user" name="adresse_user" value="<?php echo htmlspecialchars($user['adresse_user']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone_user">Téléphone</label>
                <input type="text" class="form-control" id="phone_user" name="phone_user" value="<?php echo htmlspecialchars($user['phone_user']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Mettre à Jour</button>
        </form>
    </div>
    <div class="mt-5">
        <h1>Historique des Commandes</h1>
        <?php
        // Récupérer les commandes de l'utilisateur
        $order_sql = "SELECT * FROM commandes WHERE id_user = $user_id ORDER BY date_commande DESC";
        $order_res = mysqli_query($conn, $order_sql);
        if ($order_res && mysqli_num_rows($order_res) > 0):
        ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Commande</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = mysqli_fetch_assoc($order_res)): ?>
                    <tr>
                        <td><?php echo (int)$order['id_commande']; ?></td>
                        <td><?php echo htmlspecialchars($order['date_commande']); ?></td>
                        <td><?php echo htmlspecialchars($order['total']); ?> €</td>
                        <td><?php echo htmlspecialchars($order['statut']); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Aucune commande passée pour le moment.</p>
        <?php endif; ?>
    </div>
</div>
<?php
mysqli_close($conn);
include("footer.php");
?>