<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$error = $_SESSION['error'] ?? null;
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajouter un article</title>
</head>

<body>
    <h1>Nouvel article</h1>

    <?php if ($error) : ?>
        <p><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="store_post.php" method="POST">
        <div>
            <label for="title">Titre :</label><br>
            <input type="text" id="title" name="title" required>
        </div>
        <br>
        <div>
            <label for="body">Contenu :</label><br>
            <textarea id="body" name="body" rows="6" cols="40" required></textarea>
        </div>
        <br>
        <button type="submit">Publier</button>
    </form>
</body>

</html>