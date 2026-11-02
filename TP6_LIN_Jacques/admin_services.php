<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'config.php';

$message_action = '';

// traitement de la suppression
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id_to_delete = (int)$_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM services WHERE id = :id");
        $stmt->execute([':id' => $id_to_delete]);
        $message_action = "<div class='alert alert-success rounded-0'>La boisson a été supprimée avec succès.</div>";
    } catch (PDOException $e) {
        $message_action = "<div class='alert alert-danger rounded-0'>Erreur lors de la suppression.</div>";
    }
}

// traitement de l'ajout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_service'])) {
    $nom = trim($_POST['nom'] ?? '');
    $description = trim($_POST['description'] ?? '');
    // remplacement de la virgule par un point pour la base de données MySQL
    $prix = (float)str_replace(',', '.', $_POST['prix'] ?? 0);
    $category_id = (int)($_POST['category_id'] ?? 0);
    $image_url = trim($_POST['image_url'] ?? '');

    if (!empty($nom) && $prix > 0 && $category_id > 0) {
        try {
            $sql = "INSERT INTO services (nom, description, prix, image_url, category_id) VALUES (:nom, :description, :prix, :image_url, :category_id)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nom' => $nom,
                ':description' => $description,
                ':prix' => $prix,
                ':image_url' => empty($image_url) ? null : $image_url,
                ':category_id' => $category_id
            ]);
            $message_action = "<div class='alert alert-success rounded-0'>Boisson ajoutée avec succès !</div>";
        } catch (PDOException $e) {
            $message_action = "<div class='alert alert-danger rounded-0'>Erreur lors de l'ajout en base de données.</div>";
        }
    } else {
        $message_action = "<div class='alert alert-warning rounded-0'>Veuillez remplir les champs obligatoires (Nom, Prix, Catégorie).</div>";
    }
}

// récupération des données
// Récupération des catégories pour la liste déroulante du formulaire d'ajout
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

// récupération de tous les services avec le nom de leur catégorie via une jointure SQL (JOIN)
$sql_services = "
    SELECT s.*, c.nom AS nom_categorie 
    FROM services s 
    JOIN categories c ON s.category_id = c.id 
    ORDER BY c.id, s.nom
";
$services = $pdo->query($sql_services)->fetchAll();
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestion de la carte - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="index.css" />
</head>

<body class="bg-light">

    <!-- navigation Admin (identique au dashboard) -->
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
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom border-dark pb-3">
            <h1 class="h2 text-uppercase fw-bold m-0">Gestion de la Carte</h1>
        </div>

        <?= $message_action ?>

        <div class="row g-5">
            <!-- formulaire d'ajout d'une boisson -->
            <div class="col-lg-4">
                <div class="card rounded-0 border-dark shadow-sm sticky-top" style="top: 80px; z-index: 1;">
                    <div class="card-header bg-dark text-white rounded-0 py-3">
                        <h2 class="h5 mb-0 text-uppercase">Ajouter une boisson</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="admin_services.php">
                            <div class="mb-3">
                                <label for="nom" class="form-label fw-bold">Nom de la boisson <span class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-0" id="nom" name="nom" required>
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label fw-bold">Catégorie <span class="text-danger">*</span></label>
                                <select class="form-select rounded-0 border-dark" id="category_id" name="category_id" required>
                                    <option value="">Choisir une catégorie...</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="prix" class="form-label fw-bold">Prix (€) <span class="text-danger">*</span></label>
                                <input type="number" step="0.10" class="form-control rounded-0" id="prix" name="prix" placeholder="Ex: 4.50" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Description (facultative)</label>
                                <textarea class="form-control rounded-0" id="description" name="description" rows="2"></textarea>
                            </div>

                            <div class="mb-4">
                                <label for="image_url" class="form-label fw-bold">Chemin de l'image (facultatif)</label>
                                <input type="text" class="form-control rounded-0" id="image_url" name="image_url" placeholder="Ex: images/nouveau_cafe.jpg">
                                <div class="form-text">Si laissé vide, aucune image ne s'affichera.</div>
                            </div>

                            <button type="submit" name="add_service" class="btn btn-dark w-100 py-2 rounded-0 text-uppercase fw-bold">Ajouter à la carte</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- liste des boissons actuelles -->
            <div class="col-lg-8">
                <div class="card rounded-0 border-dark shadow-sm">
                    <div class="card-header bg-white border-dark py-3">
                        <h2 class="h5 mb-0 text-uppercase">Boissons à la carte</h2>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Catégorie</th>
                                    <th>Nom</th>
                                    <th>Prix</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($services as $srv): ?>
                                    <tr>
                                        <td><span class="badge bg-secondary rounded-0"><?= htmlspecialchars($srv['nom_categorie']) ?></span></td>
                                        <td class="fw-bold"><?= htmlspecialchars($srv['nom']) ?></td>
                                        <td><?= number_format($srv['prix'], 2, ',', ' ') ?> €</td>
                                        <td class="text-end">
                                            <a href="edit_service.php?id=<?= $srv['id'] ?>" class="btn btn-sm btn-outline-dark rounded-0 me-1">Modifier</a>
                                            <!-- bouton de suppression avec confirmation JavaScript -->
                                            <a href="admin_services.php?delete=<?= $srv['id'] ?>" class="btn btn-sm btn-danger rounded-0" onclick="return confirm('Voulez-vous vraiment supprimer <?= addslashes(htmlspecialchars($srv['nom'])) ?> ?');">Supprimer</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php if (empty($services)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Aucune boisson dans la base de données.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>