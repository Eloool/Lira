-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 26 sep. 2024 à 16:53
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
-- Base de données : `lira`
--

-- --------------------------------------------------------

--
-- Structure de la table `equipesprj`
--

CREATE TABLE `equipesprj` (
  `IdEq` smallint(11) PRIMARY KEY NOT NULL  AUTO_INCREMENT,
  `NomEq` varchar(100) NOT NULL,
  `descProj` VARCHAR(55),
  `PP` tinyint(1) DEFAULT 0,
  `votingTask` tinyint(1) DEFAULT 0
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
-- Structure de la table `coutstaches`
--

CREATE TABLE `coutstaches` (
  `IdCout` smallint(4) NOT NULL,
  `ValCout` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `etatstaches`
--

INSERT INTO `coutstaches` (`IdCout`, `ValCout`) VALUES
(1, '?'),
(2, '1'),
(3, '3'),
(4, '5'),
(5, '10'),
(6, '15'),
(7, '25'),
(8, '999');

-- --------------------------------------------------------

--
-- Structure de la table `idees_bac_a_sable`
--

CREATE TABLE `idees_bac_a_sable` (
  `Id_Idee_bas` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
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
  `IdU` smallint(6) NOT NULL ,
  `IdR` varchar(6) NOT NULL,
  `IdEq` smallint(6) NOT NULL,
  `inPP` tinyint(1) DEFAULT 0
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
(9, 'SM', 8),
(10, 'R_Mode', 10),
(11, 'PO', 11),
(12, 'RefDev', 12);

-- --------------------------------------------------------

--
-- Structure de la table `sprintbacklog`
--

CREATE TABLE `sprintbacklog` (
  `IdT` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
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

--
-- Déclencheurs `sprintbacklog`
--

-- --------------------------------------------------------

--
-- Structure de la table `sprints`
--

CREATE TABLE `sprints` (
  `IdS` smallint(6) PRIMARY KEY NOT NULL AUTO_INCREMENT,
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

--
-- Déclencheurs `sprints`
--
DELIMITER $$
CREATE TRIGGER `check_date` BEFORE INSERT ON `sprints` FOR EACH ROW BEGIN
    SET @Date_From=new.DateDebS;
    SET @Date_To=new.DateFinS;

    IF (@Date_From>@Date_To) THEN
    SET new.DateFinS=@Date_From;
    SET new.DateDebS=@Date_To;

END IF ;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `taches`
--

CREATE TABLE `taches` (
  `IdT` int(11) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `TitreT` varchar(50) NOT NULL,
  `UserStoryT` varchar(300) NOT NULL,
  `IdEq` smallint(6) NOT NULL,
  `IdCout` smallint(6) NOT NULL DEFAULT 1,
  `IdPriorite` tinyint(1) NOT NULL,
  `VotePP` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `taches`
--

INSERT INTO `taches` (`IdT`, `TitreT`, `UserStoryT`, `IdEq`, `IdCout`, `IdPriorite`) VALUES
(1, 'Créer la base de données', 'En tant que développeur, je veux une base de données stable', 1, 4, 1),
(2, 'Concevoir l\'interface utilisateur', 'En tant qu\'utilisateur, je veux une interface intuitive', 2, 5, 2),
(3, 'Tester l\'application', 'En tant que QA, je veux garantir la qualité du produit', 3, 3, 3),
(4, 'Documenter le code', 'En tant que développeur, je veux une documentation claire', 4, 2, 4),
(5, 'Optimiser la requête SQL', 'En tant que DBA, je veux améliorer les performances des requêtes', 5,4, 1),
(6, 'Implémenter l\'authentification', 'En tant qu\'utilisateur, je veux me connecter en toute sécurité', 6, 6, 2),
(7, 'Créer les tests unitaires', 'En tant que QA, je veux automatiser les tests', 7, 3, 3),
(8, 'Déployer l\'application', 'En tant que développeur, je veux que l\'application soit en production', 8, 8, 4),
(9, 'Mettre en place le système de notifications', 'En tant qu\'utilisateur, je veux recevoir des alertes en temps réel', 8, 6, 1),
(10, 'Refactoriser le code legacy', 'En tant que développeur, je veux simplifier l\'ancien code', 10, 7, 2),
(11, 'Configurer le serveur de production', 'En tant que SysAdmin, je veux préparer l\'environnement de production', 11, 4, 3),
(12, 'Rédiger la documentation utilisateur', 'En tant que rédacteur, je veux que l\'utilisateur ait des guides clairs', 12, 3, 4);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `IdU` smallint(6) PRIMARY KEY NOT NULL AUTO_INCREMENT,
  `NomU` varchar(50) NOT NULL,
  `PrenomU` varchar(50) NOT NULL,
  `MotDePasseU` varchar(255) NOT NULL,
  `SpecialiteU` enum('Développeur','Modeleur','Animateur','UI','IA','WebComm','Polyvalent') NOT NULL DEFAULT 'Polyvalent',
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`IdU`, `NomU`, `PrenomU`, `MotDePasseU`, `SpecialiteU`, `is_admin`) VALUES
(1, 'Dupont', 'Jean', '$2y$10$odAVbifDyOofCjnD4AahMOmKvggSaRRmPP0VLPsJpROKi.pWzhxje', 'Développeur', 0),
(2, 'Martin', 'Marie', '$2y$10$.YUW4DfqJPB/RJC0C5nkOOYJmBHX2OtX7d6lYaH1jvs6OcTik9uSG', 'UI', 0),
(3, 'Durand', 'Pierre', '$2y$10$3R4PDx2ukqdw0QZbvdnAhOa7xKyoToRD355ob0zrQLRhDnrlHooOy', 'Polyvalent', 0),
(4, 'Lemoine', 'Sophie', '$2y$10$Upq.LJVS0dOrKhF28fe3qutOUQTatYJgmmVxdIzd1/C5G6WHnkfyW', 'IA', 0),
(5, 'Petit', 'Clara', '$2y$10$JDI1pA50q5KFvQheE4UNOuR6HN/DP7vOItftvi3ix1AwssCvsvloi', 'WebComm', 0),
(6, 'Roche', 'Nicolas', '$2y$10$Eb3GC9XBcWxPxgscIaeuxO2iXc0av495R85METqlhjay7U8hPdM.2', 'Développeur', 0),
(7, 'Moreau', 'Isabelle', '$2y$10$gmDEOGjRGHMu8102x.3nQOkc7PnZAf2rMOoW/diL5wd9K4.oAVQ8W', 'Modeleur', 0),
(8, 'Fabre', 'Thomas', '$2y$10$k487SKVTg6DP2bxzoeiKuuuIM2Sq1Si8RM2tOdahiX8DemxwuToyO', 'UI', 0),
(9, 'Bernard', 'Laura', '$2y$10$YNm1yzUSdWj5wJpibH7cw.vE1CpYEywe.OG1lnjPIafcAuhiCHGIe', 'Polyvalent', 0),
(10, 'Girard', 'Luc', '$2y$10$3MpoCCv03o1iHhsKjmTwv.6rDV.ET2dqc9YJIugEmXhOvRQ91WXV2', 'Animateur', 0),
(11, 'Mercier', 'Alexandre', '$2y$10$sTlIAf1o7/n/IWMtUgmKvuDq0ezHCmio1c1UOltaNrukc9fe7rnnG', 'Animateur', 0),
(12, 'Blanc', 'Julie', '$2y$10$VJ7VtJbacS5ayc6BQFf9j.UEJWL2bkZO6IGmeH6B.Shfzy5J37Ll.', 'Polyvalent', 0),
(13, 'JDO', 'JDO', '$2y$10$PzO/zdodF3DP/7KLR1vHUu37OcJjRgQRefXy.f9dKom3beTHDsV.6', 'Animateur', 1),
(14, 'oui', 'oui', '$2y$10$VrnlmMX76zTjD0y.GUPA/O/idnOUlNqNbnlNkqlx/3vBi0Ksda0Zq', 'Développeur', 0);

-- --------------------------------------------------------

--
-- Structure de la table `VoterPP`
--

CREATE TABLE `VoterPP` (
  `IdU` smallint(6) NOT NULL,
  `IdT` int(11) NOT NULL,
  `IdCout` smallint(6) NOT NULL DEFAULT 1,
  PRIMARY KEY (`IdU`, `IdT`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- --------------------------------------------------------


--
-- Index pour les tables déchargées
--

--
-- Index pour la table `equipesprj`
--

--
-- Index pour la table `etatstaches`
--
ALTER TABLE `etatstaches`
  ADD PRIMARY KEY (`IdEtat`);

--
-- Index pour la table `idees_bac_a_sable`
--
ALTER TABLE `idees_bac_a_sable`
  
  ADD KEY `IdU` (`IdU`),
  ADD KEY `IdEq` (`IdEq`);

--
-- Index pour la table `prioritestaches`
--
ALTER TABLE `prioritestaches`
  ADD PRIMARY KEY (`idPriorite`);

--
-- Index pour la table `coutstaches`
--
ALTER TABLE `coutstaches`
  ADD PRIMARY KEY (`IdCout`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`IdR`);

--
-- Index pour la table `rolesutilisateurprojet`
--
ALTER TABLE `rolesutilisateurprojet`
  ADD KEY `IdR` (`IdR`),
  ADD KEY `IdEq` (`IdEq`),
  ADD KEY `FK_RoleUtil_Utilisateurs` (`IdU`);

--
-- Index pour la table `sprintbacklog`
--
ALTER TABLE `sprintbacklog`
  ADD KEY `IdS` (`IdS`),
  ADD KEY `IdU` (`IdU`),
  ADD KEY `IdEtat` (`IdEtat`);

--
-- Index pour la table `sprints`
--
ALTER TABLE `sprints`
  ADD KEY `IdEq` (`IdEq`);

--
-- Index pour la table `taches`
--
ALTER TABLE `taches`
  ADD KEY `IdPriorite` (`IdPriorite`),
  ADD KEY `IndexIdEq` (`IdEq`),
  ADD KEY `IndexCout` (`IdCout`);

--
-- Index pour la table `VoterPP`
--
ALTER TABLE `VoterPP`
  ADD KEY `IdU` (`IdU`),
  ADD KEY `IdT` (`IdT`),
  ADD KEY `IndexCout` (`IdCout`);

--
-- Index pour la table `VoterPP`
--
ALTER TABLE `VoterPP`
  ADD KEY `IdU` (`IdU`),
  ADD KEY `IdT` (`IdT`),
  ADD KEY `IndexCout` (`IdCout`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`IdU`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--

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
  ADD CONSTRAINT `FK_Taches_Priorite` FOREIGN KEY (`IdPriorite`) REFERENCES `prioritestaches` (`idPriorite`),
  ADD CONSTRAINT `FK_Tache_Cout` FOREIGN KEY (`IdCout`) REFERENCES `coutstaches` (`IdCout`);

--
-- Contraintes pour la table `VoterPP`
--
ALTER TABLE `VoterPP`
  ADD CONSTRAINT `FK_VoterPP_Utilisateurs` FOREIGN KEY (`IdU`) REFERENCES `utilisateurs` (`IdU`),
  ADD CONSTRAINT `FK_VoterPP_Taches` FOREIGN KEY (`IdT`) REFERENCES `taches` (`idT`),
  ADD CONSTRAINT `FK_VoterPP_Cout` FOREIGN KEY (`IdCout`) REFERENCES `coutstaches` (`IdCout`);
COMMIT;


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


--
-- Triggers
--

DROP trigger IF EXISTS check_date
DELIMITER $$
CREATE OR REPLACE TRIGGER check_date
BEFORE INSERT  ON sprints
FOR EACH ROW
    BEGIN
    SET @Date_From=new.DateDebS;
    SET @Date_To=new.DateFinS;

    IF (@Date_From>@Date_To) THEN
    SET new.DateFinS=@Date_From;
    SET new.DateDebS=@Date_To;

END IF ;
END$$
DELIMITER ;



DROP TRIGGER IF EXISTS check_admin_insert;
DELIMITER $$

CREATE TRIGGER check_admin_insert
BEFORE INSERT ON utilisateurs
FOR EACH ROW
BEGIN
    DECLARE nb_admin INT;

    SET nb_admin = (
        SELECT COUNT(*)
        FROM utilisateurs
        WHERE utilisateurs.is_admin = TRUE
    );

    IF new.is_admin=True AND nb_admin > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Il ne peut y avoir qu\'un seul administrateur.';
    END IF;
END$$

DELIMITER ;
DROP TRIGGER IF EXISTS check_admin_update;
DELIMITER $$

CREATE TRIGGER check_admin_update
BEFORE UPDATE ON utilisateurs
FOR EACH ROW
BEGIN
    DECLARE nb_admin INT;

    SET nb_admin = (
        SELECT COUNT(*)
        FROM utilisateurs
        WHERE utilisateurs.is_admin = TRUE
    );

    IF new.is_admin=True AND nb_admin > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Il ne peut y avoir qu\'un seul administrateur.';
    END IF;
END$$

DELIMITER ;



-- procedure qui change l'etat de connection de l'utilisateur en parametre

DROP PROCEDURE IF EXISTS  Change_State_User  ;

DELIMITER //
CREATE PROCEDURE Change_State_User
(IN Id_User INT) 
BEGIN
    DECLARE connect INT;
    DECLARE new_state INT;
    SET connect = (SELECT is_connected
		   FROM utilisateurs
		   WHERE IdU=Id_User);
IF (connect=0) THEN
    SET new_state=1;
ELSE 
    SET new_state=0;
END IF ;

UPDATE utilisateurs
    SET is_connected=new_state
    WHERE IdU = Id_User;


END //
DELIMITER ;







-- procedure pour avoir la somme des cout des taches d'un sprint
DROP PROCEDURE IF EXISTS  Velo_Total_Sprint  ;

DELIMITER //
CREATE PROCEDURE Velo_Total_Sprint
(IN Id_Sprint INT) 
BEGIN
SELECT SUM(CoutT)
FROM sprintbacklog
INNER JOIN taches ON sprintbacklog.IdT=taches.IdT
WHERE IdS=Id_Sprint AND CoutT!='?' AND CoutT!='999';
END //
DELIMITER ;



-- trigger qui lance la procedure lorsqu'on modifie ou ajoute une ligne dans sprintbacklog
DROP trigger IF EXISTS update_velocite_sprint;

DELIMITER $$
CREATE TRIGGER taches
AFTER INSERT ON sprintbacklog
FOR EACH ROW
BEGIN
    UPDATE sprints
    SET VelociteEq = Velo_Total_Sprint(NEW.IdS)
    WHERE IdS = NEW.IdS;
END$$
DELIMITER ;
