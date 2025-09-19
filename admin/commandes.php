<?php include("header.php"); ?>
<?php include("sidebar.php"); ?>
			<div class="main-panel">
				<div class="content">
					<div class="container-fluid">
						<h4 class="page-title">Liste des commandes</h4>
						                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">Toutes les commandes passées sur la plateforme</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="add-row" class="display table table-striped table-hover" >
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Client</th>
                                                        <th>Article</th>
                                                        <th>Quantité</th>
                                                        <th>Prix total</th>
                                                        <th>Date de commande</th>
                                                        <th style="width: 10%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>1</td>
                                                        <td>Lady D</td>
                                                        <td>Vase en céramique</td>
                                                        <td>2</td>
                                                        <td>50.00 FCFA</td>
                                                        <td>20-09-2025</td>
                                                        <td>
                                                            <div class="form-button-action">
                                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Voir Détails">
                                                                    <i class="fa fa-eye"></i>
                                                                </button>
                                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Supprimer">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>2</td>
                                                        <td>TOUKOULA Josée</td>
                                                        <td>Collier en perles</td>
                                                        <td>1</td>
                                                        <td>40.00 FCFA</td>
                                                        <td>21-09-2025</td>
                                                        <td>
                                                            <div class="form-button-action">
                                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Voir Détails">
                                                                    <i class="fa fa-eye"></i>
                                                                </button>
                                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Supprimer">
                                                                    <i class="fa fa-times"></i>
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
</body>
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