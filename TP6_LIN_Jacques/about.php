<?php
require_once 'db.php';

// récupération de tous les contenus liés à la page 'about'
$stmt = $pdo->prepare("SELECT section, titre, contenu_texte FROM pages_content WHERE page_nom = 'about'");
$stmt->execute();
$lignes = $stmt->fetchAll();

// restructuration en tableau associatif pour faciliter l'affichage
$content = [];
foreach ($lignes as $ligne) {
  $content[$ligne['section']] = [
    'titre' => $ligne['titre'],
    'texte' => $ligne['contenu_texte']
  ];
}

// fonction utilitaire pour éviter une erreur PHP si un texte est supprimé en base
function getTextContent($section, $type, $contentArray)
{
  return $contentArray[$section][$type] ?? '<em>Contenu non défini</em>';
}
?>
<!doctype html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>L'Esprit - L'atelier de café</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="index.css" />
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
      <a class="navbar-brand" href="index.php">L'Atelier</a>
      <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Accueil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="service.php">Nos services</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="about.php">L'Esprit de l'Atelier</a>
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
      <h1 class="display-4"><?= getTextContent('hero', 'titre', $content) ?></h1>
      <p class="lead mt-3">
        <!-- on n'utilise pas htmlspecialchars ici pour conserver les balises <em> ou <strong> -->
        <?= getTextContent('hero', 'texte', $content) ?>
      </p>
    </div>
  </header>

  <main class="container py-5">
    <div class="row mb-5">
      <div class="col-lg-8 mx-auto text-center">
        <h2 class="mb-4"><?= getTextContent('passion', 'titre', $content) ?></h2>
        <p class="lead">
          <?= getTextContent('passion', 'texte', $content) ?>
        </p>
      </div>
    </div>

    <h2 class="text-center mb-5 border-bottom border-dark pb-3">Nos 3 piliers</h2>
    <div class="row text-center g-4 mb-5">
      <div class="col-md-4">
        <div class="p-4 border border-dark h-100">
          <h3 class="h5"><?= getTextContent('pilier1', 'titre', $content) ?></h3>
          <p class="mb-0 mt-3"><?= getTextContent('pilier1', 'texte', $content) ?></p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-4 border border-dark h-100 bg-dark text-white">
          <h3 class="h5 text-white"><?= getTextContent('pilier2', 'titre', $content) ?></h3>
          <p class="mb-0 mt-3"><?= getTextContent('pilier2', 'texte', $content) ?></p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-4 border border-dark h-100">
          <h3 class="h5"><?= getTextContent('pilier3', 'titre', $content) ?></h3>
          <p class="mb-0 mt-3"><?= getTextContent('pilier3', 'texte', $content) ?></p>
        </div>
      </div>
    </div>

    <section class="mt-5 pt-4">
      <div class="row align-items-center mb-5 pb-3 border-bottom border-light">
        <div class="col-md-4 mb-4 mb-md-0">
          <img src="images/agriculture.webp" alt="Le voyage du grain" class="img-fluid rounded shadow-sm w-100 object-fit-cover" style="max-height: 300px" />
        </div>
        <div class="col-md-8 border-start border-dark ps-md-4">
          <h3 class="h2 fw-bold mb-3"><?= getTextContent('voyage', 'titre', $content) ?></h3>
          <p class="text-muted">
            <?= getTextContent('voyage', 'texte', $content) ?>
          </p>
        </div>
      </div>

      <div class="row align-items-center mb-5 pb-3 border-bottom border-light flex-column-reverse flex-md-row">
        <div class="col-md-8 border-end border-dark pe-md-4 text-md-end">
          <h3 class="h2 fw-bold mb-3"><?= getTextContent('precision', 'titre', $content) ?></h3>
          <p class="text-muted">
            <?= getTextContent('precision', 'texte', $content) ?>
          </p>
        </div>
        <div class="col-md-4 mb-4 mb-md-0">
          <img src="images/barista.jpg" alt="La précision à l'état pur" class="img-fluid rounded shadow-sm w-100 object-fit-cover" style="max-height: 300px" />
        </div>
      </div>

      <div class="row align-items-center mb-5 pb-3">
        <div class="col-md-4 mb-4 mb-md-0">
          <img src="images/salon.jpg" alt="Ambiance chaleureuse" class="img-fluid rounded shadow w-100 object-fit-cover" style="max-height: 300px" />
        </div>
        <div class="col-md-8 border-start border-dark ps-md-4">
          <h3 class="h2 fw-bold mb-3"><?= getTextContent('refuge', 'titre', $content) ?></h3>
          <p class="text-muted">
            <?= getTextContent('refuge', 'texte', $content) ?>
          </p>
        </div>
      </div>
    </section>

    <div class="text-center mt-5">
      <a href="service.php" class="btn btn-custom px-4 py-3 me-sm-3 mb-3 mb-sm-0">Découvrir la carte</a>
      <a href="index.php#horaires" class="btn btn-outline-dark px-4 py-3 rounded-0 fw-bold text-uppercase border-2 mb-3 mb-sm-0">Venir nous voir</a>
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
        Email : <a href="mailto:atelierducafé@gmail.com">atelierducafé@gmail.com</a>
      </address>
      <p class="mb-0 small">© 2026 L'Atelier du Café - Tous droits réservés.</p>
    </div>
  </footer>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>