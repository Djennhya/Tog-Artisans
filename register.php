<?php
require __DIR__ . '/vendor/autoload.php';

use AfricasTalking\SDK\AfricasTalking;
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_user = $_POST['nom_user'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm_password) {
        $message = "Les mots de passe ne correspondent pas.";
    } else {
        $conn = mysqli_connect("localhost", "root", "", "togartisans");

        if (!$conn) {
            die("Erreur de connexion à la base de données: " . mysqli_connect_error());
        }

        $nom_user = mysqli_real_escape_string($conn, $nom_user);
        $email = mysqli_real_escape_string($conn, $email);
        $phone = mysqli_real_escape_string($conn, $phone);
        $password_hashed = password_hash($password, PASSWORD_BCRYPT);
        $code_2fa = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Vérifie si numéro existe déjà
        $sql_check = "SELECT * FROM users WHERE phone_user = '$phone'";
        $result_check = mysqli_query($conn, $sql_check);

        if (mysqli_num_rows($result_check) > 0) {
            $message = "Ce numéro est déjà utilisé.";
        } else {
            // Insérer l’utilisateur
            $sql_insert = "INSERT INTO users (nom_user, email_user, phone_user, password_user, code_2fa) 
                           VALUES ('$nom_user', '$email', '$phone', '$password_hashed', '$code_2fa')";
            if (mysqli_query($conn, $sql_insert)) {

                // ✅ ENVOI SMS VIA AFRICA'S TALKING
                require_once 'vendor/autoload.php';

                $username   = "sandbox"; // ⚠️ à remplacer par ton username Africa's Talking
                $apiKey     = "atsk_b0b7dd81f0e75bef264e4ea63ad1810adb47be42bbc00ee8573ca7db2a5a8b1ecbf897f3"; // ⚠️ mets ta clé API
                $AT         = new AfricasTalking($username, $apiKey);

                $sms        = $AT->sms();
                $to         = "+228" . $phone; // Format international
                try {
                    $sms->send([
                        'to'      => $to,
                        'message' => "Bonjour $nom_user, votre code de vérification est : $code_2fa",
                    ]);

                    $_SESSION['register_success'] = "Inscription réussie! Votre code 2FA a été envoyé par SMS.";
                    header('Location: login.php');
                    exit;
                } catch (Exception $e) {
                    $message = "Erreur lors de l'envoi du SMS : " . $e->getMessage();
                }

            } else {
                $message = "Erreur lors de l'inscription. Veuillez réessayer.";
            }
        }
        mysqli_close($conn);
    }
}
?>

<?php include("header.php"); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Tog'Artisans</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .register-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .register-container h2 { margin-bottom: 20px; text-align: center; }
        .form-group label { font-weight: bold; }
        .btn-primary { width: 100%; }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>
<body>
    <div class="register-container">
        <h2>Inscription a Tog'Artisans</h2>
        <form id="registerForm" method="POST" action="register.php">
            <div class="form-group">
                <label for="nom_user">Nom:</label>
                <input type="text" class="form-control" id="nom_user" name="nom_user" required>
            </div>
            <div class="form-group">
                <label for="email">Email (optionnel):</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="form-group">
                <label for="phone">Numéro de téléphone (Togo):</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">+228</span>
                    </div>
                    <input type="text" class="form-control" id="phone" name="phone" required pattern="[9][0-9]{7}" placeholder="ex: 90000000">
                </div>
                <small class="form-text text-muted">Numéro à 8 chiffres commençant par 9.</small>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmer le mot de passe:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary">S'inscrire</button>
        </form>
        <p style="margin-top:15px; text-align:center;">Déjà un compte? <a href="login.php">Connectez-vous ici</a></p>
    </div>

    <script>
        $(document).ready(function() {
            $('#registerForm').on('submit', function(e) {
                var password = $('#password').val();
                var confirmPassword = $('#confirm_password').val();
                if (password !== confirmPassword) {
                    e.preventDefault();
                    toastr.error('Les mots de passe ne correspondent pas.');
                }
            });
        });
    </script>
</body>
</html>

<?php if ($message): ?>
    <div class="alert alert-danger" style="max-width:500px; margin:20px auto;">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<?php include("footer.php"); ?>
<?php