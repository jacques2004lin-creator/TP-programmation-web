<?php
require_once 'db.php';

// récupération des boissons de la catégorie 1 (Les Classiques)
$stmtClassiques = $pdo->query("SELECT * FROM services WHERE category_id = 1");
$classiques = $stmtClassiques->fetchAll();

// récupération des boissons de la catégorie 2 (Fraîches & Plaisirs)
$stmtFraiches = $pdo->query("SELECT * FROM services WHERE category_id = 2");
$fraiches = $stmtFraiches->fetchAll();
?>
<!doctype html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Nos boissons L'atelier du café</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet" />
  <link rel="stylesheet" href="index.css" />
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
      <a class="navbar-brand" href="index.html">L'Atelier</a>
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
            <a class="nav-link active" href="#service">Nos services</a>
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

  <header class="hero-section" style="padding: 60px 0">
    <div class="container">
      <h1 class="display-4">Notre Carte</h1>
      <p class="lead mt-3"><em>À déguster sur place ou à emporter.</em></p>
    </div>
  </header>

  <main class="container py-5">
    <div class="row">
      <div class="col-lg-10 mx-auto">
        <p class="text-center lead mb-5">
          Toutes nos boissons sont préparées à la commande avec du café de
          spécialité et du lait bio (options végétales disponibles : avoine,
          amande, soja).
        </p>

        <h2 class="mb-4 border-bottom border-dark pb-2">
          Les Classiques du Barista
        </h2>
        <div class="table-responsive mb-5">
          <table class="table table-bw text-center align-middle">
            <thead>
              <tr>
                <th>Boisson</th>
                <th>Aperçu</th>
                <th>Description</th>
                <th>Prix</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($classiques as $boisson): ?>
                <tr>
                  <td class="fw-bold"><?= htmlspecialchars($boisson['nom']) ?></td>
                  <td>
                    <?php if (!empty($boisson['image_url'])): ?>
                      <img
                        src="<?= htmlspecialchars($boisson['image_url']) ?>"
                        alt="<?= htmlspecialchars($boisson['nom']) ?>"
                        width="80"
                        height="60"
                        class="img-coffee"
                        loading="lazy" />
                    <?php endif; ?>
                  </td>
                  <td><?= htmlspecialchars($boisson['description']) ?></td>
                  <td><?= number_format($boisson['prix'], 2, ',', ' ') ?> €</td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <h2 class="mb-4 border-bottom border-dark pb-2">
          Boissons Fraîches & Plaisirs
        </h2>
        <ul class="list-group list-group-flush border border-dark mb-5">
          <?php foreach ($fraiches as $boisson): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center py-3">
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

        <div class="text-center mt-4">
          <a href="index.php#horaires" class="btn btn-custom px-5 py-3">Réserver une table</a>
        </div>
      </div>
    </div>
  </main>

  <footer id="contact">
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