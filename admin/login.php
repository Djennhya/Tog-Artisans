<!-- Page login administrateur -->
<?php
session_start();
// Vérifier si l'administrateur est déjà connecté, sinon rediriger vers la page de connexion
if(isset($_SESSION["admin_loggedin"]) && $_SESSION["admin_loggedin"] === true){
    header("Location: index.php");
    exit;
}
// Inclure le fichier de configuration
require_once "config.php";
// Définir les variables et initialiser avec des valeurs vides
$username = $password = "";
$username_err = $password_err = $login_err = "";
// Traitement des données du formulaire lors de la soumission
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Vérifier si le nom d'utilisateur est vide
    if(empty(trim($_POST["username"]))){
        $username_err = "Veuillez entrer le nom d'utilisateur.";
    } else{
        $username = trim($_POST["username"]);
    }
    // Vérifier si le mot de passe est vide
    if(empty(trim($_POST["password"]))){
        $password_err = "Veuillez entrer le mot de passe.";
    } else{
        $password = trim($_POST["password"]);
    }
    // Valider les informations d'identification
    if(empty($username_err) && empty($password_err)){
        // Préparer une requête SELECT
        $sql = "SELECT id_admin, username, password FROM admins WHERE username = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Lier les variables à la requête préparée en tant que paramètres
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            // Définir les paramètres
            $param_username = $username;
            // Tenter d'exécuter la requête préparée
            if(mysqli_stmt_execute($stmt)){
                // Stocker le résultat
                mysqli_stmt_store_result($stmt);
                // Vérifier si le nom d'utilisateur existe, sinon afficher un message d'erreur
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Lier les variables de résultat
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Le mot de passe est correct, donc démarrer une nouvelle session
                            session_start();
                            // Stocker les données dans les variables de session
                            $_SESSION["admin_loggedin"] = true;
                            $_SESSION["admin_id"] = $id;
                            $_SESSION["admin_username"] = $username;
                            // Rediriger l'administrateur vers la page d'accueil
                            header("Location: index.php");
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
                echo "Oups! Quelque chose a mal tourné. Veuillez réessayer plus tard.";
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
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Admin Tog'Artisans</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <link rel="icon" href="assets/img/icon.ico" type="image/x-icon"/>

    <!-- Fonts and icons -->
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {"families":["Lato:300,400,700,900"]},
            custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['assets/css/fonts.min.css']},
            active: function() {
                sessionStorage.fonts = true;
            }
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/atlantis.min.css">
</head>
<!-- le formulaire doit etre centré verticalement et horizontalement -->
 
<body class="login">
    <div class="wrapper wrapper-login">
        <div class="container container-login animated fadeIn">
            <h3 class="text-center">PAGE DE CONNEXION ADMINISTRATEUR</h3>
            <div class="login-form">
                 <div class="text-center mb-3">
                <img src="assets/img/togartisans.png" alt="Logo" style="max-width:120px;">
            </div>
        <style>
        body.login {
            display: flex;
            justify-content: center;   /* centre horizontalement */
            align-items: center;       /* centre verticalement */
            height: 100vh;             /* prend toute la hauteur de l’écran */
            margin: 0;
            background: #f4f4f4;       /* optionnel : fond clair */
        }

        .container-login {
            max-width: 800px;          /* largeur du bloc */
            width: 100%;            /* largeur à 100% de son conteneur */
            padding: 30px;
            background: #fff;
            left: 100px;
            right: 100px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .login-form .form-group {
            margin-bottom: 20px;
        }
        .login-form .btn-primary {
            background-color: #06792eea;
            border-color: #06792eea;
        }
    </style>
                <?php 
                if(!empty($login_err)){
                    echo '<div class="alert alert-danger">' . $login_err . '</div>';
                }        
                ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                        <label for="username" class="placeholder"><b>Nom d'utilisateur</b></label>
                        <input id="username" name="username" type="text" class="form-control" value="<?php echo $username; ?>">
                        <span class="help-block" style="color: red;"><?php echo $username_err; ?></span>
                    </div>
                    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                        <label for="password" class="placeholder"><b>Mot de passe</b></label>
                        <div class="position-relative">
                            <input id="password" name="password" type="password" class="form-control" value="<?php echo $password; ?>">
                            <div class="show-password">
                                <i class="icon-eye"></i>
                            </div>
                        </div>
                        <span class="help-block" style="color: red;"><?php echo $password_err; ?></span>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="rememberme">
                            <label class="custom-control-label" for="rememberme">Se souvenir de moi</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <a href="forgot_password.php" class="link float-right">Mot de passe oublié ?</a>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Connexion</button>
                    </div>
                    <!-- pas de compte admin -->
                    <div class="form-group text-center">
                        <span class="text-muted">Pas de compte ?</span> <a href="register.php">Créer un compte</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<script src="assets/js/core/jquery.3.2.1.min.js"></script>
<script src="assets/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js
"></script>
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap.min.js"></script>
<script src="assets/js/atlantis.min.js"></script>
<script>
    $(document).ready(function() {
        // Show Password
        $('.show-password').click(function() {
            if($(this).siblings('input').attr('type') == 'password') {
                $(this).siblings('input').attr('type','text');
            } else {
                $(this).siblings('input').attr('type','password');
            }
        });
    });
</script>