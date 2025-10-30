<?php include("header.php"); ?>
<?php include("sidebar.php"); ?>
<?php include("config.php"); ?>
			<div class="main-panel">
				<div class="content">
					<div class="container-fluid">
						<h4 class="page-title">Revenus des articles</h4>
				                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">Revenus générés par les articles vendus sur la plateforme</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="add-row" class="display table table-striped table-hover" >
                                                <thead>
                                                    <tr>
                                                        <th>Id</th>
                                                        <th>Id article</th>
                                                        <th>Nom article</th>
                                                        <th>Prix unitaire</th>
                                                        <th>Quantité vendue</th>
                                                        <th>Revenu total</th>
                                                        <th>Date de calcul</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
<?php
require_once "config.php";
$sql = "SELECT id_revenu, id_product, nom_product, prix, quantite_totale, 
               (prix * quantite_totale) AS revenu_total, date_calcule 
        FROM revenus";
$result = mysqli_query($link, $sql);
if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['id_revenu'] . "</td>";
        echo "<td>" . $row['id_product'] . " - " ;
        echo "<td>" . htmlspecialchars($row['nom_product']) . "</td>";
        echo "<td>" . $row['prix'] . "</td>";
        echo "<td>" . $row['revenu_total'] . "</td>";
        echo "<td>" . $row['date_calcule'] . "</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='5'>Aucun revenu trouvé</td></tr>";
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
            </div>
        </div>
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