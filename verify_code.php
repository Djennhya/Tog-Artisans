<?php
session_start();

if (isset($_POST['code'])) {
    $entered = trim($_POST['code']);

    if (!isset($_SESSION['verification_code'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Aucun code n’a été généré.'
        ]);
        exit;
    }

    if (time() > $_SESSION['code_expires_at']) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Le code a expiré. Veuillez en demander un nouveau.'
        ]);
        session_destroy();
        exit;
    }

    if ($entered == $_SESSION['verification_code']) {
        echo json_encode([
            'status' => 'success',
            'message' => '✅ Vérification réussie !'
        ]);
        // Ici tu peux valider le compte ou autoriser la connexion
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => '❌ Code incorrect.'
        ]);
    }
}
?>