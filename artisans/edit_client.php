<!-- Page éditer un client -->
<?php
// Inclure le fichier de configuration
require_once "config.php";
// Vérifier si l'ID du client est passé en paramètre GET
if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    $id = trim($_GET["id"]);
    // Préparer une requête SELECT pour récupérer les informations du client
    $sql = "SELECT nom_user, email_user, phone_user FROM users WHERE id_user = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        // Lier les variables à la requête préparée en tant que paramètres
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        // Définir les paramètres
        $param_id = $id;
        // Tenter d'exécuter la requête préparée
        if(mysqli_stmt_execute($stmt)){
            // Stocker le résultat
            mysqli_stmt_store_result($stmt);
            // Vérifier si le client existe, sinon rediriger vers la page des clients
            if(mysqli_stmt_num_rows($stmt) == 1){
                // Lier les variables de résultat
                mysqli_stmt_bind_result($stmt, $nom, $email, $phone);
                if(mysqli_stmt_fetch($stmt)){
                    // Les variables sont maintenant définies avec les valeurs du client
                }
            } else{
                // Rediriger vers la page des clients si l'ID n'est pas valide
                header("Location: clients.php");
                exit();
            }
        } else{
            echo "Une erreur est survenue. Veuillez réessayer plus tard.";
        }
        // Fermer la déclaration
        mysqli_stmt_close($stmt);
    }
} else{
    // Rediriger vers la page des clients si l'ID n'est pas passé en paramètre
    header("Location: clients.php");
    exit();
}
// Traitement des données du formulaire lors de la soumission
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Récupérer les valeurs du formulaire
    $id = $_POST["id"];
    $nom = trim($_POST["nom"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    // Préparer une requête UPDATE pour mettre à jour les informations du client
    $sql = "UPDATE users SET nom_user = ?, email_user = ?, phone_user = ? WHERE id_user = ?";
    if($stmt = mysqli_prepare($link, $sql)){
        // Lier les variables à la requête préparée en tant que paramètres
        mysqli_stmt_bind_param($stmt, "sssi", $param_nom, $param_email, $param_phone, $param_id);
        // Définir les paramètres
        $param_nom = $nom;
        $param_email = $email;
        $param_phone = $phone;
        $param_id = $id;
        // Tenter d'exécuter la requête préparée
        if(mysqli_stmt_execute($stmt)){
            // Rediriger vers la page des clients après la mise à jour réussie
            header("Location: clients.php");
            exit();
        } else{
            echo "Une erreur est survenue. Veuillez réessayer plus tard.";
        }
        // Fermer la déclaration
        mysqli_stmt_close($stmt);
    }
}
// Fermer la connexion
mysqli_close($link);
?>
<?php include("header.php"); ?>
<?php include("sidebar.php"); ?>
            <div class="main-panel">
                <div class="content">
                    <div class="container-fluid">
                        <h4 class="page-title">Modifier les informations de ce client</h4>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">Modifier les informations du client</div>
                                    </div>
                                    <div class="card-body">
                                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                            <input type="hidden" name="id" value="<?php echo $id; ?>">
                                            <div class="form-group
                                                <label for="nom">Nom du client</label>
                                                <input type="text" name="nom" class="form-control" value="<?php echo htmlspecialchars($nom); ?>" required>
                                            </div>
                                            <div class="form-group
                                                <label for="email">Email du client</label>
                                                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
                                            </div>
                                            <div class="form-group
                                                <label for="phone">Téléphone</label>
                                                <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($phone); ?>" required>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Valider</button>
                                            <a href="clients.php" class="btn btn-secondary">Annuler</a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
<?php include("footer.php"); ?>
