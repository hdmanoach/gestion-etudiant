-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mar. 04 nov. 2025 à 16:03
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `ecole`
--

-- --------------------------------------------------------

--
-- Structure de la table `annee`
--

CREATE TABLE `annee` (
  `id` int(11) NOT NULL,
  `libelle` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `annee`
--

INSERT INTO `annee` (`id`, `libelle`) VALUES
(5, '2020-2021'),
(6, '2021-2022'),
(3, '2022-2023'),
(4, '2023-2024'),
(1, '2024-2025'),
(2, '2025-2026');

-- --------------------------------------------------------

--
-- Structure de la table `classe`
--

CREATE TABLE `classe` (
  `id` int(11) NOT NULL,
  `libelle` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `classe`
--

INSERT INTO `classe` (`id`, `libelle`) VALUES
(1, 'Licence 1 Gestion'),
(4, 'Licence 1 Informatique'),
(2, 'Licence 2 Gestion'),
(5, 'Licence 2 Informmatique'),
(3, 'Licence 3 Gestion'),
(6, 'Licence 3 Informmatique'),
(9, 'Master 1 Gestion'),
(7, 'Master 1 Informmatique'),
(10, 'Master 2 Gestion'),
(8, 'Master 2 Informmatique');

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE `etudiant` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenoms` varchar(150) NOT NULL,
  `sexe` enum('M','F') NOT NULL,
  `date_naissance` date NOT NULL,
  `lieu_naissance` varchar(150) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `adresse` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etudiant`
--

INSERT INTO `etudiant` (`id`, `nom`, `prenoms`, `sexe`, `date_naissance`, `lieu_naissance`, `telephone`, `adresse`, `photo`, `created_by`, `created_at`) VALUES
(1, 'DODO', 'Manoach', 'M', '2004-08-08', 'Porto-Novo', '53302489', 'Calavie', 'uploads/photo_69063a603dd6b6.55997535.jpeg', 1, '2025-11-01 16:50:40'),
(2, 'Mandela', 'Nelson', 'M', '2025-11-19', 'Afrique du Sud', '232323', 'Man', 'uploads/photo_6906424f6f98e3.10978935.jpeg', 2, '2025-11-01 17:24:31'),
(3, 'Barack', 'Obama', 'M', '2025-11-06', 'Massachusetts, USA', '232323232', 'USA', 'uploads/photo_6906f311521f16.64463752.jpeg', 2, '2025-11-01 21:57:29'),
(4, 'Biden', 'Joe', 'M', '1942-11-20', 'Scranton, USA', '0600000001', 'White House, Washington', 'uploads/photo_69084e45f15ff1.33858656.jpeg', 2, '2025-11-03 06:27:33'),
(5, 'Macron', 'Emmanuel', 'M', '1977-12-21', 'Amiens, France', '0600000002', 'Palais de l\'Élysée, Paris', 'uploads/photo_69084f0680cf73.03260509.jpeg', 2, '2025-11-03 06:27:33'),
(6, 'Poutine', 'Vladimir', 'M', '1952-10-07', 'Leningrad, Russie', '0600000003', 'Moscou, Russie', 'uploads/photo_69084e855a5e52.85450325.jpeg', 2, '2025-11-03 06:27:33'),
(7, 'Xi', 'Jinping', 'M', '1953-06-15', 'Pékin, Chine', '0600000004', 'Beijing, Chine', NULL, NULL, '2025-11-03 06:27:33'),
(8, 'Ramaphosa', 'Cyril', 'M', '1952-11-17', 'Johannesburg, Afrique du Sud', '0600000005', 'Pretoria, Afrique du Sud', NULL, NULL, '2025-11-03 06:27:33'),
(9, 'López Obrador', 'Andrés Manuel', 'M', '1953-11-13', 'Tabasco, Mexique', '0600000006', 'Mexico, Mexique', 'uploads/photo_690861fbc367d4.45602105.webp', 2, '2025-11-03 06:27:33'),
(10, 'Yoon', 'Suk-yeol', 'M', '1960-12-18', 'Seoul, Corée du Sud', '0600000007', 'Seoul, Corée du Sud', 'uploads/photo_69084ee020d866.30334623.jpeg', 2, '2025-11-03 06:27:33'),
(11, 'Erdoğan', 'Recep Tayyip', 'M', '1954-02-26', 'Istanbul, Turquie', '0600000008', 'Ankara, Turquie', NULL, NULL, '2025-11-03 06:27:33'),
(12, 'Petro', 'Gustavo', 'M', '1960-04-19', 'Ciénaga de Oro, Colombie', '0600000009', 'Bogotá, Colombie', NULL, NULL, '2025-11-03 06:27:33'),
(13, 'Tshisekedi', 'Félix', 'M', '1963-06-13', 'Kinshasa, RDC', '0600000010', 'Kinshasa, RDC', NULL, NULL, '2025-11-03 06:27:33');

-- --------------------------------------------------------

--
-- Structure de la table `inscription`
--

CREATE TABLE `inscription` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `classe_id` int(11) NOT NULL,
  `annee_id` int(11) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `inscription`
--

INSERT INTO `inscription` (`id`, `etudiant_id`, `classe_id`, `annee_id`, `created_by`, `created_at`) VALUES
(1, 3, 1, 1, NULL, '2025-11-03 08:05:03'),
(2, 4, 2, 1, NULL, '2025-11-03 08:05:32'),
(3, 1, 6, 2, NULL, '2025-11-03 08:56:24'),
(4, 11, 1, 1, NULL, '2025-11-03 10:20:10'),
(5, 2, 9, 6, NULL, '2025-11-03 10:20:48');

-- --------------------------------------------------------

--
-- Structure de la table `log`
--

CREATE TABLE `log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `log`
--

INSERT INTO `log` (`id`, `user_id`, `action`, `description`, `created_at`) VALUES
(2, NULL, 'login_failed', 'Echec de connexion pour dodo', '2025-11-01 16:43:52'),
(3, NULL, 'demande_verification', 'Code envoyé à l’utilisateur dodo', '2025-11-01 16:44:32'),
(4, NULL, 'envoi_code_verification', 'Code envoyé à manoachdodo@gmail.com pour dodo', '2025-11-01 16:44:44'),
(5, NULL, 'verification_reussie', 'Connexion réussie pour dodo', '2025-11-01 16:45:14'),
(6, 1, 'Ajout', 'Nouvel étudiant : DODO Manoach, ID : 1', '2025-11-01 16:50:40'),
(7, 1, 'modifier_etudiant', 'Étudiant modifié : DODO Manoach, ID : 1', '2025-11-01 17:14:54'),
(8, 1, 'deconnexion', 'L\'utilisateur dodo s\'est déconnecté.', '2025-11-01 17:16:14'),
(9, NULL, 'demande_verification', 'Code envoyé à l’utilisateur admin', '2025-11-01 17:17:42'),
(10, NULL, 'envoi_code_verification', 'Code envoyé à manoach456@gmail.com pour admin', '2025-11-01 17:17:59'),
(11, NULL, 'verification_reussie', 'Connexion réussie pour admin', '2025-11-01 17:18:24'),
(12, 2, 'ajouter_etudiant', 'Nouvel étudiant : Mandela Nelson, ID : 2', '2025-11-01 17:24:31'),
(13, NULL, 'demande_verification', 'Code envoyé à l’utilisateur dodo', '2025-11-01 17:26:17'),
(14, NULL, 'envoi_code_verification', 'Code envoyé à manoachdodo@gmail.com pour dodo', '2025-11-01 17:26:46'),
(15, NULL, 'verification_reussie', 'Connexion réussie pour dodo', '2025-11-01 17:27:16'),
(16, 1, 'Consultation des inscriptions', 'L\'utilisateur a consulté la liste des inscriptions', '2025-11-01 17:28:58'),
(17, 1, 'Consultation des inscriptions', 'L\'utilisateur a consulté la liste des inscriptions', '2025-11-01 17:29:08'),
(18, 1, 'deconnexion', 'L\'utilisateur dodo s\'est déconnecté.', '2025-11-01 17:29:40'),
(19, NULL, 'demande_verification', 'Code envoyé à l’utilisateur dodo', '2025-11-01 17:37:14'),
(20, NULL, 'envoi_code_verification', 'Code envoyé à manoachdodo@gmail.com pour dodo', '2025-11-01 17:37:28'),
(21, NULL, 'verification_reussie', 'Connexion réussie pour dodo', '2025-11-01 17:37:47'),
(22, 1, 'deconnexion', 'L\'utilisateur dodo s\'est déconnecté.', '2025-11-01 17:51:04'),
(23, NULL, 'demande_verification', 'Code envoyé à l’utilisateur dodo', '2025-11-01 21:12:01'),
(24, NULL, 'envoi_code_verification', 'Code envoyé à manoachdodo@gmail.com pour dodo', '2025-11-01 21:12:23'),
(25, NULL, 'verification_reussie', 'Connexion réussie pour dodo', '2025-11-01 21:13:04'),
(26, 2, 'ajouter_etudiant', 'Nouvel étudiant : Barack Obama, ID : 3', '2025-11-01 21:57:29');

-- --------------------------------------------------------

--
-- Structure de la table `matiere`
--

CREATE TABLE `matiere` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `libelle` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `matiere`
--

INSERT INTO `matiere` (`id`, `code`, `libelle`) VALUES
(1, 'INF101', 'Programmation'),
(2, 'INF102', 'Algèbre'),
(3, 'INF103', 'Réseaux'),
(4, 'INF104', 'Base de données'),
(5, 'INF105', 'Systèmes'),
(6, 'INF201', 'Algorithmique avancée'),
(7, 'INF202', 'Structures de données'),
(8, 'INF203', 'Système d’exploitation'),
(9, 'INF204', 'Ingénierie logicielle'),
(10, 'INF205', 'Sécurité informatique'),
(11, 'MI301', 'Intelligence Artificielle'),
(12, 'MI302', 'Big Data'),
(13, 'MI303', 'Cloud Computing'),
(14, 'MI304', 'Machine Learning'),
(15, 'MI305', 'Blockchain'),
(16, 'MG301', 'Management'),
(17, 'MG302', 'Comptabilité'),
(18, 'MG303', 'Marketing'),
(19, 'MG304', 'Statistiques'),
(20, 'MG305', 'Droit des affaires');

-- --------------------------------------------------------

--
-- Structure de la table `note`
--

CREATE TABLE `note` (
  `id` int(11) NOT NULL,
  `matiere_id` int(11) NOT NULL,
  `inscription_id` int(11) NOT NULL,
  `val_note` decimal(5,2) DEFAULT NULL CHECK (`val_note` >= 0 and `val_note` <= 20),
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `note`
--

INSERT INTO `note` (`id`, `matiere_id`, `inscription_id`, `val_note`, `created_by`, `created_at`) VALUES
(1, 17, 1, 12.00, NULL, '2025-11-03 10:18:05'),
(2, 17, 4, 12.25, NULL, '2025-11-03 10:22:54');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` tinyint(1) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_activity` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `username`, `email`, `password`, `role`, `active`, `created_at`, `last_activity`) VALUES
(1, 'dodo', 'manoachdodo@gmail.com', '$2y$10$OP1GrlscJbIbpawB7wmAaezCHsRW9o54JfuVSJfwcgRb56ZR4mxmi', 0, 1, '2025-11-01 16:44:20', '2025-11-04 15:42:34'),
(2, 'admin', 'manoach456@gmail.com', '$2y$10$SrbtUdUzCeyA81gjqn/CC.hNnSlHEu2BkjT1sIECIj8gIxMeNbdNW', 1, 1, '2025-11-01 17:17:33', '2025-11-04 15:43:41');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `annee`
--
ALTER TABLE `annee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `libelle` (`libelle`);

--
-- Index pour la table `classe`
--
ALTER TABLE `classe`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `libelle` (`libelle`);

--
-- Index pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Index pour la table `inscription`
--
ALTER TABLE `inscription`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_etudiant_annee` (`etudiant_id`,`annee_id`),
  ADD UNIQUE KEY `unique_inscription` (`etudiant_id`,`annee_id`),
  ADD KEY `classe_id` (`classe_id`),
  ADD KEY `annee_id` (`annee_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Index pour la table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `matiere`
--
ALTER TABLE `matiere`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Index pour la table `note`
--
ALTER TABLE `note`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_note` (`inscription_id`,`matiere_id`),
  ADD UNIQUE KEY `uniq_matiere_inscription` (`matiere_id`,`inscription_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `annee`
--
ALTER TABLE `annee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `classe`
--
ALTER TABLE `classe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `etudiant`
--
ALTER TABLE `etudiant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `inscription`
--
ALTER TABLE `inscription`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `log`
--
ALTER TABLE `log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `matiere`
--
ALTER TABLE `matiere`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `note`
--
ALTER TABLE `note`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD CONSTRAINT `etudiant_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `utilisateur` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `inscription`
--
ALTER TABLE `inscription`
  ADD CONSTRAINT `inscription_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiant` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscription_ibfk_2` FOREIGN KEY (`classe_id`) REFERENCES `classe` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscription_ibfk_3` FOREIGN KEY (`annee_id`) REFERENCES `annee` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscription_ibfk_4` FOREIGN KEY (`created_by`) REFERENCES `utilisateur` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utilisateur` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `note_ibfk_1` FOREIGN KEY (`matiere_id`) REFERENCES `matiere` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `note_ibfk_2` FOREIGN KEY (`inscription_id`) REFERENCES `inscription` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `note_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `utilisateur` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
