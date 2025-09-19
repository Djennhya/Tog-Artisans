<?php include 'header.php'; ?>

<!-- Head BEGIN -->
<head>
  <meta charset="utf-8">
  <title>Men category | Metronic Shop UI</title>

  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

  <meta content="Metronic Shop UI description" name="description">
  <meta content="Metronic Shop UI keywords" name="keywords">
  <meta content="keenthemes" name="author">

  <meta property="og:site_name" content="-CUSTOMER VALUE-">
  <meta property="og:title" content="-CUSTOMER VALUE-">
  <meta property="og:description" content="-CUSTOMER VALUE-">
  <meta property="og:type" content="website">
  <meta property="og:image" content="-CUSTOMER VALUE-"><!-- link to image for socio -->
  <meta property="og:url" content="-CUSTOMER VALUE-">

  <link rel="shortcut icon" href="favicon.ico">

  <!-- Fonts START -->
  <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|PT+Sans+Narrow|Source+Sans+Pro:200,300,400,600,700,900&amp;subset=all" rel="stylesheet" type="text/css"> 
  <!-- Fonts END -->

  <!-- Global styles START -->          
  <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Global styles END --> 
   
  <!-- Page level plugin styles START -->
  <link href="assets/plugins/fancybox/source/jquery.fancybox.css" rel="stylesheet">
  <link href="assets/plugins/owl.carousel/assets/owl.carousel.css" rel="stylesheet">
  <link href="assets/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css">
  <link href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css"><!-- for slider-range -->
  <link href="assets/plugins/rateit/src/rateit.css" rel="stylesheet" type="text/css">
  <!-- Page level plugin styles END -->

  <!-- Theme styles START -->
  <link href="assets/pages/css/components.css" rel="stylesheet">
  <link href="assets/corporate/css/style.css" rel="stylesheet">
  <link href="assets/pages/css/style-shop.css" rel="stylesheet" type="text/css">
  <link href="assets/corporate/css/style-responsive.css" rel="stylesheet">
  <link href="assets/corporate/css/themes/red.css" rel="stylesheet" id="style-color">
  <link href="assets/corporate/css/custom.css" rel="stylesheet">
  <!-- Theme styles END -->
</head>
<!-- Head END -->

<!-- Body BEGIN -->
<body class="ecommerce">

 <div class="title-wrapper">
      <div class="container"><div class="container-inner">
        <h1><span>Nos</span>Produits</h1>
        <em>Découvrez nos produits artisanaux</em>
      </div></div>
    </div>
    <div class="main">
      <div class="container">
        <ul class="breadcrumb">
            <li><a href="index.html">Accueil</a></li>
            <li><a href="">Boutique</a></li>
            <li class="active">Nos produits</li>
        </ul>
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
          <!-- BEGIN SIDEBAR -->
          <div class="sidebar col-md-3 col-sm-5">
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
              <li class="list-group-item clearfix"><a href="products.php?id_cat=6"><i class="fa fa-angle-right"></i>Artisanat divers & Cadeaux</a></li>
            </ul>

          </div>
          <!-- END SIDEBAR -->
          <!-- BEGIN CONTENT -->
          <div class="col-md-9 col-sm-7">
            <div class="row list-view-sorting clearfix">
              <div class="col-md-2 col-sm-2 list-view">
                <a href="javascript:;"><i class="fa fa-th-large"></i></a>
                <a href="javascript:;"><i class="fa fa-th-list"></i></a>
              </div>
          
            </div>
            <!-- BEGIN PRODUCT LIST -->
            <div class="row product-list">
<?php
$conn = mysqli_connect("localhost", "root", "", "togartisans");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$id_cat = isset($_GET['id_cat']) ? intval($_GET['id_cat']) : 0;
$where = '';
if ($id_cat > 0) {
    $where = "WHERE id_cat = $id_cat";
}
$sql = "SELECT * FROM products $where";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
    while($produit = mysqli_fetch_assoc($result)) {
        echo '<div class="col-md-4 col-sm-6 col-xs-12">
                <div class="product-item">
                  <div class="pi-img-wrapper">
                    <img src="'.htmlspecialchars($produit['img_product']).'" class="img-responsive" alt="'.htmlspecialchars($produit['nom_product']).'">
                    <div>
                      <a href="'.htmlspecialchars($produit['img_product']).'" class="btn btn-default fancybox-button">Zoom</a>
                      <a href="products_details.php?id_product='.htmlspecialchars($produit['id_product']).'" class="btn btn-default fancybox-fast-view">View</a>
                    </div>
                  </div>
                  <h3><a href="products_details.php?id_product='.htmlspecialchars($produit['id_product']).'">'.htmlspecialchars($produit['nom_product']).'</a></h3>
                  <div class="pi-price">'.htmlspecialchars($produit['prix_product']).' FCFA</div>
                  <a href="add_to_cart_form.php?id_product='.htmlspecialchars($produit['id_product']).'" class="btn btn-default add2cart">Panier</a>
                </div>
              </div>';
    }
} else {
    echo "<div class='col-md-12'>Aucun produit trouvé.</div>";
}
mysqli_close($conn);
?>
</div>
            <!-- END PRODUCT LIST -->
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

<?php include 'footer.php'; ?>

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
    <!-- BEGIN CORE PLUGINS(REQUIRED FOR ALL PAGES) -->
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