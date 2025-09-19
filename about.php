<?php include("header.php"); ?>

    <div class="main">
      <div class="container">
        <ul class="breadcrumb">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="products.php">Boutique</a></li>
        </ul>
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
          <!-- BEGIN SIDEBAR -->
          <div class="sidebar col-md-3 col-sm-3">
            <ul class="list-group margin-bottom-25 sidebar-menu">
              <li class="list-group-item clearfix"><a href="javascript:;"><i class="fa fa-angle-right"></i> Connexion</a></li>
              <li class="list-group-item clearfix"><a href="javascript:;"><i class="fa fa-angle-right"></i> Inscription</a></li>
              <li class="list-group-item clearfix"><a href="javascript:;"><i class="fa fa-angle-right"></i> Nous contacter</a></li>
            </ul>
          </div>
          <!-- END SIDEBAR -->

          <!-- BEGIN CONTENT -->
          <div class="col-md-9 col-sm-9">
            <h1>A propos de nous</h1>
            <div class="content-page">
              <p><img src="assets/pages/img/ct1.jpg" alt="About us" class="img-responsive"></p> 
              <p> Tog'Artisans est une plateforme de e-commerce dédiée à la promotion et à la vente des produits artisanaux fabriqués au Togo. 
                Notre mission est de valoriser le savoir-faire des artisans locaux en leur offrant une vitrine en ligne pour atteindre un public 
                plus large, tant au niveau national qu'international. Nous croyons fermement que l'artisanat est un élément clé de la culture 
                togolaise et nous nous engageons à soutenir les artisans en leur fournissant les outils nécessaires pour réussir dans le monde numérique.</p>

              <h2>Comment fonctionne Tog'Artisans</h2>

              <p> Sur Tog'Artisans, les artisans peuvent créer leur propre boutique en ligne où ils peuvent présenter et vendre leurs produits. 
                Nous offrons une interface conviviale qui permet aux artisans de gérer facilement leurs stocks, de suivre les commandes et 
                d'interagir avec leurs clients. Les acheteurs, quant à eux, peuvent parcourir une large gamme de produits artisanaux, 
                lire des descriptions détaillées, consulter des avis et effectuer des achats en toute sécurité.</p>

              <h3>Les étapes pour éffectuer un achat</h3>
              <ul>
                <li>Parcourir les catégories de produits ou utiliser la barre de recherche pour trouver des articles spécifiques.</li>
                <li>Cliquer sur un produit pour voir plus de détails, y compris des photos, des descriptions et des avis.</li>
                <li>Ajouter les articles souhaités au panier.</li>
                <li>Passer à la caisse, où vous pourrez entrer vos informations de livraison et de paiement.</li>
                <li>Confirmer votre commande et recevoir une confirmation par email.</li>
                <li>Suivre l'état de votre commande via votre compte utilisateur.</li>
              </ul>

              <p> Nous espérons que vous apprécierez votre expérience sur Tog'Artisans et que vous découvrirez la richesse et la diversité de l'artisanat togolais. 
                Merci de soutenir nos artisans locaux!</p>
              <h2>Dons et Cadeaux </h2>

              <p> Si vous souhaitez soutenir notre mission et aider à promouvoir l'artisanat togolais, 
                vous pouvez faire un don via notre plateforme. Votre contribution nous permettra de continuer à offrir des services de qualité aux artisans 
                et à améliorer notre site pour une meilleure expérience utilisateur. En guise de remerciement, nous offrons des cadeaux exclusifs aux donateurs, 
                tels que des réductions sur les achats futurs ou des produits artisanaux uniques. Merci de votre soutien!</p>

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

    <script src="assets/corporate/scripts/layout.js" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            Layout.init();    
            Layout.initOWL();
            Layout.initTwitter();
        });
    </script>
    <!-- END PAGE LEVEL JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>