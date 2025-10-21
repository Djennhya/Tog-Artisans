<?php
require_once 'admin/config.php';
include("header.php");

// Vérifier si l'id_shop est passé dans l'URL
if (!isset($_GET['id_shop']) || !is_numeric($_GET['id_shop'])) {
    echo "<div class='container my-5'><p class='text-center text-danger'>Boutique introuvable.</p></div>";
    include("footer.php");
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "togartisans";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}
$id_shop = (int) $_GET['id_shop'];

// Récupérer les infos de la boutique
$shop_sql = "SELECT * FROM shop WHERE id_shop = $id_shop";
$shop_res = mysqli_query($conn, $shop_sql);
$shop = mysqli_fetch_assoc($shop_res);

if (!$shop) {
    echo "<div class='container my-5'><p class='text-center text-danger'>Cette boutique n'existe pas.</p></div>";
    include("footer.php");
    exit;
}

// Récupérer les produits liés à cette boutique
$product_sql = "SELECT * FROM products WHERE id_shop = $id_shop ORDER BY nom_product";
$product_res = mysqli_query($conn, $product_sql);
?>
<!--lister les produits de la boutique de maniere horizontale en ajoutant le nom et l'image de la boutique en haut et le bouton panier-->

<div class="container my-5">
  <div class="text-center mb-4">
    <h2><?php echo htmlspecialchars($shop['nom_shop']); ?></h2>
    <?php if (!empty($shop['img_shop'])): ?>
      <img src="<?php echo htmlspecialchars($shop['img_shop']); ?>" 
           alt="Image de <?php echo htmlspecialchars($shop['nom_shop']); ?>" 
           style="width:180px; height:180px; object-fit:cover; border-radius:10px;">
    <?php endif; ?>
    <p class="mt-3 text-muted"><?php echo htmlspecialchars($shop['description_shop'] ?? ''); ?></p>
  </div>

  <?php if ($product_res && mysqli_num_rows($product_res) > 0): ?>
    <div class="row">
      <?php while ($product = mysqli_fetch_assoc($product_res)): ?>
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 d-flex">
          <div class="card h-100 w-100 shadow-sm d-flex flex-column">
            <?php if (!empty($product['img_product'])): ?>
              <img src="<?php echo htmlspecialchars($product['img_product']); ?>"
                   alt="<?php echo htmlspecialchars($product['nom_product']); ?>"
                   class="card-img-top"
                   style="height:180px; object-fit:cover;">
            <?php endif; ?>
            <div class="card-body d-flex flex-column">
              <h5 class="card-title"><?php echo htmlspecialchars($product['nom_product']); ?></h5>
              <p class="text-muted mb-2"><?php echo number_format($product['prix_product'], 0, ',', ' ') . ' FCFA'; ?></p>
              <p class="small text-truncate mb-3" style="flex:1;"><?php echo htmlspecialchars($product['description_product']); ?></p>
              <div class="mt-2 d-flex justify-content-between">
                <a href="products_details.php?id_product=<?php echo (int)$product['id_product']; ?>" class="btn btn-sm btn-outline-secondary">Voir</a>
                <a href="add_to_cart_form.php?id_product=<?php echo (int)$product['id_product']; ?>" class="btn btn-sm btn-primary">Panier</a>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p class="text-center">Aucun produit disponible pour cette boutique.</p>
  <?php endif; ?>
</div>

<?php
mysqli_close($conn);
include("footer.php");
?>
