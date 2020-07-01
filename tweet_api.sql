-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le :  mer. 01 juil. 2020 à 20:49
-- Version du serveur :  10.2.3-MariaDB-log
-- Version de PHP :  7.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `tweet_api`
--

-- --------------------------------------------------------

--
-- Structure de la table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `tags`
--

INSERT INTO `tags` (`id`, `nom`) VALUES
(3, '#VDM'),
(4, '#jaimepasleselfesdetoutefacons'),
(5, '#cestpasfaux'),
(6, '#onsennuiesurdagobah'),
(12, '#barbeuk');

-- --------------------------------------------------------

--
-- Structure de la table `tweet`
--

CREATE TABLE `tweet` (
  `id` int(11) NOT NULL,
  `user` varchar(16) CHARACTER SET utf8 NOT NULL,
  `message` text CHARACTER SET utf8 NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `tweet`
--

INSERT INTO `tweet` (`id`, `user`, `message`, `created_at`) VALUES
(3, 'Yoda_du_63', 'La force n\'a pas été avec moi...', '2020-06-30 21:22:45'),
(4, 'Perceval', 'Ce qui compte c\'est les valeurs', '2020-06-30 21:31:35'),
(5, 'Gimli', 'La bonne taille c\'est quand les pieds touchent le sol', '2020-06-30 21:32:29'),
(6, 'Perceval', 'Y\'en a gros!', '2020-06-30 21:49:26'),
(15, 'Jeanne', 'Ils m\'ont pas cru ils m\'auront cuit!', '2020-07-01 17:38:09');

-- --------------------------------------------------------

--
-- Structure de la table `tweet_has_tag`
--

CREATE TABLE `tweet_has_tag` (
  `tweet_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `tweet_has_tag`
--

INSERT INTO `tweet_has_tag` (`tweet_id`, `tag_id`) VALUES
(3, 3),
(3, 6),
(4, 5),
(5, 4),
(6, 3),
(6, 5),
(15, 3),
(15, 12);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`) USING BTREE;

--
-- Index pour la table `tweet`
--
ALTER TABLE `tweet`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Index pour la table `tweet_has_tag`
--
ALTER TABLE `tweet_has_tag`
  ADD PRIMARY KEY (`tweet_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `tweet`
--
ALTER TABLE `tweet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `tweet_has_tag`
--
ALTER TABLE `tweet_has_tag`
  ADD CONSTRAINT `tweet_has_tag_ibfk_1` FOREIGN KEY (`tweet_id`) REFERENCES `tweet` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `tweet_has_tag_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
