<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

//validation sommaire
if ($username === '' || $password === '') {
    $_SESSION['flash_error'] = 'Veuillez remplir tous les champs.';
    $_SESSION['old_username'] = $username;
    header('Location: login.php');
    exit;
}

//recherche du compte en base
try {
    $stmt = $pdo->prepare('SELECT id, username, password FROM users WHERE username = :username LIMIT 1');
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch();

    //vérification du mot de passe avec password_verify()
    if ($user && password_verify($password, $user['password'])) {
        // Régénération de l'ID de session pour éviter la fixation de session
        session_regenerate_id(true);

        // Enregistrement de l'auteur dans la session
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['username'] = $user['username'];

        $_SESSION['flash_success'] = 'Connexion réussie. Bon retour, ' . $user['username'] . ' !';
        header('Location: list_posts.php');
        exit;
    }

    //message générique si l'utilisateur n'existe pas ou si le mot de passe est faux
    $_SESSION['flash_error'] = 'Identifiants invalides.';
    $_SESSION['old_username'] = $username;
    header('Location: login.php');
    exit;
} catch (PDOException $e) {
    error_log('Erreur SQL lors de l’authentification : ' . $e->getMessage());
    $_SESSION['flash_error'] = 'Un problème technique est survenu. Veuillez réessayer.';
    header('Location: login.php');
    exit;
}
