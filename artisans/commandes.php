<?php include("header.php"); ?>
<?php include("sidebar.php"); ?>
<?php include("config.php"); ?>
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
                                                        <th>Id</th>
                                                        <th>Id prod</th>
                                                        <th>Client</th>
                                                        <th>Artisan</th>
                                                        <th>Livreur</th>
                                                        <th>Quantité</th>
                                                        <th>Prix total</th>
                                                        <th>Statut</th>
                                                        <th>Date de commande</th>
                                                        <th style="width: 20%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
<?php
$sql = "SELECT * FROM commandes";
$result = mysqli_query($link, $sql);
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id_commande'] . "</td>";
        echo "<td>" . $row['id_product'] . "</td>";
        echo "<td>" . $row['id_client'] . "</td>";
        echo "<td>" . $row['id_artisan'] . "</td>";
        echo "<td>" . $row['id_livreur'] . "</td>";
        echo "<td>" . $row['quantity'] . "</td>";
        echo "<td>" . $row['total_price'] . "</td>";
        echo "<td>" . $row['status'] . "</td>";
        echo "<td>" . $row['date_commande'] . "</td>";
        echo "<td><a href='edit_commande.php?id=" . $row['id'] . "' class='btn btn-primary btn-sm'>Edit</a> ";
        echo "<a href='delete_commande.php?id=" . $row['id'] . "' class='btn btn-danger btn-sm'>Delete</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='10'>Aucune commande trouvée</td></tr>";
}
mysqli_close($link);
?>
                                                </tbody>
                                            </table>
                                        </div>
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
            </div>
        </div>

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