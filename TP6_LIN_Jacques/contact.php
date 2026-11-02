<?php
require_once 'config.php';

// initialisation de la variable de message
$message_contact = '';

// vérification si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
  // récupération et nettoyage des données
  $nom = trim($_POST['nom'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $sujet = trim($_POST['sujet'] ?? '');
  $message = trim($_POST['message'] ?? '');

  // validation basique (vérification que les champs ne sont pas vides et que l'email est valide)
  if (!empty($nom) && filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($sujet) && !empty($message)) {
    try {
      // requête préparée pour l'insertion
      $sql = "INSERT INTO messages_contact (nom, email, sujet, message) VALUES (:nom, :email, :sujet, :message)";
      $stmt = $pdo->prepare($sql);

      // exécution de la requête
      $stmt->execute([
        ':nom' => $nom,
        ':email' => $email,
        ':sujet' => $sujet,
        ':message' => $message
      ]);

      $message_contact = "<div class='alert alert-success mb-4'>Nous avons bien reçu votre demande, " . htmlspecialchars($nom) . ", et nous vous répondrons très vite.</div>";
    } catch (PDOException $e) {
      $message_contact = "<div class='alert alert-danger mb-4'>Une erreur est survenue lors de l'envoi du message. Veuillez réessayer plus tard.</div>";
    }
  } else {
    $message_contact = "<div class='alert alert-warning mb-4'>Veuillez remplir tous les champs correctement (avec une adresse e-mail valide).</div>";
  }
}
?>
<!doctype html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>L'atelier du café - Contact</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <link rel="stylesheet" href="index.css" />
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
      <a class="navbar-brand" href="index.php">L'Atelier</a>
      <button
        class="navbar-toggler bg-light"
        type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div
        class="collapse navbar-collapse justify-content-end"
        id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Accueil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="service.php">Nos services</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="about.php">L'Esprit de l'Atelier</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="contact.php">Contact</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <header class="hero-section" style="padding: 60px 0">
    <div class="container">
      <h1 class="display-4">Contactez-nous</h1>
      <p class="lead mt-3">
        <em>Une question, une commande spéciale ou envie de collaborer avec
          nous ?</em>
      </p>
    </div>
  </header>

  <main class="container py-5">
    <div class="row g-5">
      <div class="col-lg-5">
        <h2 class="h3 fw-bold mb-4">Passez nous voir</h2>
        <p class="text-muted mb-4">
          Notre équipe est à votre disposition pour toute demande. Remplissez
          le formulaire et nous vous répondrons dans les plus brefs délais,
          généralement sous 24 heures.
        </p>

        <div class="mb-4">
          <h3 class="h5 fw-bold">Notre adresse</h3>
          <p class="text-muted mb-0">
            L'Atelier de Café<br />
            Rue de l'imagination<br />
            75000 Paris
          </p>
        </div>

        <div class="mb-4">
          <h3 class="h5 fw-bold">Heures d'ouverture</h3>
          <p class="text-muted mb-0">
            Lundi - Vendredi : 08h00 - 18h00<br />
            Samedi : 09h00 - 19h00<br />
            Dimanche : Fermé
          </p>
        </div>

        <div>
          <h3 class="h5 fw-bold">Contact direct</h3>
          <p class="text-muted mb-0">
            Tél :
            <a href="tel:+33100000000" class="text-decoration-none text-dark">01 00 00 00 00</a><br />
            Email :
            <a
              href="mailto:bonjour@atelierdecafe.fr"
              class="text-decoration-none text-dark">bonjour@atelierdecafe.fr</a>
          </p>
        </div>
      </div>

      <div class="col-lg-7">
        <div class="p-4 border border-dark shadow-sm bg-white">
          <h2 class="h4 fw-bold mb-4">Envoyez-nous un message</h2>

          <!-- affichage du message PHP (succès ou erreur) -->
          <?= $message_contact ?>

          <!-- le formulaire pointe maintenant vers contact.php en POST -->
          <form id="contactForm" method="POST" action="contact.php">
            <div class="mb-3">
              <label for="nom" class="form-label fw-semibold">Nom complet <span class="text-danger">*</span></label>
              <input
                type="text"
                class="form-control rounded-0"
                id="nom"
                name="nom"
                placeholder="Jacques LIN"
                required />
            </div>

            <div class="mb-3">
              <label for="email" class="form-label fw-semibold">Adresse e-mail <span class="text-danger">*</span></label>
              <input
                type="email"
                class="form-control rounded-0"
                id="email"
                name="email"
                placeholder="jacques2004lin@gmail.com"
                required />
            </div>

            <div class="mb-3">
              <label for="sujet" class="form-label fw-semibold">Sujet <span class="text-danger">*</span></label>
              <input
                type="text"
                class="form-control rounded-0"
                id="sujet"
                name="sujet"
                placeholder="De quoi s'agit-il ?"
                required />
            </div>

            <div class="mb-4">
              <label for="message" class="form-label fw-semibold">Votre message <span class="text-danger">*</span></label>
              <textarea
                class="form-control rounded-0"
                id="message"
                name="message"
                rows="5"
                placeholder="Comment pouvons-nous vous aider ?"
                required></textarea>
            </div>

            <button
              type="submit"
              name="submit_contact"
              class="btn btn-dark w-100 py-3 rounded-0 text-uppercase fw-bold">
              envoyer le message
            </button>
          </form>
        </div>
      </div>
    </div>
  </main>

  <footer id="contact-footer">
    <div class="container">
      <h2 class="h4 mb-4">Nous trouver</h2>
      <address class="mb-4 fst-normal">
        <strong>L'Atelier de Café</strong><br />
        Rue de l'imagination<br />
        75000 Paris<br /><br />
        Tél : <a href="tel:+33100000000">01 00 00 00 00</a><br />
        Email :
        <a href="mailto:atelierducafé@gmail.com">atelierducafé@gmail.com</a>
      </address>
      <p class="mb-0 small">
        © 2026 L'Atelier du Café - Tous droits réservés.
      </p>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>