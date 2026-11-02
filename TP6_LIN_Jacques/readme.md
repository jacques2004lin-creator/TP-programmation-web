# L'Atelier du Café - Projet Web Dynamique (TP6)

# Présentation du projet

Ce projet est l'aboutissement du TP6. Il s'agit de la transformation du site vitrine statique "L'Atelier du Café" en une véritable application web dynamique (Back-end) propulsée par PHP et MySQL. Le projet intègre un espace d'administration sécurisé permettant de gérer la carte des boissons, de traiter les réservations, de lire les messages de contact et d'éditer le contenu textuel du site.

# Prérequis

Pour faire fonctionner ce projet en local, vous devez disposer d'un environnement serveur Docker comprenant :
Serveur Web : Apache (ou Nginx)
Langage : PHP
Base de données : MariaDB

# Instructions d'installation

Copie des fichiers :
Décompressez l'archive du projet et placez le dossier racine (ex: USLA57_TP_LIN_Jacques) dans le répertoire public de votre serveur local.

Création de la base de données :
Ouvrez votre interface de gestion de base de données (ex: phpMyAdmin via http://localhost/phpmyadmin).
Créez une nouvelle base de données nommée atelier_cafe (encodage recommandé : utf8mb4_unicode_ci).
Importez la structure des tables en exécutant le fichier sql/schema.sql.
Importez les données fictives de démonstration en exécutant le fichier.

Configuration de la connexion PDO :
Le fichier `config.php`, exclu du dépôt public car il contient les identifiants locaux, doit être créé localement avec les paramètres de votre base de données (hôte `db`, base `atelier_cafe`, utilisateur et mot de passe MySQL).

Lancement de l'application :
Ouvrez votre navigateur et accédez à l'URL locale du projet :
http://localhost:8080/USLA57_TP_LIN_Jacques/TP6_LIN_Jacques/...

# Identifiants de démonstration (Back-office)

Un compte administrateur fictif a été généré lors de l'importation de la base de données. Vous pouvez l'utiliser pour accéder à l'espace de gestion CRUD.
URL de connexion : http://localhost:8080/USLA57_TP_LIN_Jacques/TP6_LIN_Jacques/login.php
Adresse e-mail : admin@atelierdecafe.fr
Mot de passe : password

(Note : Le mot de passe est sécurisé en base de données à l'aide de la fonction native PHP password_hash() avec l'algorithme BCRYPT).

# Structure principale du projet

/index.php: Page d'accueil dynamique (vitrine et formulaire de réservation).
/service.php : Carte des boissons générée depuis la BDD.
/contact.php : Formulaire de contact fonctionnel avec insertion BDD.
/about.php : Page "L'Esprit" dont les textes sont modifiables en base.
/login.php et /logout.php : Gestion sécurisée de l'authentification par sessions.
/dashboard.php : Tableau de bord administrateur (Lecture des messages, validation des réservations).
/admin_services.php et /edit_service.php : Interfaces CRUD pour la gestion de la carte.
/admin_pages.php : Interface CRUD pour l'édition des textes du site.
/db.php: Script de connexion à la base de données (PDO).
/sql/ : Dossier contenant les scripts de création et d'initialisation de la BDD.
