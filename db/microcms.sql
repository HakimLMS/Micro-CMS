-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Jeu 10 Août 2017 à 19:40
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `microcms`
--

-- --------------------------------------------------------

--
-- Structure de la table `t_articles`
--

CREATE TABLE `t_articles` (
  `art_id` int(11) NOT NULL,
  `art_title` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `art_content` text COLLATE utf8_unicode_ci NOT NULL,
  `art_state` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `t_articles`
--

INSERT INTO `t_articles` (`art_id`, `art_title`, `art_content`, `art_state`) VALUES
(1, 'Chapitre Premier', 'Il était une fois un loup', 'publie'),
(5, 'Chapitre 2', '<p>Ce loup &eacute;tait bizarre</p>', 'publie'),
(6, 'Chapitre 3', '<p>Il avait une jambe plus coure que l\'autre !</p>', 'publie'),
(7, 'Chapitre 4', '<p>Pauvre loup, en Alsaska il y &agrave; plein de trappeurs heureux qui veulent le domestiquer.</p>', 'publie'),
(8, 'Chapitre 5', '<p>Mais heureusement que les trappeurs aiment avant tout chipper.</p>', 'publie'),
(9, 'Chapitre 6', '<p>Et comme disent les autocthones au sac &agrave; dos rouge, chippeur arr&ecirc;te de chipper.</p>', 'publie'),
(10, 'Chapitre 7', '<p>Syndr&ocirc;me de la page blanche !</p>', 'brouillon');

-- --------------------------------------------------------

--
-- Structure de la table `t_comment`
--

CREATE TABLE `t_comment` (
  `com_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `com_author` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `com_mail` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `com_content` text COLLATE utf8_unicode_ci NOT NULL,
  `art_id` int(11) NOT NULL,
  `t_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `t_state` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `t_comment`
--

INSERT INTO `t_comment` (`com_id`, `parent_id`, `com_author`, `com_mail`, `com_content`, `art_id`, `t_date`, `t_state`) VALUES
(9, 0, 'WWF', 'wwf@wwf.org', 'Gaffe à la meute !', 1, NULL, 'publie'),
(10, 0, 'Brasens', 'GB@JB.fr', 'J\'aurais plutôt dit gare au Gorille !', 1, NULL, 'publie'),
(11, 10, 'SNCF', 'palapala@sncf.fr', 'Et moi Gare Montparnasse.', 1, NULL, 'publie'),
(12, 11, 'Troll', 'Troll@troll.fr', 'Le commentaire ne correspondant pas aux CGU à été modéré', 1, NULL, 'modéré'),
(13, 11, 'Cpt Obvious', 'cpt@cpt.fr', 'Don\'t feed the troll !', 1, '2017-08-10 17:46:09', 'publie'),
(14, 0, 'Jeanblaguin', 'humoriste@sav.fr', 'Bonjour les amis si vous continuez je vais plus avoir de boulot !', 1, NULL, 'publie'),
(15, 0, 'Prixgoncourt', 'P@g.fr', 'Espérons qu\'il n\'aurait jamais le syndrome de la page blanche !', 9, NULL, 'publie'),
(16, 15, 'Pierro', 'p@p.com', 'Tu l\'as dit bouffi !', 9, NULL, 'publie'),
(17, 16, 'Prixgoncourt', 'P@g.fr', 'Voyons nous n\'avons pas élevé les cochons ensemble !', 9, '2017-08-10 18:27:52', 'publie'),
(18, 0, 'JJ Abrams', 'GOT@Deneris.com', 'Quel suspens INSOUTENABLE !', 5, NULL, 'publie'),
(19, 0, 'Tolkien', 'hent@h.fr', 'J\'en suis tout émoustillé !', 5, NULL, 'publie');

-- --------------------------------------------------------

--
-- Structure de la table `t_user`
--

CREATE TABLE `t_user` (
  `usr_id` int(11) NOT NULL,
  `usr_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `usr_mail` varchar(55) COLLATE utf8_unicode_ci NOT NULL,
  `usr_password` varchar(88) COLLATE utf8_unicode_ci NOT NULL,
  `usr_salt` varchar(23) COLLATE utf8_unicode_ci NOT NULL,
  `usr_role` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `t_user`
--

INSERT INTO `t_user` (`usr_id`, `usr_name`, `usr_mail`, `usr_password`, `usr_salt`, `usr_role`) VALUES
(1, 'Admin', 'admin@admin.fr', '$2y$13$A8MQM2ZNOi99EW.ML7srhOJsCaybSbexAj/0yXrJs4gQ/2BqMMW2K', 'EDDsl&fBCJB|a5XUtAlnQN8', 'ROLE_ADMIN');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `t_articles`
--
ALTER TABLE `t_articles`
  ADD PRIMARY KEY (`art_id`);

--
-- Index pour la table `t_comment`
--
ALTER TABLE `t_comment`
  ADD PRIMARY KEY (`com_id`),
  ADD KEY `fk_com_art` (`art_id`);

--
-- Index pour la table `t_user`
--
ALTER TABLE `t_user`
  ADD PRIMARY KEY (`usr_id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `t_articles`
--
ALTER TABLE `t_articles`
  MODIFY `art_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT pour la table `t_comment`
--
ALTER TABLE `t_comment`
  MODIFY `com_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT pour la table `t_user`
--
ALTER TABLE `t_user`
  MODIFY `usr_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `t_comment`
--
ALTER TABLE `t_comment`
  ADD CONSTRAINT `fk_com_art` FOREIGN KEY (`art_id`) REFERENCES `t_articles` (`art_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
