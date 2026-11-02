<?php
session_start();

// sécurisation de l'accès
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'config.php';

// vérifier qu'un ID de boisson a bien été fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: admin_services.php');
    exit;
}

$id = (int)$_GET['id'];
$message_action = '';

// traitement de la modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_service'])) {
    $nom = trim($_POST['nom'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $prix = (float)str_replace(',', '.', $_POST['prix'] ?? 0);
    $category_id = (int)($_POST['category_id'] ?? 0);
    $image_url = trim($_POST['image_url'] ?? '');

    if (!empty($nom) && $prix > 0 && $category_id > 0) {
        try {
            $sql = "UPDATE services 
                    SET nom = :nom, description = :description, prix = :prix, image_url = :image_url, category_id = :category_id 
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nom' => $nom,
                ':description' => $description,
                ':prix' => $prix,
                ':image_url' => empty($image_url) ? null : $image_url,
                ':category_id' => $category_id,
                ':id' => $id
            ]);
            $message_action = "<div class='alert alert-success rounded-0 mb-4'>La boisson a été modifiée avec succès.</div>";
        } catch (PDOException $e) {
            $message_action = "<div class='alert alert-danger rounded-0 mb-4'>Erreur lors de la modification en base de données.</div>";
        }
    } else {
        $message_action = "<div class='alert alert-warning rounded-0 mb-4'>Veuillez remplir les champs obligatoires (Nom, Prix, Catégorie).</div>";
    }
}

// récupération des données de la boisson pour pré-remplir le formulaire
$stmtSelect = $pdo->prepare("SELECT * FROM services WHERE id = :id");
$stmtSelect->execute([':id' => $id]);
$service = $stmtSelect->fetch();

// si la boisson n'existe pas, on redirige vers la liste
if (!$service) {
    header('Location: admin_services.php');
    exit;
}

// récupération des catégories pour la liste déroulante
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modifier une boisson - Administration</title>
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
                        <a class="nav-link active" href="admin_services.php">Gérer la carte</a>
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
        <div class="row">
            <div class="col-lg-6 mx-auto">

                <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-dark">
                    <h1 class="h3 fw-bold text-uppercase m-0">Modifier : <?= htmlspecialchars($service['nom']) ?></h1>
                    <a href="admin_services.php" class="btn btn-outline-dark rounded-0">&larr; Retour à la carte</a>
                </div>

                <?= $message_action ?>

                <div class="card rounded-0 border-dark shadow-sm">
                    <div class="card-body p-4">
                        <form method="POST" action="edit_service.php?id=<?= $id ?>">

                            <div class="mb-3">
                                <label for="nom" class="form-label fw-bold">Nom de la boisson <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-0" id="nom" name="nom" value="<?= htmlspecialchars($service['nom']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label fw-bold">Catégorie <span class="text-danger">*</span></label>
                                <select class="form-select rounded-0 border-dark" id="category_id" name="category_id" required>
                                    <option value="">Choisir une catégorie...</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <!-- on sélectionne automatiquement la bonne catégorie -->
                                        <option value="<?= $cat['id'] ?>" <?= $service['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['nom']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="prix" class="form-label fw-bold">Prix (€) <span class="text-danger">*</span></label>
                                <input type="number" step="0.10" class="form-control rounded-0" id="prix" name="prix" value="<?= htmlspecialchars($service['prix']) ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Description (facultative)</label>
                                <textarea class="form-control rounded-0" id="description" name="description" rows="3"><?= htmlspecialchars($service['description'] ?? '') ?></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="image_url" class="form-label fw-bold">Chemin de l'image (facultatif)</label>
                                <input type="text" class="form-control rounded-0" id="image_url" name="image_url" value="<?= htmlspecialchars($service['image_url'] ?? '') ?>">
                                <?php if (!empty($service['image_url'])): ?>
                                    <div class="mt-2 text-muted small">Image actuelle : <?= htmlspecialchars($service['image_url']) ?></div>
                                <?php endif; ?>
                            </div>

                            <button type="submit" name="edit_service" class="btn btn-dark w-100 py-3 rounded-0 text-uppercase fw-bold">Enregistrer les modifications</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>