<!-- Body BEGIN -->
<body class="ecommerce">
<?php include("header.php"); ?>
    
    <div class="main">
      <div class="container">
        <ul class="breadcrumb">
            <li><a href="index.html">Accueil</a></li>
            <li><a href="">Boutique</a></li>
            <li class="active">Mon Compte</li>
        </ul>
        <!-- BEGIN SIDEBAR & CONTENT -->
        <div class="row margin-bottom-40">
          <!-- BEGIN SIDEBAR -->
          <div class="sidebar col-md-3 col-sm-3">
            <ul class="list-group margin-bottom-25 sidebar-menu">
              <li class="list-group-item clearfix"><a href="javascript:;"><i class="fa fa-angle-right"></i>Connexion</a></li>
              <li class="list-group-item clearfix"><a href="javascript:;"><i class="fa fa-angle-right"></i>Inscription</a></li>
              <li class="list-group-item clearfix"><a href="javascript:;"><i class="fa fa-angle-right"></i>Changer votre mot de passe</a></li>
              <li class="list-group-item clearfix"><a href="javascript:;"><i class="fa fa-angle-right"></i>Contact</a></li>
              <li class="list-group-item clearfix"><a href="javascript:;"><i class="fa fa-angle-right"></i>Returns</a></li>
            </ul>
          </div>
          <!-- END SIDEBAR -->

          <!-- BEGIN CONTENT -->
          <div class="col-md-9 col-sm-7">
            <h1>Mon profile</h1>
            <div class="content-page">
              <h3>Mes informations personnelles</h3>
              <ul>
                <li><a href="javascript:;">Éditer votre information personnelle</a></li>
                <li><a href="javascript:;">Changer votre mot de passe</a></li>
                <li><a href="javascript:;">Modifier vos adresses</a></li>
                <li><a href="javascript:;">Revoyer vos anciennes commandes</a></li>
              </ul>
              <hr>

              <h3>Mes commandes</h3>
              <ul>
                <li><a href="javascript:;">Voir l'historique de vos commandes</a></li>
                <li><a href="javascript:;">Téléchargements</a></li>
                <li><a href="javascript:;">Informations de paiement récurrent</a></li>
                <li><a href="javascript:;">Mes adresses de livraison</a></li>
                <li><a href="javascript:;">Mes adresses de facturation</a></
              </ul>
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