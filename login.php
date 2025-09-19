<!--La double authentification 2FA-->
<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $code_2fa = $_POST['code_2fa'] ?? '';

    $conn = mysqli_connect("localhost", "root", "", "togartisans");
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);
    $sql = "SELECT * FROM users WHERE email_user = '$email'";
    $result = mysqli_query($conn, $sql);
    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $user['password_user'])) {
            if ($user['code_2fa'] === $code_2fa) {
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['user_name'] = $user['nom_user'];
                header('Location: index.php');
                exit;
            } else {
                $message = "Code 2FA incorrect.";
            }
        } else {
            $message = "Mot de passe incorrect.";
        }
    } else {
        $message = "Email non trouvé.";
    }
    mysqli_close($conn);
}
?>
<?php include("header.php"); ?>
<div class="container" style="margin-top:50px; max-width:400px;">
    <h2>Connexion</h2>
    <?php if ($message): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <form method="POST" action="login.php">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="code_2fa">Code 2FA:</label>
            <input type="text" class="form-control" id="code_2fa" name="code_2fa" required>
        </div>
        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>
    <p style="margin-top:15px;">Pas encore de compte? <a href="register.php">Inscrivez-vous ici</a></p>
</div>
<?php include("footer.php"); ?>