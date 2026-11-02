<?php
require_once 'config.php';

// traitement du formulaire de réservation
$message_reservation = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_reservation'])) {
  $nom = trim($_POST['nom'] ?? '');
  $personnes = (int)($_POST['personnes'] ?? 0);
  $date_heure = trim($_POST['date_heure'] ?? '');

  if (!empty($nom) && $personnes > 0 && !empty($date_heure)) {
    try {
      $sql = "INSERT INTO reservations (nom, nb_personnes, date_heure) VALUES (:nom, :nb_personnes, :date_heure)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ':nom' => $nom,
        ':nb_personnes' => $personnes,
        ':date_heure' => $date_heure
      ]);

      $message_reservation = "<div class='alert alert-success mt-3'>Merci " . htmlspecialchars($nom) . ". Votre réservation pour $personnes personne(s) a bien été confirmée. L'équipe de L'Atelier vous attend.</div>";
    } catch (PDOException $e) {
      $message_reservation = "<div class='alert alert-danger mt-3'>Erreur lors de la réservation : veuillez réessayer plus tard.</div>";
    }
  } else {
    $message_reservation = "<div class='alert alert-warning mt-3'>Veuillez remplir tous les champs correctement.</div>";
  }
}

// récupération des boisson pour la carte
$stmtClassiques = $pdo->query("SELECT * FROM services WHERE category_id = 1");
$classiques = $stmtClassiques->fetchAll();

$stmtFraiches = $pdo->query("SELECT * FROM services WHERE category_id = 2");
$fraiches = $stmtFraiches->fetchAll();
?>
<!doctype html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>L'Atelier du café</title>
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
            <a class="nav-link" href="about.php">L'Esprit de L'Atelier</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contact.php">Contact</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <header class="hero-section">
    <div class="container">
      <h1 class="display-3">L'Atelier du Café</h1>
      <p class="lead mt-3">
        <em>Votre pause café artisanale, torréfiée avec passion.</em>
      </p>
    </div>
  </header>

  <main class="container">
    <section id="bienvenue" class="text-center">
      <h2 class="mb-4">Bienvenue à L'Atelier</h2>
      <p class="lead w-75 mx-auto">
        Installez-vous confortablement et laissez-vous guider par l'arôme de
        nos grains fraîchement moulus. Que vous veniez pour travailler,
        discuter entre amis ou simplement savourer un moment de calme, nos
        baristas préparent chaque tasse avec soin.
      </p>
      <a href="#carte" class="btn btn-custom mt-4 px-4 py-2">Découvrir la carte</a>
    </section>

    <section id="carte">
      <h2 class="text-center mb-5">À déguster sur place ou à emporter</h2>

      <div class="row">
        <div class="col-lg-8 mx-auto">
          <h3 class="mb-3 border-bottom border-dark pb-2">
            Les Classiques du Barista
          </h3>
          <div class="table-responsive">
            <table class="table table-bw text-center">
              <thead>
                <tr>
                  <th>Boisson</th>
                  <th>Description</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($classiques as $boisson): ?>
                  <tr>
                    <td>
                      <strong><?= htmlspecialchars($boisson['nom']) ?></strong><br />
                      <?php if (!empty($boisson['image_url'])): ?>
                        <img
                          src="<?= htmlspecialchars($boisson['image_url']) ?>"
                          alt="<?= htmlspecialchars($boisson['nom']) ?>"
                          width="80"
                          height="60"
                          class="img-coffee mt-2"
                          loading="lazy" />
                      <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($boisson['description']) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>

          <h3 class="mt-5 mb-3 border-bottom border-dark pb-2">
            Boissons Fraîches & Plaisirs
          </h3>
          <ul class="list-group list-group-flush border border-dark">
            <?php foreach ($fraiches as $boisson): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <strong>
                  <?= htmlspecialchars($boisson['nom']) ?>
                  <?php if (!empty($boisson['description'])): ?>
                    (<?= htmlspecialchars($boisson['description']) ?>)
                  <?php endif; ?>
                </strong>
                <span><?= number_format($boisson['prix'], 2, ',', ' ') ?> €</span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </section>

    <section id="esprit">
      <h2 class="text-center mb-5">Pourquoi venir chez nous ?</h2>
      <div class="row text-center g-4">
        <div class="col-md-4">
          <div class="p-4 border border-dark h-100">
            <h3 class="h5">1. Grains d'exception</h3>
            <p class="mb-0 mt-3">
              Cafés 100 % Arabica issus de producteurs engagés dans une
              agriculture durable.
            </p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-4 border border-dark h-100 bg-dark text-white">
            <h3 class="h5 text-white">2. Savoir-faire</h3>
            <p class="mb-0 mt-3">
              Extraction maîtrisée (V60, Chemex, Expresso) pour révéler toutes
              les notes aromatiques.
            </p>
          </div>
        </div>
        <div class="col-md-4">
          <div class="p-4 border border-dark h-100">
            <h3 class="h5">3. Ambiance</h3>
            <p class="mb-0 mt-3">
              Fauteuils confortables, musique douce et Wi-Fi gratuit :
              l'endroit idéal pour déconnecter.
            </p>
          </div>
        </div>
      </div>
    </section>

    <section id="horaires">
      <div class="row align-items-center">
        <div class="col-md-5 mb-4 mb-md-0">
          <h2>Horaires</h2>
          <ul class="list-unstyled mt-4 fs-5">
            <li class="mb-2">
              <strong>Lundi - Vendredi :</strong> 07h30 - 19h00
            </li>
            <li class="mb-2"><strong>Samedi :</strong> 08h30 - 20h00</li>
            <li class="mb-2"><strong>Dimanche :</strong> 09h00 - 18h00</li>
          </ul>
        </div>
        <div class="col-md-7">
          <div class="p-4 border border-dark bg-light">
            <h3 class="mb-4">Réserver une table</h3>

            <?= $message_reservation ?>

            <form id="reservationForm" method="POST" action="index.php#horaires">
              <div class="mb-3">
                <label for="nom" class="form-label fw-bold">Votre nom</label>
                <input
                  type="text"
                  class="form-control"
                  id="nom"
                  name="nom"
                  placeholder="Ex : Jacques LIN"
                  required />
              </div>
              <div class="mb-3">
                <label for="personnes" class="form-label fw-bold">Nombre de personnes</label>
                <input
                  type="number"
                  class="form-control"
                  id="personnes"
                  name="personnes"
                  min="1"
                  max="10"
                  value="2"
                  required />
              </div>
              <div class="mb-4">
                <label for="date" class="form-label fw-bold">Date et heure</label>
                <input
                  type="datetime-local"
                  class="form-control"
                  id="date"
                  name="date_heure"
                  required />
              </div>
              <button type="submit" name="submit_reservation" class="btn btn-custom w-100 py-2">
                Confirmer la réservation
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer id="contact">
    <div class="container">
      <h2 class="h4 mb-4">Nous trouver</h2>
      <address class="mb-4 fst-normal">
        <strong>L'Atelier du Café</strong><br />
        Rue de l'imagination<br />
        75000 Paris<br /><br />
        Tél : <a href="tel:+33100000000">01 00 00 00 00</a><br />
        Email :
        <a href="mailto:atelierducafé@gmail.com">atelierducafé@gmail.com</a>
      </address>
      <p class="mb-0 small">
        © 2026 L'Atelier de Café - Tous droits réservés.
      </p>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>