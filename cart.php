<?php
session_start();
?>
<?php include("header.php"); ?>
<!-- Body BEGIN -->
<body class="ecommerce">
    <!-- BEGIN TOP BAR -->

    <div class="main">
      <div class="container">
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
          <!-- BEGIN CONTENT -->
          <div class="col-md-12 col-sm-12">
            <h1>Mon panier</h1>
            <div class="goods-page">
              <div class="goods-data clearfix">
                <div class="table-wrapper-responsive">
                <table summary="Shopping cart">
                  <tr>
                    <th class="goods-page-image">Image product</th>
                    <th class="goods-page-name">Nom produit</th>
                    <th class="goods-page-quantity">Qté</th>
                    <th class="goods-page-category">Catégorie</th>
                    <th class="goods-page-delete">Supprimer</th>
                    <th class="goods-page-price">Prix</th>
                    <th class="goods-page-total" colspan="2">Total</th>
                  </tr>

<?php
$total_general = 0;
$frais_livraison = 500; // FCFA
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "Votre panier est vide.";
} else {
    $conn = mysqli_connect("localhost", "root", "", "togartisans");
    foreach ($_SESSION['cart'] as $id_product => $item) {
        // Correction : si $item est un int, on le convertit en tableau
        if (!is_array($item)) {
            $item = [
                'quantity' => $item,
                'prix' => 0 // Tu peux ici aller chercher le prix du produit si besoin
            ];
            $_SESSION['cart'][$id_product] = $item;
        }
        $quantity = $item['quantity'];
        $prix = $item['prix'];
        $sql = "SELECT p.*, c.nom_cat AS nom_cat FROM products p LEFT JOIN category c ON p.id_cat = c.id_cat WHERE p.id_product = $id_product";
        $result = mysqli_query($conn, $sql);
        if ($product = mysqli_fetch_assoc($result)) {
            $total = $prix * $quantity;
            $total_general += $total;
echo "<tr>
    <td class='goods-page-image'>
        <img src='".htmlspecialchars($product['img_product'])."' style='max-width:80px;'>
    </td>
    <td class='goods-page-name'>".htmlspecialchars($product['nom_product'])."</td>
    <td class='goods-page-quantity'>
      <form action=\"update_cart.php\" method=\"post\" style=\"display:inline-flex;\">
      <input type=\"hidden\" name=\"id_product\" value=\"".htmlspecialchars($product['id_product'])."\">
      <button type=\"submit\" name=\"action\" value=\"decrement\" class=\"btn btn-xs btn-default\">-</button>
      <input type=\"text\" name=\"quantity\" value=\"".htmlspecialchars($quantity)."\" style=\"width:40px;text-align:center;\" readonly>
      <button type=\"submit\" name=\"action\" value=\"increment\" class=\"btn btn-xs btn-default\">+</button>
      </form>
    </td>
    <td class='goods-page-category'>".htmlspecialchars($product['nom_cat'])."</td>
     <td>
        <form action=\"update_cart.php\" method=\"post\" style=\"display:inline;\">
        <input type=\"hidden\" name=\"id_product\" value=\"".htmlspecialchars($product['id_product'])."\">
        <button type=\"submit\" name=\"action\" value=\"delete\" class=\"btn btn-xs btn-danger\" title=\"Supprimer\">
        <i class=\"fa fa-trash\"></i>
        </button>
        </form>
    </td>
    <td class='goods-page-price'>".htmlspecialchars($prix)." FCFA</td>
    <td class='goods-page-total'>".number_format($total, 0, '', ' ')." FCFA</td>
   
</tr>";
        }
    }
    mysqli_close($conn);
}
?>

                  <!-- Example of a product in the cart -->
                  <!-- Récupération des produits d'une maniere dynamique -->
                </table>
                </div>

                <div class="shopping-total">
                  <ul>
                    <li>
                    <em>Frais de taxe</em>
                    <strong class="price"><span><?php echo number_format($frais_livraison, 0, '', ' '); ?></span> FCFA</strong>
                    </li>
                    <li class="shopping-total-price">
                    <em>Total</em>
                    <strong class="price"><span><?php echo number_format($total_general + $frais_livraison, 0, '', ' '); ?></span> FCFA</strong>
                    </li>
                  </ul>
                </div>
              </div>
              <button class="btn btn-default" type="button" onclick="window.history.back();">
                Continuer <i class="fa fa-shopping-cart"></i>
              </button>
<?php if (!isset($_SESSION['user_id'])): ?>
    <a href="login.php" class="btn btn-primary">Commander <i class="fa fa-check"></i></a>
<?php else: ?>
    <form action="valider_commande.php" method="post" style="display:inline;">
        <button class="btn btn-primary" type="submit">Commander <i class="fa fa-check"></i></button>
    </form>
<?php endif; ?>
            </div>
          </div>
          <!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR & CONTENT -->
      </div>
    </div>

    <!-- BEGIN STEPS -->
    <div class="steps-block steps-block-red">
      <div class="container">
        <div class="row">
          <div class="col-md-4 steps-block-col">
            <i class="fa fa-truck"></i>
            <div>
              <h2>Livraison rapide</h2>
              <em>Livraison rapide partout</em>
            </div>
            <span>&nbsp;</span>
          </div>
          <div class="col-md-4 steps-block-col">
            <i class="fa fa-gift"></i>
            <div>
              <h2>Dons & Cadeaux</h2>
              <em>Supportez les artisans locaux</em>
            </div>
            <span>&nbsp;</span>
          </div>
          <div class="col-md-4 steps-block-col">
            <i class="fa fa-phone"></i>
            <div>
              <h2>Disponible 24h/24</h2>
              <em>Support en ligne</em>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END STEPS -->

    <!-- BEGIN PRE-FOOTER -->
<?php include("footer.php"); ?>
    <!-- END PRE-FOOTER -->

    <!-- BEGIN fast view of a product -->
    <div id="product-pop-up" style="display: none; width: 700px;">
            <div class="product-page product-pop-up">
              <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-3">
                  <div class="product-main-image">
                    <img src="assets/pages/img/products/model7.jpg" alt="Cool green dress with red bell" class="img-responsive">
                  </div>
                  <div class="product-other-images">
                    <a href="javascript:;" class="active"><img alt="Berry Lace Dress" src="assets/pages/img/products/model3.jpg"></a>
                    <a href="javascript:;"><img alt="Berry Lace Dress" src="assets/pages/img/products/model4.jpg"></a>
                    <a href="javascript:;"><img alt="Berry Lace Dress" src="assets/pages/img/products/model5.jpg"></a>
                  </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-9">
                  <h1>Cool green dress with red bell</h1>
                  <div class="price-availability-block clearfix">
                    <div class="price">
                      <strong><span>$</span>47.00</strong>
                      <em>$<span>62.00</span></em>
                    </div>
                    <div class="availability">
                      Availability: <strong>In Stock</strong>
                    </div>
                  </div>
                  <div class="description">
                    <p>Lorem ipsum dolor ut sit ame dolore  adipiscing elit, sed nonumy nibh sed euismod laoreet dolore magna aliquarm erat volutpat 
Nostrud duis molestie at dolore.</p>
                  </div>
                  <div class="product-page-options">
                    <div class="pull-left">
                      <label class="control-label">Size:</label>
                      <select class="form-control input-sm">
                        <option>L</option>
                        <option>M</option>
                        <option>XL</option>
                      </select>
                    </div>
                    <div class="pull-left">
                      <label class="control-label">Color:</label>
                      <select class="form-control input-sm">
                        <option>Red</option>
                        <option>Blue</option>
                        <option>Black</option>
                      </select>
                    </div>
                  </div>
                  <div class="product-page-cart">
                    <div class="product-quantity">
                        <input id="product-quantity3" type="text" value="1" readonly class="form-control input-sm">
                    </div>
                    <button class="btn btn-primary" type="submit">Add to cart</button>
                    <a href="shop-item.html" class="btn btn-default">More details</a>
                  </div>
                </div>

                <div class="sticker sticker-sale"></div>
              </div>
            </div>
    </div>
    <!-- END fast view of a product -->

    <!-- Load javascripts at bottom, this will reduce page load time -->
    <!-- BEGIN CORE PLUGINS (REQUIRED FOR ALL PAGES) -->
    <!--[if lt IE 9]>
    <script src="assets/plugins/respond.min.js"></script>  
    <![endif]-->  
    <script src="assets/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-migrate.min.js" type="text/javascript"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>      
    <script src="assets/corporate/scripts/back-to-top.js" type="text/javascript"></script>
    <script src="assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->

    <!-- BEGIN PAGE LEVEL JAVASCRIPTS (REQUIRED ONLY FOR CURRENT PAGE) -->
    <script src="assets/plugins/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script><!-- pop up -->
    <script src="assets/plugins/owl.carousel/owl.carousel.min.js" type="text/javascript"></script><!-- slider for products -->
    <script src='assets/plugins/zoom/jquery.zoom.min.js' type="text/javascript"></script><!-- product zoom -->
    <script src="assets/plugins/bootstrap-touchspin/bootstrap.touchspin.js" type="text/javascript"></script><!-- Quantity -->
    <script src="assets/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script>
    <script src="assets/plugins/rateit/src/jquery.rateit.js" type="text/javascript"></script>
    <script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js" type="text/javascript"></script><!-- for slider-range -->

    <script src="assets/corporate/scripts/layout.js" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            Layout.init();    
            Layout.initOWL();
            Layout.initTwitter();
            Layout.initImageZoom();
            Layout.initTouchspin();
            Layout.initUniform();
            Layout.initSliderRange();
        });
    </script>
    <!-- END PAGE LEVEL JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>