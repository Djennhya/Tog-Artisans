<?php include("admin/config.php"); ?>
<?php
include("header.php");

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    echo "<div class='container my-5'><p class='text-center text-danger'>Vous devez être connecté pour passer une commande.</p></div>";
    include("footer.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$conn = mysqli_connect("localhost", "root", "", "togartisans");
if (!$conn) {
    die("Erreur de connexion à la base de données : " . mysqli_connect_error());
}

// 🔹 Récupération des infos utilisateur
$user_sql = "SELECT * FROM users WHERE id_user = $user_id";
$user_res = mysqli_query($conn, $user_sql);
$user = mysqli_fetch_assoc($user_res);

// 🔹 Récupération dynamique de la boutique liée au panier
$shop_sql = "
    SELECT s.nom_shop AS nom_boutique, s.numero_flooz, s.numero_tmoney
    FROM shop s
    JOIN cart c ON c.id_shop = s.id_shop
    WHERE c.id_user = $user_id
    LIMIT 1
";
$shop_res = mysqli_query($conn, $shop_sql);

if (!$shop_res) {
    die('Erreur SQL : ' . mysqli_error($conn));
}

$shop = mysqli_fetch_assoc($shop_res);

$nom_shop = $shop ? $shop['nom_boutique'] : 'Boutique inconnue';
$flooz = $shop ? $shop['numero_flooz'] : 'Non défini';
$tmoney = $shop ? $shop['numero_tmoney'] : 'Non défini';
?>

<style>
    body {
        background-color: #f8f9fa;
        font-family: "Poppins", sans-serif;
    }

    .form-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 90vh;
    }

    form {
        background-color: #fff;
        padding: 50px 60px;
        border-radius: 10px;
        width: 100%;
        max-width: 550px;
        font-size: 18px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
    }

    h4 {
        text-align: center;
        margin-bottom: 35px;
        font-weight: 600;
        font-size: 24px;
        color: #333;
    }

    .form-group {
        margin-bottom: 25px;
    }

    label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 18px;
        color: #222;
    }

    .form-control {
        width: 100%;
        padding: 14px 15px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 17px;
    }

    input[type="radio"] {
        transform: scale(1.3);
        margin-right: 8px;
    }

    .payment-section label {
        display: inline-block;
        margin-right: 25px;
        font-weight: 500;
        font-size: 17px;
        color: #333;
    }

    .btn-primary {
        width: 100%;
        padding: 14px 0;
        background-color: #007bff;
        border: none;
        border-radius: 8px;
        font-size: 18px;
        font-weight: 600;
        color: white;
        cursor: pointer;
        transition: background 0.3s ease;
        margin-top: 10px;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .hidden {
        display: none;
    }

    .payment-info-box {
        background: #f1f8ff;
        border-left: 4px solid #007bff;
        padding: 15px 20px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .payment-info-box strong {
        color: #007bff;
    }
</style>

<div class="form-wrapper">
    <form action="process_order.php" method="post" id="orderForm">
        <h4>Informations de Livraison</h4>

        <div class="form-group">
            <label for="nom_user">Nom Complet</label>
            <input type="text" class="form-control" id="nom_user" name="nom_user" 
                value="<?php echo htmlspecialchars($user['nom_user']); ?>" required>
        </div>

        <div class="form-group">
            <label for="adresse_user">Adresse de Livraison</label>
            <input type="text" class="form-control" id="adresse_user" name="adresse_user" 
                value="<?php echo htmlspecialchars($user['adresse_user']); ?>" required>
        </div>

        <div class="form-group">
            <label for="phone_user">Téléphone</label>
            <input type="text" class="form-control" id="phone_user" name="phone_user" 
                value="<?php echo htmlspecialchars($user['phone_user']); ?>" required>
        </div>

        <div class="form-group">
            <label>Mode de Paiement</label>
            <div class="payment-section">
                <label>
                    <input type="radio" name="payment_method" value="cod" required> À la Livraison
                </label>
                <label>
                    <input type="radio" name="payment_method" value="online" required> En Ligne (Flooz / TMoney)
                </label>
            </div>
        </div>

        <div id="paymentDetails" class="hidden">
            <div class="payment-info-box">
                <p><strong>Boutique :</strong> <?php echo htmlspecialchars($nom_shop); ?></p>
                <p><strong>Numéros de la Boutique :</strong></p>
                <p>Flooz : <strong><?php echo htmlspecialchars($flooz); ?></strong></p>
                <p>TMoney : <strong><?php echo htmlspecialchars($tmoney); ?></strong></p>
                <p>Veuillez effectuer votre dépôt avant de confirmer la commande.</p>
            </div>

            <div class="form-group">
                <label for="transaction_id">Preuve de Paiement</label>
                <input type="text" class="form-control" id="transaction_id" name="transaction_id" 
                    placeholder="Entrez le code ou numéro de transaction">
                <small class="text-muted">Exemple : Code Flooz ou TMoney reçu après votre dépôt.</small>
            </div>
        </div>

        <button type="submit" class="btn-primary">Confirmer la Commande</button>
    </form>
</div>

<script>
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const paymentDetails = document.getElementById('paymentDetails');

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            paymentDetails.classList.toggle('hidden', this.value !== 'online');
        });
    });
</script>

<?php
mysqli_close($conn);
include("footer.php");
?>
