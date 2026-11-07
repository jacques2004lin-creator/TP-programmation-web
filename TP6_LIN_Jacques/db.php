<?php
/**
 * Connexion à la base de données via PDO
 * Compatible à la fois avec l'environnement local (Docker) et la production (InfinityFree)
 */

$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

if (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) {
    // -------------------------------------------------------------
    // Configuration Locale Docker
    // -------------------------------------------------------------
    $servername = "db";
    $dbname     = "atelier_cafe";
    $username   = "root";
    $password   = "root";
} else {
    // -------------------------------------------------------------
    // Configuration InfinityFree
    // Remplacer avec les informations indiquées dans votre espace client InfinityFree :
    // -------------------------------------------------------------
    $servername = "sqlXXX.infinityfree.com";        // MySQL Hostname (ex: sql123.infinityfree.com)
    $dbname     = "if0_XXXXXXXX_atelier_cafe";       // Nom complet de la BDD (ex: if0_38123456_atelier_cafe)
    $username   = "if0_XXXXXXXX";                   // MySQL Username (ex: if0_38123456)
    $password   = "VOTRE_MOT_DE_PASSE";             // MySQL Password (votre mot de passe de compte)
}

$charset = "utf8mb4";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

$dsn = "mysql:host=$servername;dbname=$dbname;charset=$charset";

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
