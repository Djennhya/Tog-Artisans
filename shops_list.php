<!-- la liste des boutiques -->
<?php
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "togartisans";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}
require_once 'admin/config.php';
$shop_sql = "SELECT * FROM shop ORDER BY nom_shop";
$shop_res = mysqli_query($conn, $shop_sql);
?>
<?php include("header.php"); ?>
<div class="container my-5">
  <h2>Nos Boutiques</h2>
  <?php if ($shop_res && mysqli_num_rows($shop_res) > 0): ?>
  <div class="row">
    <?php while ($shop = mysqli_fetch_assoc($shop_res)): ?>
      <div class="col-12 col-sm-6 col-md-4 mb-4 d-flex"> 
        <div class="card shadow-sm w-100 h-100 d-flex flex-column">
            <h5 class="card-title"><?php echo htmlspecialchars($shop['nom_shop']); ?></h5>
          <?php if (!empty($shop['img_shop'])): ?>
            <img src="<?php echo htmlspecialchars($shop['img_shop']); ?>"
                 alt="Logo de <?php echo htmlspecialchars($shop['nom_shop']); ?>"
                 class="card-img-top"
                 style="height:220px; object-fit:cover;">
          <?php endif; ?>
          <div class="card-body d-flex flex-column">
            <p class="card-text" style="flex:1;"><?php echo nl2br(htmlspecialchars($shop['description_shop'] ?? '')); ?></p>
            <div class="mt-3">
              <a href="shop.php?id_shop=<?php echo (int)$shop['id_shop']; ?>" class="btn btn-primary btn-sm">Voir la boutique</a>
            </div>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
  <?php else: ?>
    <p class="text-center">Aucune boutique disponible pour le moment.</p>
    <?php endif; ?>
</div>
<?php
mysqli_close($conn);
include("footer.php");;
?>