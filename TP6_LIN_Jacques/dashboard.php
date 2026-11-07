<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'db.php';

$stmtReservations = $pdo->query("SELECT * FROM reservations ORDER BY date_heure DESC LIMIT 10");
$reservations = $stmtReservations->fetchAll();

$stmtMessages = $pdo->query("SELECT * FROM messages_contact ORDER BY date_envoi DESC LIMIT 10");
$messages = $stmtMessages->fetchAll();
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tableau de bord - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="index.css" />
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="dashboard.php">L'Atelier - ADMIN</a>
            <button class="navbar-toggler rounded-0" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">Tableau de bord</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_services.php">Gérer la carte</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin_pages.php">Gérer les textes</a>
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

    <main class="container-fluid py-5 px-4">
        <h1 class="h2 mb-5 text-uppercase fw-bold border-bottom border-dark pb-3">Vue d'ensemble</h1>

        <div class="row g-4">

            <!-- colonne réservations -->
            <div class="col-lg-6">
                <div class="card rounded-0 border-dark h-100 shadow-sm">
                    <div class="card-header bg-dark text-white rounded-0 py-3">
                        <h2 class="h5 mb-0 text-uppercase">Dernières Réservations</h2>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date & Heure</th>
                                    <th>Nom</th>
                                    <th>Pers.</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reservations as $res): ?>
                                    <tr>
                                        <td><strong><?= date('d/m/Y H:i', strtotime($res['date_heure'])) ?></strong></td>
                                        <td><?= htmlspecialchars($res['nom']) ?></td>
                                        <td><?= (int)$res['nb_personnes'] ?></td>
                                        <td>
                                            <?php if ($res['statut'] === 'en attente'): ?>
                                                <span class="badge bg-warning text-dark rounded-0">En attente</span>
                                            <?php elseif ($res['statut'] === 'confirmée'): ?>
                                                <span class="badge bg-success rounded-0">Confirmée</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger rounded-0">Annulée</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <?php if ($res['statut'] !== 'confirmée'): ?>
                                                    <a href="update_reservation.php?id=<?= $res['id'] ?>&action=confirmer" class="btn btn-sm btn-outline-success rounded-0" title="Confirmer la réservation">O</a>
                                                <?php endif; ?>
                                                <?php if ($res['statut'] !== 'annulée'): ?>
                                                    <a href="update_reservation.php?id=<?= $res['id'] ?>&action=annuler" class="btn btn-sm btn-outline-danger rounded-0" title="Annuler la réservation">X</a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php if (empty($reservations)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Aucune réservation pour le moment.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- colonne messages de contact -->
            <div class="col-lg-6">
                <div class="card rounded-0 border-dark h-100 shadow-sm">
                    <div class="card-header bg-dark text-white rounded-0 py-3">
                        <h2 class="h5 mb-0 text-uppercase">Derniers Messages</h2>
                    </div>
                    <div class="card-body p-0 table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Reçu le</th>
                                    <th>Expéditeur</th>
                                    <th>Sujet</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($messages as $msg): ?>
                                    <tr class="<?= $msg['statut'] === 'non lu' ? 'table-secondary fw-bold' : '' ?>">
                                        <td class="small text-muted"><?= date('d/m/Y', strtotime($msg['date_envoi'])) ?></td>
                                        <td>
                                            <?= htmlspecialchars($msg['nom']) ?>
                                            <?php if ($msg['statut'] === 'non lu'): ?>
                                                <span class="badge bg-danger ms-1 rounded-pill" style="font-size: 0.6rem;">Nouveau</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($msg['sujet']) ?></td>
                                        <td>
                                            <a href="lire_message.php?id=<?= $msg['id'] ?>" class="btn btn-sm btn-dark rounded-0">Lire</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php if (empty($messages)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Aucun message reçu.</td>
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