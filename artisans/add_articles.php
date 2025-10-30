<!-- Ajout d'un nouveau produit -->
<?php include("header.php"); ?>
<?php include("sidebar.php"); ?>
<?php
// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'togartisans');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérification du formulaire
if (isset($_POST['submit'])) {
    $name        = $_POST['name'];
    $description = $_POST['description'];
    $price       = $_POST['price'];
    $stock       = $_POST['stock'];
    $category    = $_POST['category']; // ID de la catégorie
    $image       = $_FILES['image']['name'];
    $target      = "images/" . basename($image);

    // Insertion du produit
    $sql = "INSERT INTO products (nom_product, description_product, prix_product, stock, category, img_product) 
            VALUES ('$name', '$description', '$price', '$stock', '$category', '$image')";

    if ($conn->query($sql)) {
        // Téléchargement de l'image
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            echo "<div class='alert alert-success'>Nouveau produit ajouté avec succès ✅</div>";
        } else {
            echo "<div class='alert alert-warning'>Produit ajouté mais image non téléchargée ⚠️</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Erreur : " . $conn->error . "</div>";
    }
}

// Récupération des catégories pour le menu déroulant
$cats = $conn->query("SELECT id_cat, nom_cat FROM category");
?>

<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10">
                <h1 class="page-header">Ajout d'un nouveau produit</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="name">Nom du produit</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="price">Prix</label>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <input type="number" class="form-control" id="stock" name="stock" required>
                    </div>

                    <div class="form-group">
                        <label for="category">Catégorie</label>
                        <select class="form-control" id="category" name="category" required>
                            <option value="">-- Choisir une catégorie --</option>
                            <?php while($row = $cats->fetch_assoc()) { ?>
                                <option value="<?= $row['id']; ?>"><?= $row['nom_category']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="image">Image du produit</label>
                        <input type="file" class="form-control" id="image" name="image" required>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary">Ajouter le produit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include("footer.php"); ?>
