-- phpMyAdmin SQL Dump
-- version 4.5.3.1
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Mer 06 Janvier 2016 à 22:30
-- Version du serveur :  10.1.10-MariaDB-log
-- Version de PHP :  7.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `dbcitharel`
--

-- --------------------------------------------------------

--
-- Structure de la table `ATTACHMENT`
--

CREATE TABLE `ATTACHMENT` (
  `url` varchar(255) NOT NULL,
  `title` text,
  `summary` text,
  `picture` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `ATTACHMENT`
--

INSERT INTO `ATTACHMENT` (`url`, `title`, `summary`, `picture`) VALUES
('http://twig.sensiolabs.org/doc/filters/raw.html', 'raw - Documentation - Twig', 'The raw filter marks the value as being "safe", which means that in an environment with automatic escaping enabled this variable will not be escaped if raw is the last filter applied to it: {% autoescape %} {{ var|raw }} {# var won\'t be escaped #} {% endautoescape %} Note Be careful when using the &hellip;', NULL),
('http://www.lemouvementcommun.fr/les-communs-la-vrai-idee-revolutionnaire/', 'Le mouvement commun » « Les communs, la vrai idée révolutionnaire »', 'Pourtant mentionnés dans le code civil, ces biens dont « l’usage est commun à tous » auraient pu disparaître si la crise du système néo-libéral ne les avait remises au goût du jour. Biens communs, ou communs, voilà un concept nouveau, à l’intersection des sciences politiques et de l’économie. Un concept à la mode, qui génère moult &hellip;', 'http://www.lemouvementcommun.fr/wp-content/uploads/2015/12/MARIANNE_LOGO.png'),
('https://github.com/j0k3r/graby', 'j0k3r/graby', 'README.md Graby helps you extract article content from web pages. This is a fork of Full-Text RSS v3.3 from @fivefilters . Why this fork ? Full-Text RSS works great as a standalone application. But when you need to encapsulate it in your own library it\'s a mess. You need this kind of ugly thing: $article &hellip;', 'https://avatars1.githubusercontent.com/u/62333?v=3&s=400');

-- --------------------------------------------------------

--
-- Structure de la table `COMMENT`
--

CREATE TABLE `COMMENT` (
  `idcomment` int(11) NOT NULL,
  `idmembre` int(11) DEFAULT NULL,
  `idpost` int(11) DEFAULT NULL,
  `contenucomment` text,
  `datecommentaire` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `COMMENT`
--

INSERT INTO `COMMENT` (`idcomment`, `idmembre`, `idpost`, `contenucomment`, `datecommentaire`) VALUES
(1, 29, 28, 'Hého', '2016-01-20');

-- --------------------------------------------------------

--
-- Structure de la table `CONVERSATIONS`
--

CREATE TABLE `CONVERSATIONS` (
  `identifiant` int(11) NOT NULL,
  `titre` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `CONVERSATIONS`
--

INSERT INTO `CONVERSATIONS` (`identifiant`, `titre`) VALUES
(1, ''),
(2, ''),
(4, 'test'),
(5, 'test2'),
(6, 'test3');

-- --------------------------------------------------------

--
-- Structure de la table `LIKES`
--

CREATE TABLE `LIKES` (
  `idpost` int(11) NOT NULL,
  `idmembre` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `LIKES`
--

INSERT INTO `LIKES` (`idpost`, `idmembre`) VALUES
(21, 29),
(22, 32),
(28, 29),
(28, 32);

-- --------------------------------------------------------

--
-- Structure de la table `MEMBER`
--

CREATE TABLE `MEMBER` (
  `idmembre` int(11) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `mail` varchar(100) DEFAULT NULL,
  `dateNaissance` date DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `dateInscription` date DEFAULT NULL,
  `dateLastConnexion` date DEFAULT NULL,
  `Friends` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `MEMBER`
--

INSERT INTO `MEMBER` (`idmembre`, `nom`, `prenom`, `mail`, `dateNaissance`, `password`, `dateInscription`, `dateLastConnexion`, `Friends`) VALUES
(29, 'Citharel', 'Thomas', 'tcit@tcit.fr', '1994-05-04', '$2y$10$mxr8lbZoAmnlK7utzLm1FuTjyy6N8jbrvMxxjWqDrRL6Dtz9XlLj6', '2015-12-23', '2015-12-23', '{"1":"32","2":"33"}'),
(31, 'Citharel', 'Clément', 'clément@tcit.fr', '1994-05-04', '$2y$10$eVGbWR4dIwq.vfTzLLyxqe756ziH3DlBpPBRV0dpSTD1pqVdR8S1q', '2015-12-24', '2015-12-24', '["29","33","32"]'),
(32, 'Citharel', 'Philippe', 'toto@titi.fr', '1994-05-04', '$2y$10$oNlal0JE0EBA6rVWDD0e5Oi81x1Ilm5p7iT3Z9nai3pkpeHgDENDi', '2015-12-24', '2015-12-24', '["29"]'),
(33, 'Citharel', 'Maurice', 'momo@tcit.fr', '1932-06-11', '$2y$10$Fs.q1slHZWsLyztvA9eCaOuMFeE83RN/jZaJo6xseVaDYFdCkHWAO', '2015-12-29', '2015-12-29', '');

-- --------------------------------------------------------

--
-- Structure de la table `MP`
--

CREATE TABLE `MP` (
  `idmp` int(11) NOT NULL,
  `idconversation` int(11) DEFAULT NULL,
  `idmembre` int(11) DEFAULT NULL,
  `contenump` text,
  `datemp` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `MP`
--

INSERT INTO `MP` (`idmp`, `idconversation`, `idmembre`, `contenump`, `datemp`) VALUES
(3, 1, 29, 'je dis coucou', '2015-12-16'),
(4, 1, 31, 'je réponds !', '2015-12-17'),
(5, 1, 29, 'tutu', '2015-12-30'),
(6, 1, 29, 'tata', '2015-12-30'),
(7, 1, 29, 'encore ?', '2015-12-30'),
(8, 1, 31, 'bah oui', '2015-12-31'),
(9, 1, 31, 'et alors ?', '2015-12-31'),
(10, 1, 29, 'bah ça fait long', '2015-12-31'),
(16, 1, 31, 'lol', '2015-12-31'),
(26, 1, 29, 'je rep', '2015-12-31'),
(27, 1, 31, 'moi aussi', '2015-12-31'),
(28, 1, 29, 'hého', '2015-12-31'),
(29, 1, 31, 'houhou', '2015-12-31'),
(30, 2, 29, 'start', '2015-12-23'),
(31, 2, 29, 'more ?', '2015-12-31'),
(32, 4, 31, 'hého', '2016-01-01'),
(33, 5, 31, 'salut vous deux', '2016-01-01'),
(34, 6, 31, 'salut tout le monde', '2016-01-01');

-- --------------------------------------------------------

--
-- Structure de la table `PARTICIPENT`
--

CREATE TABLE `PARTICIPENT` (
  `idconversation` int(11) NOT NULL,
  `idmembre` int(11) NOT NULL,
  `markread` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `PARTICIPENT`
--

INSERT INTO `PARTICIPENT` (`idconversation`, `idmembre`, `markread`) VALUES
(1, 29, 0),
(1, 31, 0),
(2, 29, 0),
(2, 33, 1),
(4, 29, 0),
(4, 31, 0),
(5, 29, 0),
(5, 31, 0),
(5, 33, 1),
(6, 29, 0),
(6, 31, 0),
(6, 32, 1),
(6, 33, 0);

-- --------------------------------------------------------

--
-- Structure de la table `POST`
--

CREATE TABLE `POST` (
  `idpost` int(11) NOT NULL,
  `idmembre` int(11) DEFAULT NULL,
  `contenupost` text,
  `datemessage` datetime DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `POST`
--

INSERT INTO `POST` (`idpost`, `idmembre`, `contenupost`, `datemessage`, `attachment`) VALUES
(14, 29, 'msg 1', '2015-12-24 03:15:44', NULL),
(15, 29, 'msg2', '2015-12-24 03:15:50', NULL),
(16, 31, 'hello world !', '2015-12-24 03:33:23', NULL),
(17, 32, 'Un troisième citharel dit : coucou', '2015-12-26 02:00:35', NULL),
(18, 29, '**coucou de Thomas**', '2015-12-26 04:12:49', NULL),
(19, 29, ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque maximus nunc non ligula maximus accumsan. Nunc quis facilisis leo. Donec est neque, lacinia in facilisis eget, tincidunt nec nisl. Duis finibus in metus ut tempor. In volutpat ullamcorper mauris, eu mattis mi posuere id. Praesent congue ornare nibh vitae interdum. Integer vehicula risus eu felis tristique, nec egestas ex sagittis. Etiam blandit turpis sapien, ac ultrices magna congue fringilla. Maecenas ornare risus nisl. Proin convallis a risus id sollicitudin. Fusce vehicula non metus nec pretium. ', '2015-12-29 12:05:14', NULL),
(21, 29, 'un autre', '2015-12-29 03:06:16', NULL),
(22, 29, 'un bon', '2015-12-29 15:07:51', NULL),
(28, 33, 'toto\r\n', '2015-12-29 15:25:02', NULL),
(29, 29, 'J\'ai utilisé https://github.com/j0k3r/graby surtout', '2016-01-05 23:06:15', 'https://github.com/j0k3r/graby'),
(45, 29, 'http://twig.sensiolabs.org/doc/filters/raw.html', '2016-01-06 21:23:36', 'http://twig.sensiolabs.org/doc/filters/raw.html');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `ATTACHMENT`
--
ALTER TABLE `ATTACHMENT`
  ADD PRIMARY KEY (`url`),
  ADD KEY `url` (`url`);

--
-- Index pour la table `COMMENT`
--
ALTER TABLE `COMMENT`
  ADD PRIMARY KEY (`idcomment`),
  ADD KEY `FKCommentMembre` (`idmembre`),
  ADD KEY `idpost` (`idpost`);

--
-- Index pour la table `CONVERSATIONS`
--
ALTER TABLE `CONVERSATIONS`
  ADD PRIMARY KEY (`identifiant`);

--
-- Index pour la table `LIKES`
--
ALTER TABLE `LIKES`
  ADD PRIMARY KEY (`idpost`,`idmembre`),
  ADD KEY `idpost` (`idpost`),
  ADD KEY `idmembre` (`idmembre`);

--
-- Index pour la table `MEMBER`
--
ALTER TABLE `MEMBER`
  ADD PRIMARY KEY (`idmembre`);

--
-- Index pour la table `MP`
--
ALTER TABLE `MP`
  ADD PRIMARY KEY (`idmp`),
  ADD KEY `idmembre` (`idmembre`),
  ADD KEY `FKConversationMP` (`idconversation`);

--
-- Index pour la table `PARTICIPENT`
--
ALTER TABLE `PARTICIPENT`
  ADD PRIMARY KEY (`idconversation`,`idmembre`),
  ADD KEY `idmembre` (`idmembre`),
  ADD KEY `idconversation` (`idconversation`);

--
-- Index pour la table `POST`
--
ALTER TABLE `POST`
  ADD PRIMARY KEY (`idpost`),
  ADD KEY `FKPostMembre` (`idmembre`),
  ADD KEY `attachment` (`attachment`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `COMMENT`
--
ALTER TABLE `COMMENT`
  MODIFY `idcomment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `CONVERSATIONS`
--
ALTER TABLE `CONVERSATIONS`
  MODIFY `identifiant` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT pour la table `MEMBER`
--
ALTER TABLE `MEMBER`
  MODIFY `idmembre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT pour la table `MP`
--
ALTER TABLE `MP`
  MODIFY `idmp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT pour la table `POST`
--
ALTER TABLE `POST`
  MODIFY `idpost` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `COMMENT`
--
ALTER TABLE `COMMENT`
  ADD CONSTRAINT `FKCommentMembre` FOREIGN KEY (`idmembre`) REFERENCES `MEMBER` (`idmembre`),
  ADD CONSTRAINT `FKCommentPost` FOREIGN KEY (`idpost`) REFERENCES `POST` (`idpost`);

--
-- Contraintes pour la table `LIKES`
--
ALTER TABLE `LIKES`
  ADD CONSTRAINT `FKLikeMember` FOREIGN KEY (`idmembre`) REFERENCES `MEMBER` (`idmembre`),
  ADD CONSTRAINT `FKLikePost` FOREIGN KEY (`idpost`) REFERENCES `POST` (`idpost`);

--
-- Contraintes pour la table `MP`
--
ALTER TABLE `MP`
  ADD CONSTRAINT `FKConversationMP` FOREIGN KEY (`idconversation`) REFERENCES `CONVERSATIONS` (`identifiant`),
  ADD CONSTRAINT `FKMEMBERMP` FOREIGN KEY (`idmembre`) REFERENCES `MEMBER` (`idmembre`);

--
-- Contraintes pour la table `PARTICIPENT`
--
ALTER TABLE `PARTICIPENT`
  ADD CONSTRAINT `FKMembreParticipe` FOREIGN KEY (`idmembre`) REFERENCES `MEMBER` (`idmembre`),
  ADD CONSTRAINT `FKParticipeConversation` FOREIGN KEY (`idconversation`) REFERENCES `CONVERSATIONS` (`identifiant`);

--
-- Contraintes pour la table `POST`
--
ALTER TABLE `POST`
  ADD CONSTRAINT `FKPostAttachment` FOREIGN KEY (`attachment`) REFERENCES `ATTACHMENT` (`url`) ON DELETE CASCADE,
  ADD CONSTRAINT `FKPostMembre` FOREIGN KEY (`idmembre`) REFERENCES `MEMBER` (`idmembre`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
