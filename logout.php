<!--Déconnexion-->
<?php
session_start();
session_unset();
session_destroy();
header('Location: index.php');
exit;
?>
<?php include("header.php"); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Déconnexion - Tog'Artisans</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    </head>
    <body>
        <div class="container" style="margin-top:50px; max-width:600px;">
            <h2>Vous êtes déconnecté</h2>
            <p>Vous avez été déconnecté avec succès.</p>
            <a href="login.php" class="btn btn-primary">Se reconnecter</a>
            <a href="index.php" class="btn btn-secondary">Retour à l'accueil</a>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    </body>
</html>
<?php include("footer.php"); ?>