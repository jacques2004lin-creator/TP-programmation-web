<?php
session_start();
require_once 'config.php';

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

$errors = [];
$username = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    //validation des champs
    if ($username === '') {
        $errors[] = 'Le nom d’utilisateur est obligatoire.';
    } elseif (mb_strlen($username) < 3) {
        $errors[] = 'Le nom d’utilisateur doit comporter au moins 3 caractères.';
    }

    if ($password === '') {
        $errors[] = 'Le mot de passe est obligatoire.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Le mot de passe doit contenir au moins 6 caractères.';
    }

    if ($password !== $passwordConfirm) {
        $errors[] = 'Les deux mots de passe ne correspondent pas.';
    }

    //vérification de l'unicité de l'identifiant
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username LIMIT 1');
            $stmt->execute([':username' => $username]);

            if ($stmt->fetch()) {
                $errors[] = 'Ce nom d’utilisateur est déjà pris.';
            }
        } catch (PDOException $e) {
            error_log('Erreur SQL vérification username : ' . $e->getMessage());
            $errors[] = 'Une erreur est survenue lors de la vérification du compte.';
        }
    }

    //hachage et insertion en base
    if (empty($errors)) {
        try {
            $passwordHash = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
            $stmt->execute([
                ':username' => $username,
                ':password' => $passwordHash,
            ]);

            $_SESSION['flash_success'] = 'Votre compte a été créé avec succès ! Vous pouvez vous connecter.';
            header('Location: login.php');
            exit;
        } catch (PDOException $e) {
            error_log('Erreur SQL insertion user : ' . $e->getMessage());
            $errors[] = 'Impossible de créer le compte pour le moment.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>

<body>

    <h1>Créer un compte</h1>

    <?php if (!empty($errors)): ?>
        <ul style="color: red;">
            <?php foreach ($errors as $error): ?>
                <li><?= e($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <p>
            <label for="username">Nom d'utilisateur :</label><br>
            <input type="text" name="username" id="username" value="<?= e($username) ?>" required>
        </p>

        <p>
            <label for="password">Mot de passe :</label><br>
            <input type="password" name="password" id="password" required>
        </p>

        <p>
            <label for="password_confirm">Confirmer le mot de passe :</label><br>
            <input type="password" name="password_confirm" id="password_confirm" required>
        </p>

        <button type="submit">S'inscrire</button>
    </form>

    <p>Déjà inscrit ? <a href="login.php">Se connecter</a></p>

</body>

</html>