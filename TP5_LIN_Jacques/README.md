# Fonctionnalités principales

Gestion des utilisateurs :Inscription avec hachage du mot de passe, connexion avec régénération de session, déconnexion.
Sécurité et droits d'accès : Restriction des fonctionnalités d'écriture (create_post.php) aux utilisateurs authentifiés.
Gestion du contenu : Publication d'articles associés à un utilisateur et affichage ordonné par date.
Protection de l'application :
Protection contre les injections SQL via les requêtes préparées PDO ($pdo->prepare()).
Protection contre les failles XSS à l'affichage avec htmlspecialchars().
Protection contre la fixation de session (session_regenerate_id(true)).

# Arborescence du Projet

tp_php_mysql/
config.php # Connexion PDO à la base de données
index.php # Script de vérification de l'environnement PHP
list_posts.php # Page d'accueil / Liste des articles publiés
create_post.php # Formulaire de création d'article (protégé par session)
store_post.php # Traitement et enregistrement d'un nouvel article
login.php # Formulaire de connexion
login_handler.php # Traitement de l'authentification
register.php # Formulaire et traitement de l'inscription
logout.php # Déconnexion et destruction de session
sql/
schema.sql # Script de création des tables (users, posts)
seed.sql # Données de démonstration fictives
README.md # Documentation du projet

# Prérequis et Installation

# Prérequis

Serveur Web (Apache / Nginx) avec PHP
Extension PHP PDO MySQL activée
Base de données MariaDB

# Initialisation de la Base de Données

Créez une base de données MySQL nommée tp_php_mysql.
Exécutez le script d'initialisation du schéma :
bash
mysql -u root -p tp_php_mysql < sql/schema.sql

Exécutez le script de données de démonstration :
bash
mysql -u root -p tp_php_mysql < sql/seed.sql

# Configuration de la connexion

Le fichier `config.php`, exclu du dépôt public car il contient les identifiants locaux, doit être créé localement avec les paramètres de votre base de données (hôte `db`, base `tp_php_mysql`, utilisateur et mot de passe MySQL).

---

# Identifiants de Démonstration (Fictifs)

Des comptes utilisateurs fictifs pré-configurés sont disponibles dans le jeu de données (sql/seed.sql) :

Utilisateur Mot de passe Rôle Description
| Maryam password123 Compte auteur 1
| Jemmy password123Compte auteur 2

Sécurité : Les mots de passe stockés en base sont hachés avec l'algorithme BCRYPT. Aucun secret réel n'est utilisé dans l'application.

# Shortlist des Tests Réalisés

Test d'inscription register.php :
Contrôle de la longueur minimale du nom d'utilisateur (>= 3 caractères) et du mot de passe (>= 6 caractères).
Vérification du rejet des doublons sur le champ username unique.

Test d'authentification login_handler.php :
Rejet des identifiants incorrects avec message d'erreur générique.
Validation de la connexion réussie et contrôle de la régénération d'identifiant de session (session_regenerate_id).

Test des contrôles d'accès (create_post.php) :
Tentative d'accès sans session active : vérification de la redirection immédiate vers login.php.

Test d'injection SQL & XSS (store_post.php, list_posts.php) :
Soumission de caractères normaux et spéciaux dans le titre/corps pour vérifier la bonne exécution des requêtes préparées PDO.
Injection de balises HTML/Script (ex: <script>alert(1)</script>) pour confirmer l'échappement à l'affichage via la fonction e().

# Utilisation de l'IA

L'intelligence artificielle a été utilisée comme support d'aprentissage pour :
Éclaircir le cheminement d'une requête HTTP entre le navigateur, PHP et la base de données.
Valider la méthodologie de sécurisation des sessions et la gestion des messages Flash.
Vérifier la structure des requêtes préparées avec PDO.
