<?php
// démarrage de la session (doit toujours être la première instruction)
session_start();

require_once 'config.php';

// si l'administrateur est déjà connecté, on le redirige vers le tableau de bord
if (isset($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error_message = '';

// traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        // Recherche de l'admin par son email
        $stmt = $pdo->prepare("SELECT id, email, password_hash FROM admins WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $admin = $stmt->fetch();

        // vérification de l'existence de l'utilisateur et de la validité du mot de passe
        if ($admin && password_verify($password, $admin['password_hash'])) {
            // Création des variables de session
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_email'] = $admin['email'];

            // Redirection vers l'espace d'administration
            header('Location: dashboard.php');
            exit;
        } else {
            $error_message = "<div class='alert alert-danger'>Identifiants incorrects.</div>";
        }
    } else {
        $error_message = "<div class='alert alert-warning'>Veuillez remplir tous les champs.</div>";
    }
}
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Connexion Administration - L'Atelier</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet" />
    <link rel="stylesheet" href="index.css" />
    <style>
        /* style spécifique pour centrer la page de login */
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            background: #fff;
            padding: 30px;
            border: 2px solid var(--black);
            box-shadow: 5px 5px 0px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="text-center mb-4">
            <h1 class="h3 text-uppercase fw-bold">L'Atelier</h1>
            <p class="text-muted">Espace d'administration</p>
        </div>

        <?= $error_message ?>

        <form method="POST" action="login.php">
            <div class="mb-3">
                <label for="email" class="form-label fw-bold">Adresse e-mail</label>
                <input
                    type="email"
                    class="form-control rounded-0"
                    id="email"
                    name="email"
                    placeholder="admin@atelierdecafe.fr"
                    required>
            </div>
            <div class="mb-4">
                <label for="password" class="form-label fw-bold">Mot de passe</label>
                <input
                    type="password"
                    class="form-control rounded-0"
                    id="password"
                    name="password"
                    required>
            </div>
            <button type="submit" name="submit_login" class="btn btn-custom w-100 py-2 mb-3">
                Se connecter
            </button>
        </form>

        <div class="text-center">
            <a href="index.php" class="text-decoration-none text-dark small">&larr; Retour au site</a>
        </div>
    </div>

</body>

</html>