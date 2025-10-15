<!-- page login artisan -->
<?php
// Démarrer la session
// Vérifier si l'artisan est déjà connecté, si oui, le rediriger vers la page d'accueil
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
// Inclure le fichier de configuration
require_once "../admin/config.php";
// Définir les variables et initialiser avec des valeurs vides
$username = $password = "";
$username_err = $password_err = $login_err = "";
// Traitement des données du formulaire lors de la soumission
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Vérifier si le nom d'utilisateur est vide
    if(empty(trim($_POST["username"]))){
        $username_err = "Veuillez entrer votre nom d'utilisateur.";
    } else{
        $username = trim($_POST["username"]);
    }
    // Vérifier si le mot de passe est vide
    if(empty(trim($_POST["password"]))){
        $password_err = "Veuillez entrer votre mot de passe.";
    } else{
        $password = trim($_POST["password"]);
    }
    // Valider les informations d'identification
    if(empty($username_err) && empty($password_err)){
        // Préparer une requête SELECT
        $sql = "SELECT id_artisan, username, password FROM artisans WHERE username = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Lier les variables à la requête préparée en tant que paramètres
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            // Définir les paramètres
            $param_username = $username;
            // Tenter d'exécuter la requête préparée
            if(mysqli_stmt_execute($stmt)){
                // Stocker le résultat
                mysqli_stmt_store_result($stmt);
                // Vérifier si le nom d'utilisateur existe, si oui vérifier le mot de passe
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    // Lier les variables de résultat
                    mysqli_stmt_bind_result($stmt, $id_artisan, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Le mot de passe est correct, démarrer une nouvelle session
                            session_start();
                            // Stocker les données dans les variables de session
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id_artisan"] = $id_artisan;
                            $_SESSION["username"] = $username;                            
                            // Rediriger l'artisan vers la page d'accueil
                            header("location: index.php");
                        } else{
                            // Le mot de passe n'est pas valide, afficher un message d'erreur générique
                            $login_err = "Nom d'utilisateur ou mot de passe invalide.";
                        }
                    }
                } else{
                    // Le nom d'utilisateur n'existe pas, afficher un message d'erreur générique
                    $login_err = "Nom d'utilisateur ou mot de passe invalide.";
                }
            } else{
                echo "Oups! Une erreur est survenue. Veuillez réessayer plus tard.";
            }
            // Fermer la déclaration
            mysqli_stmt_close($stmt);
        }
    }
    // Fermer la connexion
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Artisan - Tog'Artisans</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ 
        width: 600px; 
        padding: 30px; 
        margin: auto; 
        margin-top: 100px; 
        border: 1px solid #ccc; 
        border-radius: 10px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>PAGE DE CONNEXION ARTISAN</h2>
        <p>Veuillez remplir vos identifiants pour vous connecter.</p>
        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Nom d'utilisateur</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Se connecter">
            </div>
            <p>Vous n'avez pas de compte? <a href="register.php">Inscrivez-vous maintenant</a>.</p>
        </form>
    </div>
</body>
</html>