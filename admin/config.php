<?php
session_start();

// Paramètres de connexion à la base de données
$host = "localhost";       // Hôte MySQL (souvent localhost)
$dbname = "togartisans"; // Nom de ta base
$username = "root";         // Utilisateur MySQL
$password = "";             // Mot de passe MySQL

/* Connexion à la base de données MySQL */
$link = mysqli_connect("localhost", "root", "", "togartisans");

// Vérifier la connexion
if($link === false){
    die("ERREUR : Impossible de se connecter. " . mysqli_connect_error());
}

try {
    // Connexion avec PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    // Définir le mode d'erreur sur Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Message d’erreur si la connexion échoue
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Démarrage de la session pour gérer les connexions administrateurs
if (session_status() === PHP_SESSION_NONE) {
}

// Optionnel : définir le fuseau horaire
date_default_timezone_set("Africa/Lome");

// Exemple de constante globale
define("SITE_NAME", "Administration - Artisapp");

