<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'db.php';

$message_action = '';

// traitement de la modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_section'])) {
    $id = (int)$_POST['section_id'];
    $titre = trim($_POST['titre'] ?? '');
    $contenu_texte = trim($_POST['contenu_texte'] ?? '');

    if (!empty($titre) && !empty($contenu_texte)) {
        try {
            $stmt = $pdo->prepare("UPDATE pages_content SET titre = :titre, contenu_texte = :contenu_texte WHERE id = :id");
            $stmt->execute([
                ':titre' => $titre,
                ':contenu_texte' => $contenu_texte,
                ':id' => $id
            ]);
            $message_action = "<div class='alert alert-success rounded-0 mb-4'>La section a été mise à jour avec succès.</div>";
        } catch (PDOException $e) {
            $message_action = "<div class='alert alert-danger rounded-0 mb-4'>Erreur lors de la mise à jour des textes.</div>";
        }
    } else {
        $message_action = "<div class='alert alert-warning rounded-0 mb-4'>Les champs titre et contenu ne peuvent pas être vides.</div>";
    }
}

// récupération des textes
// on récupère tous les contenus, triés par page puis par identifiant
$stmtSelect = $pdo->query("SELECT * FROM pages_content ORDER BY page_nom, id");
$sections = $stmtSelect->fetchAll();
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestion des textes - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="index.css" />
</head>

<body class="bg-light">

    <!-- navigation Admin -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="dashboard.php">L'Atelier - ADMIN</a>
            <button class="navbar-toggler rounded-0" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">Tableau de bord</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_services.php">Gérer la carte</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="admin_pages.php">Gérer les textes</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item d-flex align-items-center me-3">
                        <span class="text-light small">Connecté en tant que : <strong><?= htmlspecialchars($_SESSION['admin_email']) ?></strong></span>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm rounded-0 mt-1" href="logout.php">Déconnexion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-dark">
            <h1 class="h2 text-uppercase fw-bold m-0">Édition du contenu (Pages)</h1>
        </div>

        <?= $message_action ?>

        <p class="text-muted mb-4">
            Modifiez les textes des différentes sections du site ci-dessous. Vous pouvez utiliser des balises HTML simples comme <code>&lt;strong&gt;</code> pour mettre en gras, <code>&lt;em&gt;</code> pour l'italique ou <code>&lt;br&gt;</code> pour sauter une ligne.
        </p>

        <div class="row g-4">
            <?php foreach ($sections as $sec): ?>
                <div class="col-lg-6">
                    <div class="card rounded-0 border-dark h-100 shadow-sm">
                        <div class="card-header bg-dark text-white rounded-0 py-2 d-flex justify-content-between align-items-center">
                            <h2 class="h6 mb-0 text-uppercase">Page : <?= htmlspecialchars($sec['page_nom']) ?></h2>
                            <span class="badge bg-light text-dark rounded-0">Section : <?= htmlspecialchars($sec['section']) ?></span>
                        </div>
                        <div class="card-body bg-white p-4">
                            <form method="POST" action="admin_pages.php">
                                <!-- champ caché pour transmettre l'ID de la section à modifier -->
                                <input type="hidden" name="section_id" value="<?= $sec['id'] ?>">

                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Titre de la section</label>
                                    <input type="text" class="form-control rounded-0 border-dark" name="titre" value="<?= htmlspecialchars($sec['titre']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted text-uppercase">Contenu principal</label>
                                    <textarea class="form-control rounded-0 border-dark" name="contenu_texte" rows="5" required><?= htmlspecialchars($sec['contenu_texte']) ?></textarea>
                                </div>

                                <button type="submit" name="update_section" class="btn btn-dark w-100 py-2 rounded-0 text-uppercase fw-bold">Mettre à jour cette section</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($sections)): ?>
                <div class="col-12">
                    <div class="alert alert-info rounded-0">Aucun contenu dynamique trouvé dans la base de données.</div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>