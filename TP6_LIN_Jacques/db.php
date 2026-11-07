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
    // Configuration InfinityFree (Production)
    // -------------------------------------------------------------
    $servername = "sql213.infinityfree.com";
    $dbname     = "if0_43376768_atelier_cafe";
    $username   = "if0_43376768";
    $password   = "bm3QJv1l6xM";
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
