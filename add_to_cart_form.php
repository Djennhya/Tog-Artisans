<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "togartisans");
$id_product = isset($_GET['id_product']) ? intval($_GET['id_product']) : 0;
$product = null;
if ($id_product > 0) {
    $sql = "SELECT p.*, c.nom_cat AS nom_cat FROM products p LEFT JOIN category c ON p.id_cat = c.id_cat WHERE p.id_product = $id_product";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);
}
mysqli_close($conn);

if (!$product) {
    echo "Produit introuvable.";
    exit;
}

// Après avoir récupéré $product
$today = date('Y-m-d');
$conn = mysqli_connect("localhost", "root", "", "togartisans");
$sql_promo = "SELECT prix_promo FROM promotions WHERE id_product = {$product['id_product']} AND date_debut <= '$today' AND date_fin >= '$today' LIMIT 1";
$res_promo = mysqli_query($conn, $sql_promo);
$promo = mysqli_fetch_assoc($res_promo);
$prix_panier = $promo ? $promo['prix_promo'] : $product['prix_product'];
?>


<?php include("header.php"); ?>

<div class="container">
    <h2>L'aperçu du produit</h2>
    <div class="goods-page">
        <div class="goods-data clearfix">
            <div class="table-wrapper-responsive">
                <form action="add_to_cart.php" method="post">
                    <table summary="Shopping cart">
                        <tr>
                            <th class="goods-page-image">Image produit</th>
                            <th class="goods-page-name">Nom produit</th>
                            <th class="goods-page-quantity">Quantité</th>
                            <th class="goods-page-price">Prix</th>
                            <th class="goods-page-total">Total</th>
                        </tr>
                        <tr>
                            <td class="goods-page-image">
                                <img src="<?php echo htmlspecialchars($product['img_product']); ?>" style="max-width:80px;">
                            </td>
                            <td class="goods-page-name"><?php echo htmlspecialchars($product['nom_product']); ?></td>
                            <td class="goods-page-quantity">
                                <input type="hidden" name="id_product" value="<?php echo $product['id_product']; ?>">
                                <input type="hidden" name="prix_panier" value="<?php echo $prix_panier; ?>">
                                <input type="number" name="quantity" value="1" min="1" max="99" style="width:60px;" id="qte">
                            </td>
                            <td class="goods-page-price" id="prix"><?php echo htmlspecialchars($prix_panier); ?> FCFA</td>
                            <td class="goods-page-total" id="total"><?php echo htmlspecialchars($prix_panier); ?> FCFA</td>
                        </tr>
                    </table>
                    <button type="submit" class="btn btn-primary" style="margin-top:15px;">Ajouter au panier</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.getElementById('qte').addEventListener('input', function() {
    var prix = <?php echo (int)$prix_panier; ?>;
    var qte = this.value;
    document.getElementById('total').innerText = (prix * qte) + " FCFA";
});
</script>

<?php include("footer.php"); ?>