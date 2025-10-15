<?php
// Si l'utilisateur est déjà connecté
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: index.php");
    exit;
}

// Connexion à la base de données
require_once "../admin/config.php";

// Variables
$nom_user = $email_user = $phone_user = $password_user = $confirm_password = $adresse_user = $role = "";
$nom_err = $email_err = $phone_err = $password_err = $confirm_password_err = $adresse_err = $role_err = "";

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // NOM
    if (empty(trim($_POST["nom_user"]))) {
        $nom_err = "Veuillez entrer votre nom.";
    } else {
        $nom_user = trim($_POST["nom_user"]);
    }

    // EMAIL
    if (empty(trim($_POST["email_user"]))) {
        $email_err = "Veuillez entrer votre email.";
    } else {
        $sql = "SELECT id_user FROM users WHERE email_user = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            $param_email = trim($_POST["email_user"]);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $email_err = "Cet email est déjà utilisé.";
                } else {
                    $email_user = trim($_POST["email_user"]);
                }
            }
            mysqli_stmt_close($stmt);
        }
    }

    // TÉLÉPHONE
    if (empty(trim($_POST["phone_user"]))) {
        $phone_err = "Veuillez entrer votre numéro de téléphone.";
    } else {
        $sql = "SELECT id_user FROM users WHERE phone_user = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $param_phone);
            $param_phone = trim($_POST["phone_user"]);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $phone_err = "Ce numéro est déjà utilisé.";
                } else {
                    $phone_user = trim($_POST["phone_user"]);
                }
            }
            mysqli_stmt_close($stmt);
        }
    }

    // MOT DE PASSE
    if (empty(trim($_POST["password_user"]))) {
        $password_err = "Veuillez entrer un mot de passe.";
    } elseif (strlen(trim($_POST["password_user"])) < 6) {
        $password_err = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        $password_user = trim($_POST["password_user"]);
    }

    // CONFIRMATION MOT DE PASSE
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Veuillez confirmer le mot de passe.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password_user != $confirm_password)) {
            $confirm_password_err = "Les mots de passe ne correspondent pas.";
        }
    }

    // ADRESSE
    if (empty(trim($_POST["adresse_user"]))) {
        $adresse_err = "Veuillez entrer votre adresse.";
    } else {
        $adresse_user = trim($_POST["adresse_user"]);
    }

    // ROLE
    if (empty($_POST["role"])) {
        $role_err = "Veuillez choisir un rôle.";
    } else {
        $role = $_POST["role"];
    }

    // Si pas d'erreurs → insertion
    if (empty($nom_err) && empty($email_err) && empty($phone_err) && empty($password_err) && empty($confirm_password_err) && empty($adresse_err) && empty($role_err)) {

        $sql = "INSERT INTO users (nom_user, email_user, phone_user, password_user, adresse_user, role) 
                VALUES (?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssssss", $param_nom, $param_email, $param_phone, $param_password, $param_adresse, $param_role);

            $param_nom = $nom_user;
            $param_email = $email_user;
            $param_phone = $phone_user;
            $param_password = password_hash($password_user, PASSWORD_DEFAULT);
            $param_adresse = $adresse_user;
            $param_role = $role;

            if (mysqli_stmt_execute($stmt)) {
                // Création de la session après inscription
                $_SESSION["loggedin"] = true;
                $_SESSION["email_user"] = $email_user;
                $_SESSION["role"] = $role;

                // Redirection selon le rôle
                if ($role === "artisan") {
                    header("location: artisans/login.php");
                } elseif ($role === "livreur") {
                    header("location: livreurs/login.php");
                } else {
                    header("location: client/accueil.php");
                }
                exit;
            } else {
                echo "❌ Une erreur est survenue. Veuillez réessayer.";
            }

            mysqli_stmt_close($stmt);
        }
    }

    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - Tog'Artisans</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body { font: 14px sans-serif; }
        .wrapper {
            width: 650px;
            padding: 30px;
            margin: auto;
            margin-top: 80px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <h2><center>PAGE D'INSCRIPTION</center></h2>
    <p><center>Veuillez remplir ce formulaire pour créer un compte.</center></p>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

        <div class="form-group">
            <label>Nom :</label>
            <input type="text" name="nom_user" class="form-control" value="<?php echo $nom_user; ?>" required>
            <span class="text-danger"><?php echo $nom_err; ?></span>
        </div>

        <div class="form-group">
            <label>Email :</label>
            <input type="email" name="email_user" class="form-control" value="<?php echo $email_user; ?>" required>
            <span class="text-danger"><?php echo $email_err; ?></span>
        </div>

        <div class="form-group">
            <label>Téléphone :</label>
            <input type="text" name="phone_user" class="form-control" value="<?php echo $phone_user; ?>" required>
            <span class="text-danger"><?php echo $phone_err; ?></span>
        </div>

        <div class="form-group">
            <label>Mot de passe :</label>
            <input type="password" name="password_user" class="form-control" required>
            <span class="text-danger"><?php echo $password_err; ?></span>
        </div>

        <div class="form-group">
            <label>Confirmer le mot de passe :</label>
            <input type="password" name="confirm_password" class="form-control" required>
            <span class="text-danger"><?php echo $confirm_password_err; ?></span>
        </div>

        <div class="form-group">
            <label>Adresse :</label>
            <input type="text" name="adresse_user" class="form-control" value="<?php echo $adresse_user; ?>" required>
            <span class="text-danger"><?php echo $adresse_err; ?></span>
        </div>

        <div class="form-group">
            <label>Rôle :</label>
            <select name="role" class="form-control" required>
                <option value="">Sélectionnez votre rôle</option>
                <option value="client" <?php if($role=='client') echo 'selected'; ?>>Client</option>
                <option value="artisan" <?php if($role=='artisan') echo 'selected'; ?>>Artisan</option>
                <option value="livreur" <?php if($role=='livreur') echo 'selected'; ?>>Livreur</option>
            </select>
            <span class="text-danger"><?php echo $role_err; ?></span>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary">S'inscrire</button>
            <button type="reset" class="btn btn-secondary ml-2">Réinitialiser</button>
        </div>

    </form>
</div>
</body>
</html>
