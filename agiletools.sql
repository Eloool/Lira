-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 09 oct. 2024 à 13:13
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

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

DELIMITER $$
--
-- Procédures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `Change_State_User` (IN `Id_User` INT)   BEGIN
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


END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Velo_Total_Sprint` (IN `Id_Sprint` INT)   BEGIN
SELECT SUM(IdCout)
FROM sprintbacklog
INNER JOIN taches ON sprintbacklog.IdT=taches.IdT
WHERE IdS=Id_Sprint AND IdCout!='?' AND IdCout!='999';
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `coutstaches`
--

CREATE TABLE `coutstaches` (
  `IdCout` smallint(4) NOT NULL,
  `ValCout` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `coutstaches`
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
-- Structure de la table `equipesprj`
--

CREATE TABLE `equipesprj` (
  `IdEq` smallint(11) NOT NULL,
  `NomEq` varchar(100) NOT NULL,
  `descProj` varchar(55) DEFAULT NULL,
  `PP` tinyint(1) DEFAULT 0,
  `votingTask` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `equipesprj`
--

INSERT INTO `equipesprj` (`IdEq`, `NomEq`, `descProj`, `PP`, `votingTask`) VALUES
(1, 'Equipe Alpha', NULL, 0, 0),
(2, 'Equipe Bravo', NULL, 0, 0);

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
(1, 'Améliorer l\'interface utilisateur pour les tableaux de bord.', 4, 1),
(2, 'Ajouter une fonctionnalité d\'export des rapports en PDF.', 2, 1),
(3, 'Optimiser le code backend pour réduire la latence.', 2, 2),
(4, 'Créer un prototype de nouvelle animation pour le projet.', 7, 2),
(5, 'Intégrer un modèle IA pour l\'analyse des données utilisateurs.', 6, 1),
(6, 'Développer un module de test automatisé pour les nouvelles fonctionnalités.', 5, 1);

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
  `IdEq` smallint(6) NOT NULL,
  `inPP` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `rolesutilisateurprojet`
--

INSERT INTO `rolesutilisateurprojet` (`IdU`, `IdR`, `IdEq`, `inPP`) VALUES
(2, 'RefDev', 1, 0),
(3, 'R_Mode', 1, 0),
(3, 'R_Mode', 2, 0),
(4, 'RefUi', 1, 0),
(5, 'SM', 1, 0),
(6, 'PO', 2, 0),
(7, 'R_Anim', 2, 0),
(8, 'SM', 2, 0);

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
(1, 1, 2, 5),
(2, 2, 3, 5),
(3, 3, 2, 3),
(4, 4, 3, 2),
(5, 5, 6, 2),
(6, 6, 7, 1);

--
-- Déclencheurs `sprintbacklog`
--
DELIMITER $$
CREATE TRIGGER `taches` AFTER INSERT ON `sprintbacklog` FOR EACH ROW BEGIN
    UPDATE sprints
    SET VelociteEq = Velo_Total_Sprint(NEW.IdS)
    WHERE IdS = NEW.IdS;
END
$$
DELIMITER ;

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
(1, '2024-01-01', '2024-01-14', 'Rétrospective Sprint 1 : analyse des performances', 'Revue Sprint 1 : démo des fonctionnalités', 1, 25),
(2, '2024-02-01', '2024-02-14', 'Rétrospective Sprint 2 : ajustements nécessaires', 'Revue Sprint 2 : présentation des améliorations', 2, 30),
(3, '2024-03-01', '2024-03-14', 'Rétrospective Sprint 3 : le feedback des utilisateurs', 'Revue Sprint 3 : nouvelles fonctionnalités en avant-première', 1, 20),
(4, '2024-04-01', '2024-04-14', 'Rétrospective Sprint 4 : évaluation des objectifs', 'Revue Sprint 4 : préparation du lancement', 1, 15),
(5, '2024-05-01', '2024-05-14', 'Rétrospective Sprint 5 : analyse des résultats', 'Revue Sprint 5 : priorisation des prochaines tâches', 2, 18),
(6, '2024-06-01', '2024-06-14', 'Rétrospective Sprint 6 : bilan des avancées', 'Revue Sprint 6 : discussion des retours utilisateurs', 2, 12);

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
  `IdT` int(11) NOT NULL,
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

INSERT INTO `taches` (`IdT`, `TitreT`, `UserStoryT`, `IdEq`, `IdCout`, `IdPriorite`, `VotePP`) VALUES
(1, 'Développement de la fonctionnalité d\'inscription', 'En tant qu\'utilisateur, je veux m\'inscrire pour utiliser l\'application', 1, 3, 1, 0),
(2, 'Intégration du paiement en ligne', 'En tant qu\'utilisateur, je souhaite pouvoir payer en ligne pour mes achats', 2, 4, 2, 0),
(3, 'Création de la page d\'accueil', 'En tant qu\'administrateur, je veux une page d\'accueil attractive', 1, 5, 1, 0),
(4, 'Tests de la fonctionnalité de recherche', 'En tant qu\'utilisateur, je veux rechercher des articles dans l\'application', 1, 6, 2, 0),
(5, 'Amélioration de la sécurité des données', 'En tant qu\'administrateur, je veux garantir la sécurité des données utilisateur', 2, 3, 3, 0),
(6, 'Optimisation des performances du site', 'En tant qu\'utilisateur, je veux que le site se charge rapidement', 2, 4, 4, 0);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `IdU` smallint(6) NOT NULL,
  `NomU` varchar(50) NOT NULL,
  `PrenomU` varchar(50) NOT NULL,
  `MotDePasseU` varchar(255) NOT NULL,
  `SpecialiteU` enum('Développeur','Modeleur','Animateur','UI','IA','WebComm','Polyvalent') NOT NULL DEFAULT 'Polyvalent',
  `is_admin` tinyint(1) DEFAULT 0,
  `is_connected` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`IdU`, `NomU`, `PrenomU`, `MotDePasseU`, `SpecialiteU`, `is_admin`, `is_connected`) VALUES
(1, 'admin', 'admin', '$2b$12$rXsIUK8GzjYNl7aSxYrz8.eyAINBNjXz2mHNyVWsWzEYAbcBh6Z0q', 'Polyvalent', 1, 0),
(2, 'Dupont', 'Jean', '$2b$12$G3zl2VAaorY0/VyxGD73ie.13HQa.DQrUTSgcz9rzP4EacyWuLyvO', 'Développeur', 0, 0),
(3, 'Martin', 'Paul', '$2b$12$G3zl2VAaorY0/VyxGD73ie.13HQa.DQrUTSgcz9rzP4EacyWuLyvO', 'Modeleur', 0, 0),
(4, 'Durand', 'Marie', '$2b$12$G3zl2VAaorY0/VyxGD73ie.13HQa.DQrUTSgcz9rzP4EacyWuLyvO', 'UI', 0, 0),
(5, 'Petit', 'Luc', '$2b$12$G3zl2VAaorY0/VyxGD73ie.13HQa.DQrUTSgcz9rzP4EacyWuLyvO', 'Développeur', 0, 0),
(6, 'Moreau', 'Chloé', '$2b$12$G3zl2VAaorY0/VyxGD73ie.13HQa.DQrUTSgcz9rzP4EacyWuLyvO', 'Polyvalent', 0, 0),
(7, 'Bernard', 'Sophie', '$2b$12$G3zl2VAaorY0/VyxGD73ie.13HQa.DQrUTSgcz9rzP4EacyWuLyvO', 'Animateur', 0, 0),
(8, 'Loiseau', 'Olivier', '$2b$12$G3zl2VAaorY0/VyxGD73ie.13HQa.DQrUTSgcz9rzP4EacyWuLyvO', 'Développeur', 0, 0);

--
-- Déclencheurs `utilisateurs`
--
DELIMITER $$
CREATE TRIGGER `check_admin_insert` BEFORE INSERT ON `utilisateurs` FOR EACH ROW BEGIN
    DECLARE nb_admin INT;

    SET nb_admin = (
        SELECT COUNT(*)
        FROM utilisateurs
        WHERE utilisateurs.is_admin = TRUE
    );

    IF new.is_admin=True AND nb_admin > 0 THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Il ne peut y avoir qu''un seul administrateur.';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `voterpp`
--

CREATE TABLE `voterpp` (
  `IdU` smallint(6) NOT NULL,
  `IdT` int(11) NOT NULL,
  `IdCout` smallint(6) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `coutstaches`
--
ALTER TABLE `coutstaches`
  ADD PRIMARY KEY (`IdCout`);

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
  ADD KEY `IndexIdEq` (`IdEq`),
  ADD KEY `IndexCout` (`IdCout`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`IdU`);

--
-- Index pour la table `voterpp`
--
ALTER TABLE `voterpp`
  ADD PRIMARY KEY (`IdU`,`IdT`),
  ADD KEY `IdU` (`IdU`),
  ADD KEY `IdT` (`IdT`),
  ADD KEY `IndexCout` (`IdCout`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `equipesprj`
--
ALTER TABLE `equipesprj`
  MODIFY `IdEq` smallint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `idees_bac_a_sable`
--
ALTER TABLE `idees_bac_a_sable`
  MODIFY `Id_Idee_bas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `sprintbacklog`
--
ALTER TABLE `sprintbacklog`
  MODIFY `IdT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `sprints`
--
ALTER TABLE `sprints`
  MODIFY `IdS` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `taches`
--
ALTER TABLE `taches`
  MODIFY `IdT` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `IdU` smallint(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  ADD CONSTRAINT `FK_SB_Taches` FOREIGN KEY (`IdT`) REFERENCES `taches` (`IdT`) ON DELETE CASCADE,
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
  ADD CONSTRAINT `FK_Tache_Cout` FOREIGN KEY (`IdCout`) REFERENCES `coutstaches` (`IdCout`),
  ADD CONSTRAINT `FK_TachesEquipes` FOREIGN KEY (`IdEq`) REFERENCES `equipesprj` (`IdEq`),
  ADD CONSTRAINT `FK_Taches_Priorite` FOREIGN KEY (`IdPriorite`) REFERENCES `prioritestaches` (`idPriorite`);

--
-- Contraintes pour la table `voterpp`
--
ALTER TABLE `voterpp`
  ADD CONSTRAINT `FK_VoterPP_Cout` FOREIGN KEY (`IdCout`) REFERENCES `coutstaches` (`IdCout`),
  ADD CONSTRAINT `FK_VoterPP_Taches` FOREIGN KEY (`IdT`) REFERENCES `taches` (`IdT`),
  ADD CONSTRAINT `FK_VoterPP_Utilisateurs` FOREIGN KEY (`IdU`) REFERENCES `utilisateurs` (`IdU`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
