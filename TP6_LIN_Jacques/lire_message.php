<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'config.php';

// vérifier qu'un ID de message a bien été fourni dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$message_id = (int)$_GET['id'];

// mettre à jour le statut du message à "lu" dans la base de données
$stmtUpdate = $pdo->prepare("UPDATE messages_contact SET statut = 'lu' WHERE id = :id");
$stmtUpdate->execute([':id' => $message_id]);

// récupérer les informations complètes du message pour les afficher
$stmtSelect = $pdo->prepare("SELECT * FROM messages_contact WHERE id = :id");
$stmtSelect->execute([':id' => $message_id]);
$message = $stmtSelect->fetch();

// si le message n'existe pas, on redirige vers le tableau de bord
if (!$message) {
    header('Location: dashboard.php');
    exit;
}
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Lecture du message - Administration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="index.css" />
</head>

<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="dashboard.php">L'Atelier - ADMIN</a>
        </div>
    </nav>

    <main class="container py-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 fw-bold text-uppercase m-0">Détails du message</h1>
                    <a href="dashboard.php" class="btn btn-outline-dark rounded-0">&larr; Retour au tableau de bord</a>
                </div>

                <div class="card rounded-0 border-dark shadow-sm">
                    <div class="card-header bg-dark text-white rounded-0 py-3">
                        <h2 class="h5 mb-0">Sujet : <?= htmlspecialchars($message['sujet']) ?></h2>
                    </div>
                    <div class="card-body p-4">
                        <div class="row border-bottom pb-3 mb-3">
                            <div class="col-md-6">
                                <p class="mb-1 text-muted small">Expéditeur</p>
                                <p class="fw-bold mb-0"><?= htmlspecialchars($message['nom']) ?></p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1 text-muted small">Adresse e-mail</p>
                                <p class="fw-bold mb-0">
                                    <a href="mailto:<?= htmlspecialchars($message['email']) ?>" class="text-dark">
                                        <?= htmlspecialchars($message['email']) ?>
                                    </a>
                                </p>
                            </div>
                        </div>
                        <div class="mb-2">
                            <p class="mb-1 text-muted small">Reçu le</p>
                            <p class="mb-3"><?= date('d/m/Y à H:i', strtotime($message['date_envoi'])) ?></p>
                        </div>
                        <div class="bg-light p-4 border">
                            <!-- permet de conserver les sauts de ligne tapés par l'utilisateur -->
                            <p class="mb-0"><?= nl2br(htmlspecialchars($message['message'])) ?></p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

</body>

</html>