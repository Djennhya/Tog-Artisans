<?php include("header.php"); ?>
    <!-- BEGIN SLIDER -->
    <div class="page-slider margin-bottom-35">
        <div id="carousel-example-generic" class="carousel slide carousel-slider">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                <li data-target="#carousel-example-generic" data-slide-to="2"></li>
                <li data-target="#carousel-example-generic" data-slide-to="3"></li>
            </ol>

            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                <!-- First slide -->
                <div class="item carousel-item-four active">
                    <div class="container">
                        <div class="carousel-position-four text-center">
                            <h2 class="margin-bottom-20 animate-delay carousel-title-v3 border-bottom-title text-uppercase" data-animation="animated fadeInDown">
                              <br/><span class="color-black">Tog'Artisans</span><br/>
                            </h2>
                            <p class="carousel-subtitle-v2" data-animation="animated fadeInUp">
                                La plateforme de vente en ligne des produits artisanaux du Togo </p>
                        </div>
                    </div>
                </div>

                <!-- Third slide -->
                <div class="item carousel-item-six">
                    <div class="container">
                        <div class="carousel-position-four text-center">
                            <span class="carousel-subtitle-v3 margin-bottom-15" data-animation="animated fadeInDown">
                            </span>
                            <p class="carousel-subtitle-v4" data-animation="animated fadeInDown">
                                Tog'Artisans
                            </p>
                            <p class="carousel-subtitle-v3" data-animation="animated fadeInDown">
                                Soutenez les artisans locaux en achetant leurs produits uniques et faits main. En plus de contribuer à l'économie locale, vous pouvez également bénéficier d'avantages exclusifs tels que des réductions sur les achats futurs ou des produits artisanaux uniques. Merci de votre soutien!
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Fourth slide -->
                <div class="item carousel-item-seven">
                   <div class="center-block">
                        <div class="center-block-wrap">
                            <div class="center-block-body">
                                <h2 class="carousel-title-v1 margin-bottom-20" data-animation="animated fadeInDown">
                                    Tog'Artisans <br/>
                                    <span class="carousel-title-v1-small">Supportez les artisans locaux</span>
                                </h2>
                                <a class="carousel-btn" href="products.php" data-animation="animated fadeInUp">Voir plus</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Controls -->
            <a class="left carousel-control carousel-control-shop" href="#carousel-example-generic" role="button" data-slide="prev">
                <i class="fa fa-angle-left" aria-hidden="true"></i>
            </a>
            <a class="right carousel-control carousel-control-shop" href="#carousel-example-generic" role="button" data-slide="next">
                <i class="fa fa-angle-right" aria-hidden="true"></i>
            </a>
        </div>
    </div>
    <!-- END SLIDER -->

    <div class="main">
      <div class="container">
        <!-- BEGIN SALE PRODUCT & NEW ARRIVALS -->
        <div class="row margin-bottom-40">
          <!-- BEGIN SALE PRODUCT -->
          <div class="col-md-12 sale-product">
            <h2>Nos produits</h2>
            <div class="owl-carousel owl-carousel5">
              <?php
$conn = mysqli_connect("localhost", "root", "", "togartisans");
$sql = "SELECT * FROM products LIMIT 6"; // Limite à 6 produits pour l'accueil
$result = mysqli_query($conn, $sql);
while($products = mysqli_fetch_assoc($result)) {
    echo '<div>
        <div class="product-item">
          <div class="pi-img-wrapper">
            <img src="'.htmlspecialchars($products['img_product']).'" class="img-responsive" alt="'.htmlspecialchars($products['nom_product']).'">
            <div>
              <a href="'.htmlspecialchars($products['img_product']).'" class="btn btn-default fancybox-button">Zoom</a>
              <a href="products_details.php?id_product='.htmlspecialchars($products['id_product']).'" class="btn btn-default fancybox-fast-view">View</a>
            </div>
          </div>
          <h3><a href="products_details.php?id_product='.htmlspecialchars($products['id_product']).'">'.htmlspecialchars($products['nom_product']).'</a></h3>
          <div class="pi-price">'.htmlspecialchars($products['prix_product']).' FCFA</div>
          <a href="add_to_cart_form.php?id_product='.htmlspecialchars($products['id_product']).'" class="btn btn-default add2cart">Panier</a>
        </div>
      </div>';
}
mysqli_close($conn);
?>
            </div>
          </div>
          <!-- END SALE PRODUCT -->
        </div>
        <!-- END SALE PRODUCT & NEW ARRIVALS -->

        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40 ">
          <!-- BEGIN SIDEBAR -->
          <div class="sidebar col-md-3 col-sm-4">
            <?php
$conn = mysqli_connect("localhost", "root", "", "togartisans");
$id_cat = isset($_GET['id_cat']) ? intval($_GET['id_cat']) : 0;
$where = '';
if ($id_cat > 0) {
    $where = "WHERE id_cat = $id_cat";
}
$sql = "SELECT * FROM products $where";
$result = mysqli_query($conn, $sql);
// ... suite du code ...
?>
            <ul class="list-group margin-bottom-25 sidebar-menu">
              <li class="list-group-item clearfix"><a href="products.php?id_cat=1"><i class="fa fa-angle-right"></i>Mode & Accessoires</a></li>
              <li class="list-group-item clearfix"><a href="products.php?id_cat=2"><i class="fa fa-angle-right"></i>Décoration & Maison</a></li>
              <li class="list-group-item clearfix"><a href="products.php?id_cat=3"><i class="fa fa-angle-right"></i>Produits de beauté & Bien-être</a></li>
              <li class="list-group-item clearfix"><a href="products.php?id_cat=4"><i class="fa fa-angle-right"></i>Alimentation artisanale</a></li>
              <li class="list-group-item clearfix"><a href="products.php?id_cat=5"><i class="fa fa-angle-right"></i>Instruments de musique & Culture</a></li>
              <li class="list-group-item clearfix"><a href="products.php?id_cat=6"><i class="fa fa-angle-right"></i>Mobilier & Ameublement</a></li>
              <li class="list-group-item clearfix"><a href="products.php?id_cat=7"><i class="fa fa-angle-right"></i>Artisanat divers & Cadeaux</a></li>
            </ul>
          </div>
          <!-- END SIDEBAR -->
          <!-- BEGIN CONTENT -->
          <div class="col-md-9 col-sm-8">
            <h2>Nos produits en promotion</h2>
            <div class="owl-carousel owl-carousel3">
              <?php
$conn = mysqli_connect("localhost", "root", "", "togartisans");
$today = date('Y-m-d');
$sql = "SELECT p.*, promo.prix_promo 
        FROM products p
        INNER JOIN promotions promo ON p.id_product = promo.id_product
        WHERE promo.date_debut <= '$today' AND promo.date_fin >= '$today'
        LIMIT 4";
$result = mysqli_query($conn, $sql);
while($product = mysqli_fetch_assoc($result)) {
    echo '<div>
        <div class="product-item">
          <div class="pi-img-wrapper">
            <img src="'.htmlspecialchars($product['img_product']).'" class="img-responsive" alt="'.htmlspecialchars($product['nom_product']).'">
            <div>
              <a href="'.htmlspecialchars($product['img_product']).'" class="btn btn-default fancybox-button">Zoom</a>
              <a href="products_details.php?id_product='.htmlspecialchars($product['id_product']).'" class="btn btn-default fancybox-fast-view">View</a>
            </div>
          </div>
          <h3><a href="products_details.php?id_product='.htmlspecialchars($product['id_product']).'">'.htmlspecialchars($product['nom_product']).'</a></h3>
          <div class="pi-price">
            <span style="text-decoration:line-through;color:#888;">'.htmlspecialchars($product['prix_product']).' FCFA</span>
            <span style="color:#e74c3c;font-weight:bold;">'.htmlspecialchars($product['prix_promo']).' FCFA</span>
          </div>
          <a href="add_to_cart_form.php?id_product='.htmlspecialchars($product['id_product']).'" class="btn btn-default add2cart">Panier</a>
        </div>
      </div>';
}
mysqli_close($conn);
?>
            </div>
          </div>
          <!-- END CONTENT -->
        </div>
        <!-- END SIDEBAR & CONTENT -->

        <!-- BEGIN TWO PRODUCTS & PROMO -->
        <div class="row margin-bottom-35 ">
          <!-- BEGIN TWO PRODUCTS -->
          <div class="col-md-6 two-items-bottom-items">
            <h2>Nos meilleurs produits</h2>
            <div class="owl-carousel owl-carousel2">
              <div>
                <div class="product-item">
                  <div class="pi-img-wrapper">
                    <img src="assets/pages/img/products/c1.jpg" class="img-responsive" alt="Berry Lace Dress">
                    <div>
                      <a href="assets/pages/img/products/c1.jpg" class="btn btn-default fancybox-button">Zoom</a>
                      <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                    </div>
                  </div>
                  <h3><a href="shop-item.html">Savon nooir</a></h3>
                  <div class="pi-price">1500 FCFA</div>
                  <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                </div>
              </div>
              <div>
                <div class="product-item">
                  <div class="pi-img-wrapper">
                    <img src="assets/pages/img/products/d2.jpg" class="img-responsive" alt="Berry Lace Dress">
                    <div>
                      <a href="assets/pages/img/products/d2.jpg" class="btn btn-default fancybox-button">Zoom</a>
                      <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                    </div>
                  </div>
                  <h3><a href="shop-item.html">Sac détente</a></h3>
                  <div class="pi-price">5000 FCFA</div>
                  <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                </div>
              </div>
              <div>
                <div class="product-item">
                  <div class="pi-img-wrapper">
                    <img src="assets/pages/img/products/k3.jpg" class="img-responsive" alt="Berry Lace Dress">
                    <div>
                      <a href="assets/pages/img/products/k3.jpg" class="btn btn-default fancybox-button">Zoom</a>
                      <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                    </div>
                  </div>
                  <h3><a href="shop-item.html">Berry Lace Dress</a></h3>
                  <div class="pi-price">$29.00</div>
                  <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                </div>
              </div>
              <div>
                <div class="product-item">
                  <div class="pi-img-wrapper">
                    <img src="assets/pages/img/products/k1.jpg" class="img-responsive" alt="Berry Lace Dress">
                    <div>
                      <a href="assets/pages/img/products/k1.jpg" class="btn btn-default fancybox-button">Zoom</a>
                      <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                    </div>
                  </div>
                  <h3><a href="shop-item.html">Berry Lace Dress</a></h3>
                  <div class="pi-price">$29.00</div>
                  <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                </div>
              </div>
              <div>
                <div class="product-item">
                  <div class="pi-img-wrapper">
                    <img src="assets/pages/img/products/k4.jpg" class="img-responsive" alt="Berry Lace Dress">
                    <div>
                      <a href="assets/pages/img/products/k4.jpg" class="btn btn-default fancybox-button">Zoom</a>
                      <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                    </div>
                  </div>
                  <h3><a href="shop-item.html">Berry Lace Dress</a></h3>
                  <div class="pi-price">$29.00</div>
                  <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                </div>
              </div>
              <div>
                <div class="product-item">
                  <div class="pi-img-wrapper">
                    <img src="assets/pages/img/products/k3.jpg" class="img-responsive" alt="Berry Lace Dress">
                    <div>
                      <a href="assets/pages/img/products/k3.jpg" class="btn btn-default fancybox-button">Zoom</a>
                      <a href="#product-pop-up" class="btn btn-default fancybox-fast-view">View</a>
                    </div>
                  </div>
                  <h3><a href="shop-item.html">Berry Lace Dress</a></h3>
                  <div class="pi-price">$29.00</div>
                  <a href="javascript:;" class="btn btn-default add2cart">Add to cart</a>
                </div>
              </div>
            </div>
          </div>
          <!-- END TWO PRODUCTS -->
          <!-- BEGIN PROMO -->
          <div class="col-md-6 shop-index-carousel">
            <div class="content-slider">
              <div id="myCarousel" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                  <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                  <li data-target="#myCarousel" data-slide-to="1"></li>
                  <li data-target="#myCarousel" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                  <div class="item active">
                    <img src="assets/pages/img/index-sliders/t4.jpg" class="img-responsive" alt="Berry Lace Dress">
                  </div>
                  <div class="item">
                    <img src="assets/pages/img/index-sliders/t7.jpg" class="img-responsive" alt="Berry Lace Dress">
                  </div>
                  <div class="item">
                    <img src="assets/pages/img/index-sliders/t6.jpg" class="img-responsive" alt="Berry Lace Dress">
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- END PROMO -->
        </div>        
        <!-- END TWO PRODUCTS & PROMO -->
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
<?php include("footer.php"); ?>

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
                  <h2>Cool green dress with red bell</h2>
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
                    <p>Lorem ipsum dolor ut sit ame dolore  adipiscing elit, sed nonumy nibh sed euismod laoreet dolore magna aliquarm erat volutpat Nostrud duis molestie at dolore.</p>
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
                        <input id="product-quantity" type="text" value="1" readonly name="product-quantity" class="form-control input-sm">
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

    <script src="assets/corporate/scripts/layout.js" type="text/javascript"></script>
    <script src="assets/pages/scripts/bs-carousel.js" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            Layout.init();    
            Layout.initOWL();
            Layout.initImageZoom();
            Layout.initTouchspin();
            Layout.initTwitter();
        });
    </script>
    <!-- END PAGE LEVEL JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>