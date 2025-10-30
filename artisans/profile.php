<!-- Mon profil en tant que administrateur -->
<?php include("header.php"); ?>
<?php include("sidebar.php"); ?>
<div class="main-panel">
    <div class="content">
        <div class="container-fluid">
            <h4 class="page-title">Mon Profil</h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Détails du profil administrateur</div>
                        </div>
                        <div class="card-body">
                            <form>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="adminName">Nom</label>
                                        <input type="text" class="form-control" id="adminName" value="Admin User" disabled>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="adminEmail">Email</label>
                                        <input type="email" class="form-control" id="adminEmail" value="Admin Email" disabled>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="adminRole">Rôle</label>
                                        <input type="text" class="form-control" id="adminRole" value="Administrateur" disabled>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="adminUsername">Nom d'utilisateur</label>
                                        <input type="text" class="form-control" id="adminUsername" value="adminuser" disabled>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="adminPassword">Ancien mot de passe</label>
                                        <input type="password" class="form-control" id="adminPassword" value="password123" disabled>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="adminNewPassword">Nouveau mot de passe</label>
                                        <input type="password" class="form-control" id="adminNewPassword" placeholder="Nouveau mot de passe" disabled>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="adminAddress">Adresse</label>
                                        <input type="text" class="form-control" id="adminAddress" value="Adidogome wessome, Togo,Lomé" disabled>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="adminPhone">Téléphone</label>
                                        <input type="text" class="form-control" id="adminPhone" value="+228 00 00 00 00" disabled>
                                    </div>
                                <button type="button" class="btn btn-primary" disabled>Modifier le profil</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("footer.php"); ?>
</div>
</div>
</div>
</body>
</html>
<script src="assets/js/core/jquery.3.2.1.min.js"></script>
<script src="assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap.min.js"></script>
<script src="assets/js/plugin/chartist/chartist.min.js"></script>
<script src="assets/js/plugin/chartist/plugin/chartist-plugin-tooltip.min.js"></script>
<script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
<script src="assets/js/plugin/bootstrap-toggle/bootstrap-toggle.min.js"></script>
<script src="assets/js/plugin/jquery-mapael/jquery.mapael.min.js"></script>
<script src="assets/js/plugin/jquery-mapael/maps/world_countries.min.js"></script>
<script src="assets/js/plugin/chart-circle/circles.min.js"></script>
<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>
<script src="assets/js/ready.min.js"></script>
<script>
    $( function() {
        $( "#slider" ).slider({
            range: "min",
            max: 100,
            value: 40,
        });
        $( "#slider-range" ).slider({
            range: true,
            min: 0,
            max: 500,
            values: [ 75, 300 ]
        });
    } );
</script>
</html>