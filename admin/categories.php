<?php include("header.php"); ?>
<?php include("sidebar.php"); ?>
<?php include("config.php"); ?>
			<div class="main-panel">
				<div class="content">
					<div class="container-fluid">
						<h4 class="page-title">Liste des catégories</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">Toutes les catégories d'articles sur la plateforme</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table id="add-row" class="display table table-striped table-hover" >
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Nom de la catégorie</th>
                                                        <th>Description</th>
                                                        <th style="width: 20%">Action</th>
                                                    </tr>
                                                </thead>
                                                <?php
												// Inclure le fichier de configuration
												require_once "config.php";

												// Récupérer tous les clients depuis la base de données
												$sql = "SELECT id_cat, nom_cat, description_cat FROM category";
												$result = mysqli_query($link, $sql);

												if (mysqli_num_rows($result) > 0) {
													// Afficher chaque client dans une ligne de tableau
													while($row = mysqli_fetch_assoc($result)) {
														echo "<tr>";
														echo "<td>" . $row['id_cat'] . "</td>";
														echo "<td>" . htmlspecialchars($row['nom_cat']) . "</td>";
														echo "<td>" . htmlspecialchars($row['description_cat']) . "</td>";
														echo "<td><a href='edit_cat.php?id=" . $row['id_cat'] . "' class='btn btn-primary btn-sm'>Éditer</a> ";
														echo "<a href='delete_cat.php?id=" . $row['id_cat'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce client ?\");'>Supprimer</a></td>";
														echo "</tr>";
													}
												} else {
													echo "<tr><td colspan='6'>Aucune catégorie trouvée.</td></tr>";
												}

												// Fermer la connexion
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