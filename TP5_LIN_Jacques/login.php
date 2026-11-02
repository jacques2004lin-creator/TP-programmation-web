<?php
session_start();

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>

<body>

    <h1>Connexion</h1>

    <!-- message de succès-->
    <?php if (!empty($_SESSION['flash_success'])) : ?>
        <p style="color: green; font-weight: bold;">
            <?= e($_SESSION['flash_success']) ?>
        </p>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>

    <!-- messages d'erreur -->
    <?php if (!empty($_SESSION['flash_error'])) : ?>
        <p style="color: red; font-weight: bold;">
            <?= e($_SESSION['flash_error']) ?>
        </p>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <form action="login_handler.php" method="POST">
        <p>
            <label for="username">Nom d'utilisateur :</label><br>
            <input type="text" name="username" id="username" value="<?= e($_SESSION['old_username'] ?? '') ?>" required>
        </p>

        <p>
            <label for="password">Mot de passe :</label><br>
            <input type="password" name="password" id="password" required>
        </p>

        <button type="submit">Se connecter</button>
    </form>
    <?php unset($_SESSION['old_username']); ?>

    <p>Pas encore de compte ? <a href="register.php">Créer un compte</a></p>
    <p><a href="list_posts.php">Retourner aux articles</a></p>

</body>

</html>