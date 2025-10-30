<!-- Ajouter une nouvelle catégorie -->

<?php
include("header.php");
include("sidebar.php");
include("config.php");

if(isset($_POST['submit'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);

    $sql = "INSERT INTO category (nom_cat, description_cat) VALUES ('$name', '$description')";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='alert alert-success'>Nouvelle catégorie ajoutée avec succès ✅</div>";
    } else {
        echo "<div class='alert alert-danger'>Erreur: " . $conn->error . "</div>";
    }
}
?>
<!-- Ajouter du style aux messages -->
<style>
    .alert {
        position: fixed;
        top: 50px;
        margin-top: 200px;
        right: 20px;
        z-index: 1000;
    }

</style>
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10">
                <h3 class="page-header">Ajouter une nouvelle catégorie</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="name">Nom de la catégorie</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Ajouter la catégorie</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include("footer.php"); ?>
