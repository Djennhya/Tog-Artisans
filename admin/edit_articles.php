<!-- Éditer un produit -->
<?php include("header.php"); ?>
<?php include("sidebar.php"); ?>
<?php
// Inclure le fichier de configuration
require_once "config.php";
// Vérifier si l'ID du produit est passé en paramètre
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = intval($_GET['id']);
    // Récupérer les détails du produit depuis la base de données
    $sql = "SELECT * FROM products WHERE id_product = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $product_id);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) == 1) {
                $product = mysqli_fetch_assoc($result);
            } else {
                echo "<script>alert('Produit non trouvé.'); window.location.href='articles.php';</script>";
                exit();
            }
        } else {
            echo "Erreur lors de l'exécution de la requête.";
            exit();
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Erreur lors de la préparation de la requête.";
        exit();
    }
} else {
    echo "<script>alert('ID de produit invalide.'); window.location.href='articles.php';</script>";
    exit();
}
// Traiter le formulaire soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $prix = floatval($_POST['prix']);
    $stock = intval($_POST['stock']);
    $categorie = intval($_POST['categorie']);
    // Gérer le téléchargement de l'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../assets/img/products/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                $img_path = "assets/img/products/" . basename($_FILES["image"]["name"]);
            } else {
                echo "<script>alert('Erreur lors du téléchargement de l\'image.');</script>";
                $img_path = $product['img_product'];
            }
        } else {
            echo "<script>alert('Seules les images JPG, JPEG, PNG et GIF sont autorisées.');</script>";
            $img_path = $product['img_product'];
        }
    } else {
        $img_path = $product['img_product'];
    }
    // Mettre à jour les détails du produit dans la base de données
    $update_sql = "UPDATE products SET nom_product = ?, description_product = ?, prix_product = ?, stock = ?, img_product = ?, id_cat = ? WHERE id_product = ?";
    if ($stmt = mysqli_prepare($link, $update_sql)) {
        mysqli_stmt_bind_param($stmt, "ssdssii", $nom, $description, $prix, $stock, $img_path, $categorie, $product_id);
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Produit mis à jour avec succès.'); window.location.href='articles.php';</script>";
            exit();
        } else {
            echo "Erreur lors de la mise à jour du produit.";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Erreur lors de la préparation de la requête de mise à jour.";
    }
}
?>
            <div class="main-panel">
                <div class="content">
                    <div class="container-fluid">
                        <h4 class="page-title">Éditer le produit</h4>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">Modifier les détails du produit</div>
                                    </div>
                                    <div class="card-body">
                                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id=" . $product_id); ?>" method="post" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label for="nom">Nom du produit</label>
                                                <input type="text" name="nom" class="form-control" value="<?php echo htmlspecialchars($product['nom_product']); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($product['description_product']); ?></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="prix">Prix (FCFA)</label>
                                                <input type="number" step="0.01" name="prix" class="form-control" value="<?php echo htmlspecialchars($product['prix_product']); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="stock">Stock</label>
                                                <input type="number" name="stock" class="form-control" value="<?php echo htmlspecialchars($product['stock']); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="categorie">Catégorie</label>
                                                <input type="number" name="categorie" class="form-control" value="<?php echo htmlspecialchars($product['id_cat']); ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label for="image">Image du produit</label>
                                                <input type="file" name="image" class="form-control-file">
                                                <?php
                                                if (!empty($product['img_product'])) {
                                                    echo "<img src='../" . htmlspecialchars($product['img_product']) . "' alt='Produit' width='150' style='margin-top:10px;'>";
                                                }
                                                ?>
                                            </div>
                                            <button type="submit" class="btn btn-success">Mettre à jour le produit</button>
                                            <a href="articles.php" class="btn btn-secondary">Annuler</a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
<?php include("footer.php"); ?>