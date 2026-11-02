<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: create_post.php');
    exit;
}

$title = trim($_POST['title'] ?? '');
$body  = trim($_POST['body'] ?? '');
$userId = $_SESSION['user_id'] ?? filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);

$errors = [];
if ($title === '') {
    $errors[] = 'Le titre est obligatoire.';
}
if ($body === '') {
    $errors[] = 'Le contenu ne peut pas être vide.';
}
if (!$userId) {
    $errors[] = 'Identifiant auteur manquant ou invalide.';
}

if (!empty($errors)) {
    $_SESSION['flash_error'] = implode(' ', $errors);
    header('Location: create_post.php');
    exit;
}

try {
    $sql = 'INSERT INTO posts (user_id, title, body) VALUES (:user_id, :title, :body)';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':user_id' => $userId,
        ':title' => $title,
        ':body' => $body,
    ]);

    // message de succès stocké en session (Flash message)
    $_SESSION['flash_success'] = 'L’article a été publié avec succès !';
    header('Location: list_posts.php');
    exit;
} catch (PDOException $e) {
    // enregistrement de l'erreur réelle dans les logs du serveur (non visible au visiteur)
    error_log('Erreur SQL dans store_post.php : ' . $e->getMessage());

    // message générique pour l'utilisateur
    $_SESSION['flash_error'] = 'Une erreur est survenue lors de l’enregistrement. Veuillez réessayer ultérieurement.';
    header('Location: create_post.php');
    exit;
}
