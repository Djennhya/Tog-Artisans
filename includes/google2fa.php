<?php
require_once __DIR__ . '/../vendor/autoload.php';

use RobThree\Auth\TwoFactorAuth;

/**
 * Retourne une instance TwoFactorAuth
 */
function tfa_instance(string $appName = 'TogArtisans'): TwoFactorAuth {
    return new TwoFactorAuth($appName);
}