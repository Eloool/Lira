-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 25 sep. 2024 à 10:18
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
-- Base de données : `agiletools`
--

-- --------------------------------------------------------

--
-- Structure de la table `equipesprj`
--

CREATE TABLE `equipesprj` (
  `IdEq` smallint(11) NOT NULL,
  `NomEq` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `equipesprj`
--

INSERT INTO `equipesprj` (`IdEq`, `NomEq`) VALUES
(1, 'Equipe Alpha'),
(2, 'Equipe Bravo'),
(3, 'Equipe Charlie'),
(4, 'Equipe Delta'),
(5, 'Equipe Echo'),
(6, 'Equipe Foxtrot'),
(7, 'Equipe Golf'),
(8, 'Equipe Hotel'),
(9, 'Equipe India'),
(10, 'Equipe Juliette'),
(11, 'Equipe Kilo'),
(12, 'Equipe Lima');

-- --------------------------------------------------------

--
-- Structure de la table `etatstaches`
--

CREATE TABLE `etatstaches` (
  `IdEtat` smallint(4) NOT NULL,
  `DescEtat` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etatstaches`
--

INSERT INTO `etatstaches` (`IdEtat`, `DescEtat`) VALUES
(1, 'A faire'),
(2, 'En cours'),
(3, 'Terminé et TestUnitaire réalisé'),
(4, 'Test Fonctionnel Réalisé / Module intégré dans ver'),
(5, 'intégré dans version de production');

-- --------------------------------------------------------

--
-- Structure de la table `idees_bac_a_sable`
--

CREATE TABLE `idees_bac_a_sable` (
  `Id_Idee_bas` int(11) NOT NULL,
  `desc_Idee_bas` varchar(300) NOT NULL,
  `IdU` smallint(6) NOT NULL,
  `IdEq` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `idees_bac_a_sable`
--

INSERT INTO `idees_bac_a_sable` (`Id_Idee_bas`, `desc_Idee_bas`, `IdU`, `IdEq`) VALUES
(1, 'Améliorer la vitesse de traitement des données', 1, 1),
(2, 'Refactorisation du module de sécurité', 2, 2),
(3, 'Mise à jour de l\'interface utilisateur', 3, 3),
(4, 'Optimisation du code backend', 4, 4),
(5, 'Implémentation du nouveau design', 5, 5),
(6, 'Révision de la gestion des erreurs', 6, 6),
(7, 'Ajout de nouvelles fonctionnalités de test', 7, 7),
(8, 'Mise en place du CI/CD', 8, 8),
(9, 'Augmentation de la couverture des tests unitaires', 9, 9),
(10, 'Amélioration de la documentation technique', 10, 10),
(11, 'Sécurisation des endpoints API', 11, 11),
(12, 'Modernisation de la stack front-end', 12, 12);

-- --------------------------------------------------------

--
-- Structure de la table `prioritestaches`
--

CREATE TABLE `prioritestaches` (
  `idPriorite` tinyint(1) NOT NULL,
  `DescPriorite` varchar(15) NOT NULL,
  `valPriorite` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `prioritestaches`
--

INSERT INTO `prioritestaches` (`idPriorite`, `DescPriorite`, `valPriorite`) VALUES
(1, '1', 1),
(2, '2', 2),
(3, '3', 3),
(4, '4', 4),
(5, '5', 5),
(6, 'MUST (MoSCoW)', 5),
(7, 'SHOULD (MoSCoW)', 4),
(8, 'Could ', 2),
(9, 'WONT (MoSCoW)', 0);

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `IdR` varchar(6) NOT NULL,
  `DescR` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `roles`
--

INSERT INTO `roles` (`IdR`, `DescR`) VALUES
('PO', 'Product Owner'),
('RefDev', 'Référent Dev'),
('RefUi', 'Référent UI'),
('R_Anim', 'Référent Animation'),
('R_Mode', 'Référent Modélisation'),
('SM', 'Scrum Master');

-- --------------------------------------------------------

--
-- Structure de la table `rolesutilisateurprojet`
--

CREATE TABLE `rolesutilisateurprojet` (
  `IdU` smallint(6) NOT NULL,
  `IdR` varchar(6) NOT NULL,
  `IdEq` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `rolesutilisateurprojet`
--

INSERT INTO `rolesutilisateurprojet` (`IdU`, `IdR`, `IdEq`) VALUES
(1, 'PO', 1),
(2, 'RefDev', 2),
(3, 'SM', 3),
(4, 'PO', 4),
(5, 'RefDev', 5),
(6, 'SM', 6),
(7, 'RefUi', 7),
(8, 'R_Anim', 8),
(9, 'R_Mode', 9),
(10, 'SM', 10),
(11, 'PO', 11),
(12, 'RefDev', 12);

-- --------------------------------------------------------

--
-- Structure de la table `sprintbacklog`
--

CREATE TABLE `sprintbacklog` (
  `IdT` int(11) NOT NULL,
  `IdS` smallint(6) NOT NULL,
  `IdU` smallint(6) NOT NULL,
  `IdEtat` smallint(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `sprintbacklog`
--

INSERT INTO `sprintbacklog` (`IdT`, `IdS`, `IdU`, `IdEtat`) VALUES
(1, 1, 1, 1),
(2, 1, 2, 2),
(3, 2, 3, 3),
(4, 2, 4, 4),
(5, 3, 5, 1),
(6, 3, 6, 2),
(7, 4, 7, 3),
(8, 4, 8, 4),
(9, 5, 9, 1),
(10, 5, 10, 2),
(11, 6, 11, 3),
(12, 6, 12, 4);

-- --------------------------------------------------------

--
-- Structure de la table `sprints`
--

CREATE TABLE `sprints` (
  `IdS` smallint(6) NOT NULL,
  `DateDebS` date NOT NULL,
  `DateFinS` date NOT NULL,
  `RetrospectiveS` varchar(300) DEFAULT NULL,
  `RevueS` varchar(300) DEFAULT NULL,
  `IdEq` smallint(6) NOT NULL,
  `VelociteEq` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `sprints`
--

INSERT INTO `sprints` (`IdS`, `DateDebS`, `DateFinS`, `RetrospectiveS`, `RevueS`, `IdEq`, `VelociteEq`) VALUES
(1, '2024-09-01', '2024-09-15', 'Amélioration de la communication', 'Version stable livrée', 1, 50),
(2, '2024-09-16', '2024-09-30', 'Bons résultats, mais besoin de focus', 'Quelques bugs corrigés', 2, 40),
(3, '2024-10-01', '2024-10-15', 'Plus de tests unitaires nécessaires', 'Version alpha livrée', 3, 60),
(4, '2024-10-16', '2024-10-31', 'Collaboration plus fluide entre équipes', 'Version beta livrée', 4, 70),
(5, '2024-11-01', '2024-11-15', 'Amélioration de la documentation', 'Version RC livrée', 5, 80),
(6, '2024-11-16', '2024-11-30', 'Focus sur la performance', 'Version stable livrée', 6, 90),
(7, '2024-12-01', '2024-12-15', 'Réduction du nombre de bugs', 'Version finale livrée', 7, 100),
(8, '2024-12-16', '2024-12-31', 'Satisfaction des utilisateurs améliorée', 'Patch final livré', 8, 110);

-- --------------------------------------------------------

--
-- Structure de la table `taches`
--

CREATE TABLE `taches` (
  `IdT` int(11) NOT NULL,
  `TitreT` varchar(50) NOT NULL,
  `UserStoryT` varchar(300) NOT NULL,
  `IdEq` smallint(6) NOT NULL,
  `CoutT` enum('?','1','3','5','10','15','25','999') NOT NULL DEFAULT '?',
  `IdPriorite` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `taches`
--

INSERT INTO `taches` (`IdT`, `TitreT`, `UserStoryT`, `IdEq`, `CoutT`, `IdPriorite`) VALUES
(1, 'Créer la base de données', 'En tant que développeur, je veux une base de données stable', 1, '5', 1),
(2, 'Concevoir l\'interface utilisateur', 'En tant qu\'utilisateur, je veux une interface intuitive', 2, '10', 2),
(3, 'Tester l\'application', 'En tant que QA, je veux garantir la qualité du produit', 3, '3', 3),
(4, 'Documenter le code', 'En tant que développeur, je veux une documentation claire', 4, '1', 4),
(5, 'Optimiser la requête SQL', 'En tant que DBA, je veux améliorer les performances des requêtes', 5, '5', 1),
(6, 'Implémenter l\'authentification', 'En tant qu\'utilisateur, je veux me connecter en toute sécurité', 6, '10', 2),
(7, 'Créer les tests unitaires', 'En tant que QA, je veux automatiser les tests', 7, '3', 3),
(8, 'Déployer l\'application', 'En tant que développeur, je veux que l\'application soit en production', 8, '999', 4),
(9, 'Mettre en place le système de notifications', 'En tant qu\'utilisateur, je veux recevoir des alertes en temps réel', 9, '15', 1),
(10, 'Refactoriser le code legacy', 'En tant que développeur, je veux simplifier l\'ancien code', 10, '25', 2),
(11, 'Configurer le serveur de production', 'En tant que SysAdmin, je veux préparer l\'environnement de production', 11, '5', 3),
(12, 'Rédiger la documentation utilisateur', 'En tant que rédacteur, je veux que l\'utilisateur ait des guides clairs', 12, '3', 4);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `IdU` smallint(6) NOT NULL,
  `NomU` varchar(50) NOT NULL,
  `PrenomU` varchar(50) NOT NULL,
  `MotDePasseU` varchar(15) NOT NULL,
  `SpecialiteU` enum('Développeur','Modeleur','Animateur','UI','IA','WebComm','Polyvalent') NOT NULL DEFAULT 'Polyvalent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`IdU`, `NomU`, `PrenomU`, `MotDePasseU`, `SpecialiteU`) VALUES
(1, 'Dupont', 'Jean', 'password123', 'Développeur'),
(2, 'Martin', 'Marie', 'pass456', 'UI'),
(3, 'Durand', 'Pierre', 'secure789', 'Polyvalent'),
(4, 'Lemoine', 'Sophie', 'testuser', 'IA'),
(5, 'Petit', 'Clara', 'mypassword', 'WebComm'),
(6, 'Roche', 'Nicolas', 'devpass', 'Développeur'),
(7, 'Moreau', 'Isabelle', 'secure321', 'Modeleur'),
(8, 'Fabre', 'Thomas', 'designUI', 'UI'),
(9, 'Bernard', 'Laura', 'polyvalent22', 'Polyvalent'),
(10, 'Girard', 'Luc', 'scripted', 'Animateur'),
(11, 'Mercier', 'Alexandre', 'animation123', 'Animateur'),
(12, 'Blanc', 'Julie', 'project567', 'Polyvalent');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `equipesprj`
--
ALTER TABLE `equipesprj`
  ADD PRIMARY KEY (`IdEq`);

--
-- Index pour la table `etatstaches`
--
ALTER TABLE `etatstaches`
  ADD PRIMARY KEY (`IdEtat`);

--
-- Index pour la table `idees_bac_a_sable`
--
ALTER TABLE `idees_bac_a_sable`
  ADD PRIMARY KEY (`Id_Idee_bas`),
  ADD KEY `IdU` (`IdU`),
  ADD KEY `IdEq` (`IdEq`);

--
-- Index pour la table `prioritestaches`
--
ALTER TABLE `prioritestaches`
  ADD PRIMARY KEY (`idPriorite`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`IdR`);

--
-- Index pour la table `rolesutilisateurprojet`
--
ALTER TABLE `rolesutilisateurprojet`
  ADD PRIMARY KEY (`IdR`,`IdEq`),
  ADD KEY `IdR` (`IdR`),
  ADD KEY `IdEq` (`IdEq`),
  ADD KEY `FK_RoleUtil_Utilisateurs` (`IdU`);

--
-- Index pour la table `sprintbacklog`
--
ALTER TABLE `sprintbacklog`
  ADD PRIMARY KEY (`IdT`),
  ADD KEY `IdS` (`IdS`),
  ADD KEY `IdU` (`IdU`),
  ADD KEY `IdEtat` (`IdEtat`);

--
-- Index pour la table `sprints`
--
ALTER TABLE `sprints`
  ADD PRIMARY KEY (`IdS`),
  ADD KEY `IdEq` (`IdEq`);

--
-- Index pour la table `taches`
--
ALTER TABLE `taches`
  ADD PRIMARY KEY (`IdT`),
  ADD KEY `IdPriorite` (`IdPriorite`),
  ADD KEY `IndexIdEq` (`IdEq`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`IdU`);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `idees_bac_a_sable`
--
ALTER TABLE `idees_bac_a_sable`
  ADD CONSTRAINT `FK_IdeeBAS_Equipes` FOREIGN KEY (`IdEq`) REFERENCES `equipesprj` (`IdEq`),
  ADD CONSTRAINT `FK_IdeesBAS_Utilisateurs` FOREIGN KEY (`IdU`) REFERENCES `utilisateurs` (`IdU`);

--
-- Contraintes pour la table `rolesutilisateurprojet`
--
ALTER TABLE `rolesutilisateurprojet`
  ADD CONSTRAINT `FK_RoleUtil_Equipes` FOREIGN KEY (`IdEq`) REFERENCES `equipesprj` (`IdEq`),
  ADD CONSTRAINT `FK_RoleUtil_Roles` FOREIGN KEY (`IdR`) REFERENCES `roles` (`IdR`),
  ADD CONSTRAINT `FK_RoleUtil_Utilisateurs` FOREIGN KEY (`IdU`) REFERENCES `utilisateurs` (`IdU`);

--
-- Contraintes pour la table `sprintbacklog`
--
ALTER TABLE `sprintbacklog`
  ADD CONSTRAINT `FK_SB_EtatTaches` FOREIGN KEY (`IdEtat`) REFERENCES `etatstaches` (`IdEtat`),
  ADD CONSTRAINT `FK_SB_Sprints` FOREIGN KEY (`IdS`) REFERENCES `sprints` (`IdS`),
  ADD CONSTRAINT `FK_SB_Taches` FOREIGN KEY (`IdT`) REFERENCES `taches` (`IdT`),
  ADD CONSTRAINT `FK_SB_Utilisateurs` FOREIGN KEY (`IdU`) REFERENCES `utilisateurs` (`IdU`);

--
-- Contraintes pour la table `sprints`
--
ALTER TABLE `sprints`
  ADD CONSTRAINT `FK_Sprints_Equipes` FOREIGN KEY (`IdEq`) REFERENCES `equipesprj` (`IdEq`);

--
-- Contraintes pour la table `taches`
--
ALTER TABLE `taches`
  ADD CONSTRAINT `FK_TachesEquipes` FOREIGN KEY (`IdEq`) REFERENCES `equipesprj` (`IdEq`),
  ADD CONSTRAINT `FK_Taches_Priorite` FOREIGN KEY (`IdPriorite`) REFERENCES `prioritestaches` (`idPriorite`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
