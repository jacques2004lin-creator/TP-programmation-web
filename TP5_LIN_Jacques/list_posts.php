<?php
require_once 'config.php';

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

$sql = "SELECT
            posts.id,
            posts.title,
            posts.body,
            posts.created_at,
            users.username AS author
        FROM posts
        INNER JOIN users ON posts.user_id = users.id
        ORDER BY posts.created_at DESC";

$stmt = $pdo->query($sql);
$posts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Liste des articles</title>
</head>

<body>
    <nav>
        <?php if (isset($_SESSION['user_id'])): ?>
            <span>Connecté en tant que <strong><?= e($_SESSION['username']) ?></strong></span>
            | <a href="create_post.php">Nouvel article</a>
            | <a href="logout.php">Se déconnecter</a>
        <?php else: ?>
            <a href="login.php">Se connecter</a>
            | <a href="register.php">S'inscrire</a>
        <?php endif; ?>
    </nav>
    <?php if (!empty($_SESSION['flash_success'])) : ?>
        <p style="color: green; font-weight: bold;">
            <?= htmlspecialchars($_SESSION['flash_success']) ?>
        </p>
        <?php unset($_SESSION['flash_success']); ?>
    <?php endif; ?>
    <h1>Articles du Blog</h1>

    <?php if (empty($posts)): ?>
        <p>Aucun article trouvé.</p>
    <?php else: ?>
        <?php foreach ($posts as $post): ?>
            <?php
            $fullBody = $post['body'];
            $excerpt = mb_substr($fullBody, 0, 200);
            if (mb_strlen($fullBody) > 200) {
                $excerpt .= '...';
            }

            $date = new DateTime($post['created_at']);
            $formattedDate = $date->format('d/m/Y à H:i');
            ?>
            <article>
                <h2><?= e($post['title']) ?></h2>

                <div>
                    Publié par<?= e($post['author']) ?> le <?= e($formattedDate) ?>
                </div>
                <p><?= nl2br(e($excerpt)) ?></p>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>

</body>

</html>