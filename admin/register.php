<!-- page d'inscription en tant qu'administrateur -->
<?php
// Inclure le fichier de configuration
require_once "config.php";
// Définir les variables et initialiser avec des valeurs vides
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
$email = $email_err = "";
$role = $role_err = "";
$full_name = $full_name_err = "";
$address = $address_err = "";
$phone = $phone_err = "";
// Traitement des données du formulaire lors de la soumission
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Valider le nom d'utilisateur
    if(empty(trim($_POST["username"]))){
        $username_err = "Veuillez entrer un nom d'utilisateur.";
    } else{
        // Préparer une requête SELECT
        $sql = "SELECT id_admin FROM admins WHERE username = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Lier les variables à la requête préparée en tant que paramètres
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            // Définir les paramètres
            $param_username = trim($_POST["username"]);
            // Tenter d'exécuter la requête préparée
            if(mysqli_stmt_execute($stmt)){
                /* stocker le résultat */
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Ce nom d'utilisateur est déjà pris.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Une erreur est survenue. Veuillez réessayer plus tard.";
            }
            // Fermer la déclaration
            mysqli_stmt_close($stmt);
        }
    }
    // Valider l'email
    if(empty(trim($_POST["email"]))){
        $email_err = "Veuillez entrer une adresse email.";
    } elseif(!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)){
        $email_err = "Veuillez entrer une adresse email valide.";
    } else{
        // Préparer une requête SELECT
        $sql = "SELECT id_admin FROM admins WHERE email = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Lier les variables à la requête préparée en tant que paramètres
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            // Définir les paramètres
            $param_email = trim($_POST["email"]);
            // Tenter d'exécuter la requête préparée
            if(mysqli_stmt_execute($stmt)){
                /* stocker le résultat */
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "Cette adresse email est déjà utilisée.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                echo "Oops! Une erreur est survenue. Veuillez réessayer plus tard.";
            }
            // Fermer la déclaration
            mysqli_stmt_close($stmt);
        }
    }
    // Valider le mot de passe
    if(empty(trim($_POST["password"]))){
        $password_err = "Veuillez entrer un mot de passe.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Le mot de passe doit contenir au moins 6 caractères.";
    } else{
        $password = trim($_POST["password"]);
    }
    // Valider la confirmation du mot de passe
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Veuillez confirmer le mot de passe.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Le mot de passe et la confirmation ne correspondent pas.";
        }
    }
    // Valider le rôle
    if(empty(trim($_POST["role"]))){
        $role_err = "Veuillez entrer un rôle.";
    } else{
        $role = trim($_POST["role"]);
    }
    // Valider le nom complet
    if(empty(trim($_POST["full_name"]))){
        $full_name_err = "Veuillez entrer le nom complet.";
    } else{
        $full_name = trim($_POST["full_name"]);
    }
    // Valider l'adresse
    if(empty(trim($_POST["address"]))){
        $address_err = "Veuillez entrer une adresse.";
    } else{
        $address = trim($_POST["address"]);
    }
    // Valider le téléphone
    if(empty(trim($_POST["phone"]))){
        $phone_err = "Veuillez entrer un numéro de téléphone.";
    } else{
        $phone = trim($_POST["phone"]);
    }
    // Vérifier les erreurs avant d'insérer dans la base de données
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err
) && empty($email_err) && empty($role_err) && empty($full_name_err) && empty($address_err) && empty($phone_err)){
        // Préparer une requête INSERT
        $sql = "INSERT INTO admins (username, password, email, role, full_name, address, phone) VALUES (?, ?, ?, ?, ?, ?, ?)";
        if($stmt = mysqli_prepare($link, $sql)){
            // Lier les variables à la requête préparée en tant que paramètres
            mysqli_stmt_bind_param($stmt, "sssssss", $param_username, $param_password, $param_email, $param_role, $param_full_name, $param_address, $param_phone);
            // Définir les paramètres
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Créer un mot de passe haché
            $param_email = $email;
            $param_role = $role;
            $param_full_name = $full_name;
            $param_address = $address;
            $param_phone = $phone;
            // Tenter d'exécuter la requête préparée
            if(mysqli_stmt_execute($stmt)){
                // Rediriger vers la page de connexion
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Une erreur est survenue. Veuillez réessayer plus tard.";
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
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription Administrateur</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font: 14px sans-serif; 
            background-color: #f8f9fa;
        }
        .wrapper {
            width: 400px; 
            padding: 20px; 
            margin: auto; 
            margin-top: 50px; 
            background: #fff; 
            border-radius: 10px; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .form-group span {
            color: red;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Inscription Administrateur</h2>
        <p>Veuillez remplir ce formulaire pour créer un compte administrateur.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Nom d'utilisateur</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label>Mot de passe</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirmer le mot de passe</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Rôle</label>
                <input type="text" name="role" class="form-control <?php echo (!empty($role_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $role; ?>">
                <span class="invalid-feedback"><?php echo $role_err; ?></span>
            </div>
            <div class="form-group">
                <label>Nom complet</label>
                <input type="text" name="full_name" class="form-control <?php echo (!empty($full_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $full_name; ?>">
                <span class="invalid-feedback"><?php echo $full_name_err; ?></span>
            </div>
            <div class="form-group">
                <label>Adresse</label>
                <input type="text" name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $address; ?>">
                <span class="invalid-feedback"><?php echo $address_err; ?></span>
            </div>
            <div class="form-group">
                <label>Téléphone</label>
                <input type="text" name="phone" class="form-control <?php echo (!empty($phone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phone; ?>">
                <span class="invalid-feedback"><?php echo $phone_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="S'inscrire">
                <input type="reset" class="btn btn-secondary ml-2" value="Réinitialiser">
            </div>
            <p>Vous avez déjà un compte ? <a href="login.php">Connectez-vous ici</a>.</p>
        </form>
    </div>
</body>
</html>