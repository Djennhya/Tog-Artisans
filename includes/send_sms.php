<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use AfricasTalking\SDK\AfricasTalking;

// Charger .env (racine du projet)
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

/**
 * send_sms(string $to, string $message) : array
 * Retourne ['ok' => bool, 'msg' => mixed]
 */
function send_sms(string $to, string $message): array {
    $logFile = __DIR__ . '/../logs/sms_send.log';
    if (!is_dir(dirname($logFile))) mkdir(dirname($logFile), 0777, true);

    // Lecture robuste des credentials : getenv OR $_ENV OR $_SERVER
    $username = getenv('AT_USERNAME') ?: ($_ENV['AT_USERNAME'] ?? ($_SERVER['AT_USERNAME'] ?? null));
    $apiKey   = getenv('AT_API_KEY')   ?: ($_ENV['AT_API_KEY']   ?? ($_SERVER['AT_API_KEY']   ?? null));

    // Assurer que getenv retourne aussi ces valeurs (fallback)
    if ($username && !getenv('AT_USERNAME')) putenv("AT_USERNAME={$username}");
    if ($apiKey && !getenv('AT_API_KEY')) putenv("AT_API_KEY={$apiKey}");

    if (!$username || !$apiKey) {
        $err = "AT credentials missing (AT_USERNAME/AT_API_KEY)";
        // log plus d'info sans exposer la clé complète en clair dans prod
        file_put_contents($logFile, date('c') . " ERROR {$err} env_username=" . var_export(isset($username), true) . " env_api=" . var_export(isset($apiKey), true) . PHP_EOL, FILE_APPEND);
        return ['ok' => false, 'msg' => $err];
    }

    // normaliser numéro
    $to = trim($to);
    $digits = preg_replace('/[^\d]/', '', $to);
    if (strlen($digits) === 8) $to = '+228' . $digits;
    elseif (strpos($to, '+') !== 0) $to = '+' . $digits;

    if (!preg_match('/^\+\d{8,15}$/', $to)) {
        $err = "Numéro invalide: {$to}";
        file_put_contents($logFile, date('c') . " ERROR {$err}\n", FILE_APPEND);
        return ['ok' => false, 'msg' => $err];
    }

    try {
        $AT = new AfricasTalking($username, $apiKey);
        $sms = $AT->sms();
        $result = $sms->send([
            'to'      => $to,
            'message' => $message,
        ]);
        file_put_contents($logFile, date('c') . " OK to={$to} res=" . var_export($result, true) . PHP_EOL, FILE_APPEND);
        return ['ok' => true, 'msg' => $result];
    } catch (\Throwable $e) {
        file_put_contents($logFile, date('c') . " ERROR to={$to} err=" . $e->getMessage() . PHP_EOL, FILE_APPEND);
        return ['ok' => false, 'msg' => $e->getMessage()];
    }
}