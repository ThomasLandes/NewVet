-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 26 août 2024 à 03:42
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
-- Base de données : `new_vet`
--

-- --------------------------------------------------------

--
-- Structure de la table `adresse`
--

CREATE TABLE `adresse` (
  `adresse_id` int(11) NOT NULL,
  `adresse_prenom` varchar(30) DEFAULT NULL,
  `adresse_nom` varchar(50) DEFAULT NULL,
  `adresse_rue` varchar(100) DEFAULT NULL,
  `adresse_complement` varchar(50) DEFAULT NULL,
  `adresse_ville` varchar(50) DEFAULT NULL,
  `adresse_region` varchar(50) DEFAULT NULL,
  `adresse_pays` varchar(50) DEFAULT NULL,
  `adresse_tel` char(10) DEFAULT NULL,
  `utilisateur_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `categorie_id` int(11) NOT NULL,
  `categorie_nom` varchar(50) DEFAULT NULL,
  `categorie_desc` varchar(250) DEFAULT NULL,
  `categorie_image` varchar(200) NOT NULL,
  `categorie_highlight` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`categorie_id`, `categorie_nom`, `categorie_desc`, `categorie_image`, `categorie_highlight`) VALUES
(1, 'PANTALON', 'Découvrez notre collection de pantalons pour femmes, alliant confort, style et qualité. Du jean classique au chino élégant, trouvez le modèle parfait pour chaque occasion et affirmez votre style.', '..\\IMAGE\\Categorie\\Pantalon.jpg', 1),
(2, 'ROBE', 'Explorez notre sélection de robes élégantes pour toutes les occasions. Que ce soit pour une soirée chic ou une journée décontractée, trouvez la robe parfaite qui sublime votre style avec confort et élégance.', '..\\IMAGE\\Categorie\\Robe.jpg', 0),
(3, 'HAUT', 'Trouvez des t-shirts basiques, confortables et essentiels pour votre garde-robe. Des modèles simples, polyvalents et parfaits pour un look décontracté au quotidien.', '..\\IMAGE\\Categorie\\Haut.jpg', 1),
(4, 'SURVETEMENT', 'Découvrez nos survêtements alliant confort et style, parfaits pour le sport ou la détente. Des ensembles modernes et fonctionnels, idéaux pour un look casual ou athlétique en toute simplicité.\r\n\r\n\r\n\r\n\r\n\r\n\r\n', '..\\IMAGE\\Categorie\\Survetement.jpg', 0),
(5, 'PULL', 'Explorez notre collection de pulls, alliant chaleur et style. Des modèles classiques aux designs tendance, trouvez le pull parfait pour chaque saison, idéal pour superposer ou porter seul.', '..\\IMAGE\\Categorie\\Pull.jpg', 1);

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `commande_id` int(11) NOT NULL,
  `commande_date_crea` datetime DEFAULT NULL,
  `commande_date_envoi` datetime DEFAULT NULL,
  `adresse_livraison_id` int(11) NOT NULL,
  `adresse_facturation_id` int(11) NOT NULL,
  `paiement_id` int(11) NOT NULL,
  `utilisateur_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `composition`
--

CREATE TABLE `composition` (
  `produit_id` int(11) NOT NULL,
  `materiau_id` int(11) NOT NULL,
  `composition_pourcentage` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `composition`
--

INSERT INTO `composition` (`produit_id`, `materiau_id`, `composition_pourcentage`) VALUES
(1, 3, 100),
(2, 3, 100),
(3, 3, 100),
(4, 3, 60),
(4, 4, 40),
(5, 1, 80),
(5, 2, 20),
(6, 1, 30),
(6, 4, 65),
(6, 6, 5),
(7, 3, 100),
(8, 3, 100),
(9, 1, 10),
(9, 2, 10),
(9, 3, 80),
(10, 3, 100),
(11, 3, 100),
(12, 3, 70),
(12, 4, 30),
(13, 3, 70),
(13, 4, 30),
(14, 1, 10),
(14, 3, 65),
(14, 4, 20),
(14, 6, 5),
(15, 3, 80),
(15, 4, 20),
(16, 3, 70),
(16, 4, 30);

-- --------------------------------------------------------

--
-- Structure de la table `contenu_commande`
--

CREATE TABLE `contenu_commande` (
  `produit_id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `contenu_quantite` tinyint(4) DEFAULT NULL,
  `contenu_prix_unite` decimal(15,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contenu_panier`
--

CREATE TABLE `contenu_panier` (
  `produit_id` int(11) NOT NULL,
  `panier_id` int(11) NOT NULL,
  `contenu_panier_quantite` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `illustration_produit`
--

CREATE TABLE `illustration_produit` (
  `produit_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `illustration_produit`
--

INSERT INTO `illustration_produit` (`produit_id`, `image_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(2, 5),
(2, 6),
(2, 7),
(3, 8),
(3, 9),
(3, 10),
(3, 11),
(4, 12),
(4, 13),
(4, 14),
(4, 15),
(5, 16),
(5, 17),
(5, 18),
(5, 19),
(6, 20),
(6, 21),
(6, 22),
(6, 23),
(7, 24),
(7, 25),
(7, 26),
(8, 27),
(8, 28),
(8, 29),
(9, 30),
(9, 31),
(9, 32),
(10, 33),
(10, 34),
(10, 35),
(10, 36),
(11, 37),
(11, 38),
(11, 39),
(12, 40),
(12, 41),
(12, 42),
(12, 43),
(13, 44),
(13, 45),
(14, 46),
(14, 47),
(14, 48),
(14, 49),
(15, 50),
(15, 51),
(15, 52),
(16, 53),
(16, 54),
(16, 55),
(16, 56);

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE `image` (
  `image_id` int(11) NOT NULL,
  `image_nom` varchar(100) DEFAULT NULL,
  `image_lien` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`image_id`, `image_nom`, `image_lien`) VALUES
(1, 'Jeans 60s - 1', '..\\IMAGE\\Produit\\Pantalon\\Jeans 60s\\Jeans 60s - 1.jpg'),
(2, 'Jeans 60s - 2', '..\\IMAGE\\Produit\\Pantalon\\Jeans 60s\\Jeans 60s - 2.jpg'),
(3, 'Jeans 60s - 3', '..\\IMAGE\\Produit\\Pantalon\\Jeans 60s\\Jeans 60s - 3.jpg'),
(4, 'Jeans 60s - 4', '..\\IMAGE\\Produit\\Pantalon\\Jeans 60s\\Jeans 60s - 4.jpg'),
(5, 'Jeans Baggy - 1', '..\\IMAGE\\Produit\\Pantalon\\Jeans Baggy\\Jeans Baggy - 1.jpg'),
(6, 'Jeans Baggy - 2', '..\\IMAGE\\Produit\\Pantalon\\Jeans Baggy\\Jeans Baggy - 2.jpg'),
(7, 'Jeans Baggy - 3', '..\\IMAGE\\Produit\\Pantalon\\Jeans Baggy\\Jeans Baggy - 3.jpg'),
(8, 'Jeans Classic - 1', '..\\IMAGE\\Produit\\Pantalon\\Jeans Classic\\Jeans Classic - 1.jpg'),
(9, 'Jeans Classic - 2', '..\\IMAGE\\Produit\\Pantalon\\Jeans Classic\\Jeans Classic - 2.jpg'),
(10, 'Jeans Classic - 3', '..\\IMAGE\\Produit\\Pantalon\\Jeans Classic\\Jeans Classic - 3.jpg'),
(11, 'Jeans Classic - 4', '..\\IMAGE\\Produit\\Pantalon\\Jeans Classic\\Jeans Classic - 4.jpg'),
(12, 'Pantalon Pro - 1', '..\\IMAGE\\Produit\\Pantalon\\Pantalon Pro\\Pantalon Pro - 1.jpg'),
(13, 'Pantalon Pro - 2', '..\\IMAGE\\Produit\\Pantalon\\Pantalon Pro\\Pantalon Pro - 2.jpg'),
(14, 'Pantalon Pro - 3', '..\\IMAGE\\Produit\\Pantalon\\Pantalon Pro\\Pantalon Pro - 3.jpg'),
(15, 'Pantalon Pro - 4', '..\\IMAGE\\Produit\\Pantalon\\Pantalon Pro\\Pantalon Pro - 4.jpg'),
(16, 'Robe Loose - 1', '..\\IMAGE\\Produit\\Robe\\Robe Loose\\Robe Loose - 1.jpg'),
(17, 'Robe Loose - 2', '..\\IMAGE\\Produit\\Robe\\Robe Loose\\Robe Loose - 2.jpg'),
(18, 'Robe Loose - 3', '..\\IMAGE\\Produit\\Robe\\Robe Loose\\Robe Loose - 3.jpg'),
(19, 'Robe Loose - 4', '..\\IMAGE\\Produit\\Robe\\Robe Loose\\Robe Loose - 4.jpg'),
(20, 'Robe Summer - 1', '..\\IMAGE\\Produit\\Robe\\Robe Summer\\Robe Summer - 1.jpg'),
(21, 'Robe Summer - 2', '..\\IMAGE\\Produit\\Robe\\Robe Summer\\Robe Summer - 2.jpg'),
(22, 'Robe Summer - 3', '..\\IMAGE\\Produit\\Robe\\Robe Summer\\Robe Summer - 3.jpg'),
(23, 'Robe Summer - 4', '..\\IMAGE\\Produit\\Robe\\Robe Summer\\Robe Summer - 4.jpg'),
(24, 'Robe Summer II - 1', '..\\IMAGE\\Produit\\Robe\\Robe Summer II\\Robe Summer II - 1.jpg'),
(25, 'Robe Summer II - 2', '..\\IMAGE\\Produit\\Robe\\Robe Summer II\\Robe Summer II - 2.jpg'),
(26, 'Robe Summer II - 3', '..\\IMAGE\\Produit\\Robe\\Robe Summer II\\Robe Summer II - 3.jpg'),
(27, 'Haut Classic - 1', '..\\IMAGE\\Produit\\Haut\\Haut Classic\\Tee-Shirt Classic - 1.jpg'),
(28, 'Haut Classic - 2', '..\\IMAGE\\Produit\\Haut\\Haut Classic\\Tee-Shirt Classic - 2.jpg'),
(29, 'Haut Classic - 3', '..\\IMAGE\\Produit\\Haut\\Haut Classic\\Tee-Shirt Classic - 3.jpg'),
(30, 'Haut Mock - 1', '..\\IMAGE\\Produit\\Haut\\Haut Mock\\Tee-Shirt Mock - 1.jpg'),
(31, 'Haut Mock - 2', '..\\IMAGE\\Produit\\Haut\\Haut Mock\\Tee-Shirt Mock - 2.jpg'),
(32, 'Haut Mock - 3', '..\\IMAGE\\Produit\\Haut\\Haut Mock\\Tee-Shirt Mock - 3.jpg'),
(33, 'Haut Twill - 1', '..\\IMAGE\\Produit\\Haut\\Haut Twill\\Haut Twill - 1.jpg'),
(34, 'Haut Twill - 2', '..\\IMAGE\\Produit\\Haut\\Haut Twill\\Haut Twill - 2.jpg'),
(35, 'Haut Twill - 3', '..\\IMAGE\\Produit\\Haut\\Haut Twill\\Haut Twill - 3.jpg'),
(36, 'Haut Twill - 4', '..\\IMAGE\\Produit\\Haut\\Haut Twill\\Haut Twill - 4.jpg'),
(37, 'Survetement Terry - 1', '..\\IMAGE\\Produit\\Survetement\\Surevetement Terry\\Survetement Terry - 1.jpg'),
(38, 'Survetement Terry - 2', '..\\IMAGE\\Produit\\Survetement\\Surevetement Terry\\Survetement Terry - 2.jpg'),
(39, 'Survetement Terry - 3', '..\\IMAGE\\Produit\\Survetement\\Surevetement Terry\\Survetement Terry - 3.jpg'),
(40, 'Survetement Loose - 1', '..\\IMAGE\\Produit\\Survetement\\Survetement Loose\\Survetement Loose - 1.jpg'),
(41, 'Survetement Loose - 2', '..\\IMAGE\\Produit\\Survetement\\Survetement Loose\\Survetement Loose - 2.jpg'),
(42, 'Survetement Loose - 3', '..\\IMAGE\\Produit\\Survetement\\Survetement Loose\\Survetement Loose - 3.jpg'),
(43, 'Survetement Loose - 4', '..\\IMAGE\\Produit\\Survetement\\Survetement Loose\\Survetement Loose - 4.jpg'),
(44, 'Pull Heavy - 1', '..\\IMAGE\\Produit\\Pull\\Pull Heavy\\Pull Heavy - 1.jpg'),
(45, 'Pull Heavy - 2', '..\\IMAGE\\Produit\\Pull\\Pull Heavy\\Pull Heavy - 2.jpg'),
(46, 'Pull Hoodie - 1', '..\\IMAGE\\Produit\\Pull\\Pull Hoodie\\Pull Hoodie - 1.jpg'),
(47, 'Pull Hoodie - 2', '..\\IMAGE\\Produit\\Pull\\Pull Hoodie\\Pull Hoodie - 2.jpg'),
(48, 'Pull Hoodie - 3', '..\\IMAGE\\Produit\\Pull\\Pull Hoodie\\Pull Hoodie - 3.jpg'),
(49, 'Pull Hoodie - 4', '..\\IMAGE\\Produit\\Pull\\Pull Hoodie\\Pull Hoodie - 4.jpg'),
(50, 'Pull Standard - 1', '..\\IMAGE\\Produit\\Pull\\Pull Standard\\Pull Standard - 1.jpg'),
(51, 'Pull Standard - 2', '..\\IMAGE\\Produit\\Pull\\Pull Standard\\Pull Standard - 2.jpg'),
(52, 'Pull Standard - 3', '..\\IMAGE\\Produit\\Pull\\Pull Standard\\Pull Standard - 3.jpg'),
(53, 'Pull Zippy - 1', '..\\IMAGE\\Produit\\Pull\\Pull Zippy\\Pull Zippy - 1.jpg'),
(54, 'Pull Zippy - 2', '..\\IMAGE\\Produit\\Pull\\Pull Zippy\\Pull Zippy - 2.jpg'),
(55, 'Pull Zippy - 3', '..\\IMAGE\\Produit\\Pull\\Pull Zippy\\Pull Zippy - 3.jpg'),
(56, 'Pull Zippy - 4', '..\\IMAGE\\Produit\\Pull\\Pull Zippy\\Pull Zippy - 4.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `materiau`
--

CREATE TABLE `materiau` (
  `materiau_id` int(11) NOT NULL,
  `materiau_nom` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `materiau`
--

INSERT INTO `materiau` (`materiau_id`, `materiau_nom`) VALUES
(1, 'Viscose'),
(2, 'Polyamide'),
(3, 'Coton'),
(4, 'Polyester'),
(5, 'Lyocell'),
(6, 'Elastane'),
(7, 'Modal');

-- --------------------------------------------------------

--
-- Structure de la table `paiement`
--

CREATE TABLE `paiement` (
  `paiement_id` int(11) NOT NULL,
  `paiement_nom` varchar(50) DEFAULT NULL,
  `paiement_numero` varchar(50) DEFAULT NULL,
  `paiement_date_exp` char(4) DEFAULT NULL,
  `utilisateur_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

CREATE TABLE `panier` (
  `panier_id` int(11) NOT NULL,
  `panier_creation` datetime DEFAULT NULL,
  `panier_expiration` datetime DEFAULT NULL,
  `utilisateur_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `produit_id` int(11) NOT NULL,
  `produit_nom` varchar(50) DEFAULT NULL,
  `produit_desc` varchar(200) DEFAULT NULL,
  `produit_prix` decimal(5,2) DEFAULT NULL,
  `produit_stock` tinyint(4) DEFAULT NULL,
  `produit_highlander` tinyint(1) NOT NULL DEFAULT 0,
  `ordre_highlander` int(11) NOT NULL,
  `categorie_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`produit_id`, `produit_nom`, `produit_desc`, `produit_prix`, `produit_stock`, `produit_highlander`, `ordre_highlander`, `categorie_id`) VALUES
(1, 'Jeans 60s', 'Jeans pattes d\'éléphant des années 60 : coupe évasée, style rétro emblématique pour un look vintage chic.', 59.99, 10, 1, 5, 1),
(2, 'Jeans Baggy', 'Jeans baggy : coupe ample, look décontracté et urbain, symbole des années 90.', 49.99, 13, 0, 0, 1),
(3, 'Jeans Classic', 'Jeans coupe classique : ajustement droit, intemporel et polyvalent, idéal pour tous les styles.', 50.99, 0, 0, 0, 1),
(4, 'Pantalon Pro', 'Pantalon coupe professionnel : élégant, bien ajusté, parfait pour un look soigné au bureau.', 69.99, 6, 0, 0, 1),
(5, 'Robe Loose', 'Robe loose : coupe ample et décontractée, offrant confort et style pour un look chic sans effort.', 39.99, 0, 0, 0, 2),
(6, 'Robe Summer', 'Robe summer : légère et aérienne, idéale pour les journées ensoleillées avec un style frais et décontracté.', 34.99, 3, 0, 0, 2),
(7, 'Robe Summer II', 'Robe Summer II : chic et fluide, parfaite pour les journées estivales, alliant confort et élégance avec des détails estivaux raffinés.', 39.99, 15, 1, 1, 2),
(8, 'Haut Classic', 'Haut classique : t-shirt noir essentiel, simple et polyvalent, parfait pour toutes les occasions avec une touche élégante et intemporelle.\r\n\r\n\r\n\r\n', 19.99, 20, 0, 0, 3),
(9, 'Haut Mock', 'Haut mock neck : col montant subtil offrant une touche élégante et moderne, idéal pour une allure raffinée et contemporaine.', 24.99, 0, 0, 0, 3),
(10, 'Haut Twill', 'Haut twill corset : coupe ajustée en tissu twill, combinant structure et élégance pour un look sophistiqué et féminin.', 29.99, 5, 1, 4, 3),
(11, 'Survetement Terry', 'Survêtement terry : ensemble jogging en tissu éponge, doux et confortable, parfait pour le sport ou les moments de détente avec un style décontracté.', 33.99, 0, 0, 0, 4),
(12, 'Survetement Loose', 'Survêtement loose : coupe ample et décontractée, offrant une liberté de mouvement et un confort optimal pour les moments de détente.', 39.99, 10, 0, 0, 4),
(13, 'Pull Heavy', 'Pull heavy : épais et chaud, parfait pour se protéger du froid avec une touche de confort et de style robuste.', 79.99, 5, 1, 2, 5),
(14, 'Pull Hoodie', 'Hoodie : sweat à capuche polyvalent et confortable, idéal pour un look décontracté et une chaleur supplémentaire.', 59.99, 9, 0, 0, 5),
(15, 'Pull Standard', 'sweatshirt standard : classique et confortable, idéal pour un look décontracté avec une coupe simple et pratique.', 45.99, 18, 1, 3, 5),
(16, 'Pull Zippy ', 'Pull Zippy : sweat à capuche zippé, offrant polyvalence et facilité d\'ajustement pour un style casual et pratique.\r\n\r\n\r\n\r\n\r\n\r\n\r\n', 49.99, 20, 0, 0, 5);

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `role_nom` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `role`
--

INSERT INTO `role` (`role_id`, `role_nom`) VALUES
(1, 'admin'),
(2, 'client');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `utilisateur_id` int(11) NOT NULL,
  `utilisateur_email` varchar(50) DEFAULT NULL,
  `utilisateur_mdp` varchar(72) DEFAULT NULL,
  `utilisateur_token` varchar(64) NOT NULL,
  `utilisateur_is_valide` tinyint(1) DEFAULT 0,
  `role_id` int(11) NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`utilisateur_id`, `utilisateur_email`, `utilisateur_mdp`, `utilisateur_token`, `utilisateur_is_valide`, `role_id`) VALUES
(6, 'orbitalcrashamazon@gmail.com', '$2y$10$xjVL3q.3/VD4ZWMULj.kHuoL.ipA6f9QK5b0b88uDKm81IMYmBeqS', '', 1, 2),
(15, 'thomas.landes@limayrac.fr', '$2y$10$Kkc2z1g51YnPr3/3UhH.N.0IWzOmC9G6Qxp1YYZGYblYHFvousMty', '', 1, 2);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `adresse`
--
ALTER TABLE `adresse`
  ADD PRIMARY KEY (`adresse_id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`categorie_id`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`commande_id`),
  ADD KEY `adresse_id` (`adresse_livraison_id`),
  ADD KEY `adresse_id_1` (`adresse_facturation_id`),
  ADD KEY `paiement_id` (`paiement_id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `composition`
--
ALTER TABLE `composition`
  ADD PRIMARY KEY (`produit_id`,`materiau_id`),
  ADD KEY `materiau_id` (`materiau_id`);

--
-- Index pour la table `contenu_commande`
--
ALTER TABLE `contenu_commande`
  ADD PRIMARY KEY (`produit_id`,`commande_id`),
  ADD KEY `commande_id` (`commande_id`);

--
-- Index pour la table `contenu_panier`
--
ALTER TABLE `contenu_panier`
  ADD PRIMARY KEY (`produit_id`,`panier_id`),
  ADD KEY `panier_id` (`panier_id`);

--
-- Index pour la table `illustration_produit`
--
ALTER TABLE `illustration_produit`
  ADD PRIMARY KEY (`produit_id`,`image_id`),
  ADD KEY `image_id` (`image_id`);

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`image_id`);

--
-- Index pour la table `materiau`
--
ALTER TABLE `materiau`
  ADD PRIMARY KEY (`materiau_id`);

--
-- Index pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD PRIMARY KEY (`paiement_id`),
  ADD KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `panier`
--
ALTER TABLE `panier`
  ADD PRIMARY KEY (`panier_id`),
  ADD UNIQUE KEY `utilisateur_id` (`utilisateur_id`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`produit_id`),
  ADD KEY `categorie_id` (`categorie_id`);

--
-- Index pour la table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`utilisateur_id`),
  ADD UNIQUE KEY `utilisateur_email` (`utilisateur_email`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `adresse`
--
ALTER TABLE `adresse`
  MODIFY `adresse_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `categorie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `commande_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT pour la table `materiau`
--
ALTER TABLE `materiau`
  MODIFY `materiau_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `paiement`
--
ALTER TABLE `paiement`
  MODIFY `paiement_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `panier`
--
ALTER TABLE `panier`
  MODIFY `panier_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `produit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `utilisateur_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `adresse`
--
ALTER TABLE `adresse`
  ADD CONSTRAINT `adresse_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`utilisateur_id`);

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`adresse_livraison_id`) REFERENCES `adresse` (`adresse_id`),
  ADD CONSTRAINT `commande_ibfk_2` FOREIGN KEY (`adresse_facturation_id`) REFERENCES `adresse` (`adresse_id`),
  ADD CONSTRAINT `commande_ibfk_3` FOREIGN KEY (`paiement_id`) REFERENCES `paiement` (`paiement_id`),
  ADD CONSTRAINT `commande_ibfk_4` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`utilisateur_id`);

--
-- Contraintes pour la table `composition`
--
ALTER TABLE `composition`
  ADD CONSTRAINT `composition_ibfk_1` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`produit_id`),
  ADD CONSTRAINT `composition_ibfk_2` FOREIGN KEY (`materiau_id`) REFERENCES `materiau` (`materiau_id`);

--
-- Contraintes pour la table `contenu_commande`
--
ALTER TABLE `contenu_commande`
  ADD CONSTRAINT `contenu_commande_ibfk_1` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`produit_id`),
  ADD CONSTRAINT `contenu_commande_ibfk_2` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`commande_id`);

--
-- Contraintes pour la table `contenu_panier`
--
ALTER TABLE `contenu_panier`
  ADD CONSTRAINT `contenu_panier_ibfk_1` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`produit_id`),
  ADD CONSTRAINT `contenu_panier_ibfk_2` FOREIGN KEY (`panier_id`) REFERENCES `panier` (`panier_id`);

--
-- Contraintes pour la table `illustration_produit`
--
ALTER TABLE `illustration_produit`
  ADD CONSTRAINT `illustration_produit_ibfk_1` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`produit_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `illustration_produit_ibfk_2` FOREIGN KEY (`image_id`) REFERENCES `image` (`image_id`);

--
-- Contraintes pour la table `paiement`
--
ALTER TABLE `paiement`
  ADD CONSTRAINT `paiement_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`utilisateur_id`);

--
-- Contraintes pour la table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `panier_ibfk_1` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateur` (`utilisateur_id`);

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `produit_ibfk_1` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`categorie_id`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
