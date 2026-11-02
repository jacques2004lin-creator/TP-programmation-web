-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : lun. 02 nov. 2026 à 17:12
-- Version du serveur : 10.11.15-MariaDB-ubu2204
-- Version de PHP : 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `atelier_cafe`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password_hash`, `created_at`) VALUES
(1, 'admin@atelierdecafe.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '2026-10-30 14:32:46');

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `nom`) VALUES
(1, 'Les Classiques du Barista'),
(2, 'Boissons Fraîches & Plaisirs');

-- --------------------------------------------------------

--
-- Structure de la table `messages_contact`
--

CREATE TABLE `messages_contact` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `sujet` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `date_envoi` timestamp NULL DEFAULT current_timestamp(),
  `statut` enum('non lu','lu') DEFAULT 'non lu'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `messages_contact`
--

INSERT INTO `messages_contact` (`id`, `nom`, `email`, `sujet`, `message`, `date_envoi`, `statut`) VALUES
(1, 'lin', 'jacques2004lin@gmail.com', 'test', 'lol', '2026-10-30 18:26:38', 'lu');

-- --------------------------------------------------------

--
-- Structure de la table `pages_content`
--

CREATE TABLE `pages_content` (
  `id` int(11) NOT NULL,
  `page_nom` varchar(50) NOT NULL,
  `section` varchar(50) NOT NULL,
  `titre` varchar(255) DEFAULT NULL,
  `contenu_texte` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `pages_content`
--

INSERT INTO `pages_content` (`id`, `page_nom`, `section`, `titre`, `contenu_texte`) VALUES
(1, 'about', 'hero', 'L\'Esprit de l\'Atelier', '<em>Notre histoire, nos valeurs et notre passion du café.</em>'),
(2, 'about', 'passion', 'Une passion partagée', 'L\'Atelier de Café est né d\'une idée simple : redonner au café ses lettres de noblesse dans un cadre épuré et minimaliste.<br><br>Nous croyons qu\'une tasse de café n\'est pas juste une boisson rapide, mais une véritable expérience qui rassemble. C\'est pourquoi nous mettons toute notre exigence dans la sélection de nos grains, la torréfaction, et la préparation minutieuse de chaque commande.'),
(3, 'about', 'pilier1', '1. Grains d\'exception', 'Nous sélectionnons des cafés 100 % Arabica issus de petits producteurs engagés dans une agriculture durable. Chaque grain est choisi pour exprimer son terroir unique.'),
(4, 'about', 'pilier2', '2. Savoir-faire artisanal', 'Nos baristas maîtrisent l\'art complexe de l\'extraction. Que ce soit en méthode douce (V60, Chemex) ou en Expresso, nous révélons toutes les notes aromatiques avec précision.'),
(5, 'about', 'pilier3', '3. Ambiance chaleureuse', 'Fauteuils confortables, musique douce, décoration brute et Wi-Fi gratuit : L\'Atelier est l\'endroit idéal pour déconnecter ou pour travailler sereinement.'),
(6, 'about', 'voyage', 'Le voyage du grain', 'Nous privilégions le <strong>\"Direct Trade\"</strong> (commerce direct) afin de garantir une rémunération juste aux cultivateurs situés en Éthiopie, en Colombie ou au Costa Rica. Nous choisissons des grains cultivés en haute altitude, cueillis à la main à parfaite maturité. La torréfaction est ensuite réalisée de manière artisanale : une torréfaction légère à moyenne qui respecte le grain, ne le brûle jamais, et permet d\'exprimer des notes florales, fruitées ou chocolatées uniques à chaque terroir.'),
(7, 'about', 'precision', 'La précision à l\'état pur', 'Derrière le comptoir, rien n\'est laissé au hasard. Nos baristas voient le café comme une véritable science. La <strong class=\"text-dark\">température de l\'eau, le grammage exact, la taille de la mouture ajustée au micromètre près</strong>, et le temps d\'extraction sont contrôlés avec une rigueur absolue. Ils s\'entraînent quotidiennement pour sublimer chaque recette et vous offrir des tasses parfaitement équilibrées, couronnées par des techniques de <em>Latte Art</em> irréprochables.'),
(8, 'about', 'refuge', 'Votre refuge urbain', 'Nous avons conçu L\'Atelier comme une extension de votre propre salon. L\'architecture d\'intérieur mêle des matériaux bruts et nobles avec des lumières chaudes. L\'acoustique a été pensée pour préserver le calme : une playlist lofi douce, le murmure des conversations et le son réconfortant de la machine à expresso. Que vous soyez plongé dans un livre, concentré sur votre écran ou en pleine discussion refaisant le monde, le parfum du café fraîchement moulu vous accompagne pour vous offrir une véritable parenthèse.');

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `nb_personnes` int(11) NOT NULL,
  `date_heure` datetime NOT NULL,
  `statut` enum('en attente','confirmée','annulée') DEFAULT 'en attente',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id`, `nom`, `nb_personnes`, `date_heure`, `statut`, `created_at`) VALUES
(1, 'Jacques LIN', 2, '2026-10-30 16:27:00', 'en attente', '2026-10-30 15:27:58'),
(2, 'Alice', 2, '2026-10-30 16:36:00', 'annulée', '2026-10-30 15:36:26'),
(3, 'Bob', 2, '2026-10-30 16:41:00', 'confirmée', '2026-10-30 15:42:26');

-- --------------------------------------------------------

--
-- Structure de la table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(5,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `services`
--

INSERT INTO `services` (`id`, `nom`, `description`, `prix`, `image_url`, `category_id`) VALUES
(1, 'Expresso', 'Court, intense et riche en arômes.', 2.50, 'images/expresso.webp', 1),
(2, 'Double Expresso', 'Deux doses d\'expresso pour un réveil garanti.', 3.80, 'images/double_expresso.webp', 1),
(3, 'Americano', 'Un expresso allongé à l\'eau chaude, doux et équilibré.', 3.80, 'images/americano.jpg', 1),
(4, 'Cappuccino', 'Expresso, lait chaud et une mousse de lait onctueuse.', 4.20, 'images/cappuccino.webp', 1),
(5, 'Flat White', 'Double shot d\'expresso recouvert d\'une fine couche de lait micro-moussé.', 4.50, 'images/flat_white.png', 1),
(6, 'Latte Macchiato', 'Grand verre de lait chaud taché d\'un expresso fondant.', 4.50, 'images/latte_macchiato.webp', 1),
(7, 'Iced Latte', 'Café glacé au lait', 4.80, NULL, 2),
(8, 'Cold Brew', 'Café infusé à froid pendant 16h', 4.50, NULL, 2),
(9, 'Chocolat Chaud Maison', NULL, 4.20, NULL, 2),
(10, 'Chaï Latte Épicé', NULL, 4.50, NULL, 2),
(11, 'Thé Vert ou Noir Bio', NULL, 3.50, NULL, 2);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `messages_contact`
--
ALTER TABLE `messages_contact`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pages_content`
--
ALTER TABLE `pages_content`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_page_section` (`page_nom`,`section`);

--
-- Index pour la table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `messages_contact`
--
ALTER TABLE `messages_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `pages_content`
--
ALTER TABLE `pages_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
