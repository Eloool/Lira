<?php
session_start();

// Inclure le fichier de connexion à la base de données
include 'include/db_connect.php';

$ID_user = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!$ID_user) {
    // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
    header("Location: login.php");
    exit();
}

$project_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Vérifiez si l'ID du projet est valide
if ($project_id <= 0) {
    echo "ID de projet non spécifié.";
    exit();
}
function get_roles_for_user_for_project($conn,$user_id,$project_id){
    $sql = "SELECT IdR
            FROM rolesutilisateurprojet
            WHERE IdU = ? AND IdEq = ?";

    // Préparation de la requête
    if ($stmt = $conn->prepare($sql)) {
        // Liaison du paramètre (le ? correspond à $user_id)
        $stmt->bind_param('ii', $user_id, $project_id);

        // Exécution de la requête
        $stmt->execute();

        // Récupération des résultats
        $result = $stmt->get_result();

        // Récupération des enregistrements sous forme de tableau associatif
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        // Gestion de l'erreur si la requête échoue
        die("Erreur dans la requête : " . $conn->error);
    }
}

function is_planning_poker($conn,$project_id){
    $sql = "SELECT PP
            FROM equipesprj
            WHERE IdEq = ?";

    // Préparation de la requête
    if ($stmt = $conn->prepare($sql)) {
        // Liaison du paramètre (le ? correspond à $user_id)
        $stmt->bind_param('i', $project_id);

        // Exécution de la requête
        $stmt->execute();

        // Récupération des résultats
        $result = $stmt->get_result();

        // Récupération des enregistrements sous forme de tableau associatif
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        // Gestion de l'erreur si la requête échoue
        die("Erreur dans la requête : " . $conn->error);
    }
}

function get_tasks_list($conn, $project_id){
    $sql = "SELECT *
            FROM taches
            WHERE IdEq = ? AND (IdCout = 1 OR IdCout = 8)";

    // Préparation de la requête
    if ($stmt = $conn->prepare($sql)) {
        // Liaison du paramètre (le ? correspond à $user_id)
        $stmt->bind_param('i', $project_id);

        // Exécution de la requête
        $stmt->execute();

        // Récupération des résultats
        $result = $stmt->get_result();

        // Récupération des enregistrements sous forme de tableau associatif
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        // Gestion de l'erreur si la requête échoue
        die("Erreur dans la requête : " . $conn->error);
    }
}

function set_task_to_eval($conn, $task_id, $eval_value){
    $sql = "UPDATE taches
        SET taches.VotePP = ?
        WHERE taches.IdT = ?";

    // Préparation de la requête
    if ($stmt = $conn->prepare($sql)) {
        // Liaison du paramètre (le ? correspond à $user_id)
        $stmt->bind_param('ii', $eval_value, $task_id);

        // Exécution de la requête
        $stmt->execute();
    } else {
        // Gestion de l'erreur si la requête échoue
        die("Erreur dans la requête : " . $conn->error);
    }
}

function get_pp_participants($conn, $project_id){
    $sql = "SELECT utilisateurs.*
            FROM utilisateurs
            JOIN rolesutilisateurprojet ON rolesutilisateurprojet.IdU = utilisateurs.IdU
            WHERE rolesutilisateurprojet.IdEq = ? AND rolesutilisateurprojet.inPP = 1";

    // Préparation de la requête
    if ($stmt = $conn->prepare($sql)) {
        // Liaison du paramètre (le ? correspond à $user_id)
        $stmt->bind_param('i', $project_id);

        // Exécution de la requête
        $stmt->execute();

        // Récupération des résultats
        $result = $stmt->get_result();

        // Récupération des enregistrements sous forme de tableau associatif
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        // Gestion de l'erreur si la requête échoue
        die("Erreur dans la requête : " . $conn->error);
    }
}

function is_actually_voting($conn,$project_id){
    $sql = "SELECT votingTask
            FROM equipesprj
            WHERE IdEq = ?";

    // Préparation de la requête
    if ($stmt = $conn->prepare($sql)) {
        // Liaison du paramètre (le ? correspond à $user_id)
        $stmt->bind_param('i', $project_id);

        // Exécution de la requête
        $stmt->execute();

        // Récupération des résultats
        $result = $stmt->get_result();

        // Récupération des enregistrements sous forme de tableau associatif
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        // Gestion de l'erreur si la requête échoue
        die("Erreur dans la requête : " . $conn->error);
    }
}

function set_pp_participant($conn, $project_id, $user_id, $value){
    $sql = "UPDATE rolesutilisateurprojet
        SET inPP = ?
        WHERE IdU = ? && IdEq = ?";

    // Préparation de la requête
    if ($stmt = $conn->prepare($sql)) {
        // Liaison du paramètre (le ? correspond à $user_id)
        $stmt->bind_param('iii', $value, $user_id, $project_id);

        // Exécution de la requête
        $stmt->execute();
    } else {
        // Gestion de l'erreur si la requête échoue
        die("Erreur dans la requête : " . $conn->error);
    }
}

function set_vote_state($conn, $value, $project_id) {
    //Changer le booléen dans equipesprj "votingTask" à 1(true)
    $sql = "UPDATE equipesprj
        SET votingTask = ?
        WHERE IdEq = ?";

    // Préparation de la requête
    if ($stmt = $conn->prepare($sql)) {
        // Liaison du paramètre (le ? correspond à $user_id)
        $stmt->bind_param('ii', $value, $project_id);

        // Exécution de la requête
        $stmt->execute();
    } else {
        // Gestion de l'erreur si la requête échoue
        die("Erreur dans la requête : " . $conn->error);
    }
}

function get_task_estimations($conn){
    //[TODO] : créer la table et corriger la requête
    $sql = "SELECT *
            FROM coutstaches";

    // Préparation de la requête
    if ($stmt = $conn->prepare($sql)) {

        // Exécution de la requête
        $stmt->execute();

        // Récupération des résultats
        $result = $stmt->get_result();

        // Récupération des enregistrements sous forme de tableau associatif
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        // Gestion de l'erreur si la requête échoue
        die("Erreur dans la requête : " . $conn->error);
    }
}

$RoleUser = get_roles_for_user_for_project($conn,$ID_user,$project_id)[0]['IdR'];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Planning Poker</title>
</head>

<body>
    <div id="header">
        <?php include "header.php"; ?>
    </div>

    <div class="container">
        <?php
        $tasks = get_tasks_list($conn, $project_id);
        set_pp_participant($conn, $project_id, $ID_user, 1);

        if (array_key_exists('leavePP', $_POST)) {
            set_vote_state($conn, 0, $project_id);
            set_pp_participant($conn, $project_id, $ID_user, 0);
            header("Location: projet.PHP?id=$project_id");
            exit;
        }

        if (array_key_exists('beginBtn', $_POST)) {
            set_vote_state($conn, 1, $project_id);
            header("Location: ppvote.PHP?id=$project_id");
        }

        if (array_key_exists('stopBtn', $_POST)) {
            set_vote_state($conn, 0, $project_id);
            header("Location: ppvote.PHP?id=$project_id");
        }
        ?>

        <form method="post">
            <input type="submit" name="leavePP" class="button-blue"
                <?php if($RoleUser === 'SM') echo 'value="Arrêter le PP"'; else echo 'value="Quitter le PP"'; ?> />
        </form>

        <br>

        <?php
        if (is_planning_poker($conn, $project_id)[0]['PP'] == 1 && count($tasks) > 0) {
            $taskToEval = $tasks[0];
            set_task_to_eval($conn, $taskToEval['IdT'], 1);

            if (array_key_exists('voteBtn', $_POST)) {
                $sql = "INSERT INTO voterpp VALUES (?, ?, ?)";

                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param('iii', $ID_user, $taskToEval['IdT'], $_POST['choix']);
                    $stmt->execute();
                } else {
                    die("Erreur dans la requête : " . $conn->error);
                }
                header("Location: recapvote.php");
            }
        ?>

        <!-- Tableau avec le titre, la description et les participants -->
        <div class="table-container">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Description</th>
                        <th>Participants</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><?= htmlspecialchars($taskToEval['TitreT']) ?></td>
                        <td><?= htmlspecialchars($taskToEval['UserStoryT']) ?></td>
                        <td>
                            <ul>
                                <?php
                                $participants = get_pp_participants($conn, $project_id);
                                foreach ($participants as $participant) :
                                ?>
                                <li>
                                    <?= htmlspecialchars($participant['NomU']) . ' ' . htmlspecialchars($participant['PrenomU']) ?>
                                    <?php if (htmlspecialchars($participant['IdU']) == $ID_user) echo " (vous)"; ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <br>

        <?php if (is_actually_voting($conn, $project_id)[0]['votingTask'] == 1) { ?>
        <form method="post" class="form-box">
            <label for="choix">Sélectionnez votre vote :</label>
            <select id="choix" name="choix" required>
                <option value="">--Choisissez une estimation--</option>
                <?php
                $estimations = get_task_estimations($conn);
                foreach ($estimations as $estimation) :
                ?>
                <option value=<?= htmlspecialchars($estimation['IdCout']) ?>>
                    <?= htmlspecialchars($estimation['ValCout']) ?>
                </option>
                <?php endforeach; ?>
            </select>

            <br>
            <input type="submit" name="voteBtn" class="button-blue" value="Soumettre le vote">
        </form>
        <br>

        <?php if ($RoleUser === 'SM') { ?>
        <form method="post">
            <input type="submit" name="stopBtn" class="button-blue" value="Arrêter le vote">
        </form>
        <br>
        <?php }
        } else 
        {
        if ($RoleUser === 'SM') 
        { ?>
        <form method="post">
            <input type="submit" name="beginBtn" class="button-blue" value="Commencer le vote">
        </form>
        <br>
        <?php }
        } ?>

        <?php if ($RoleUser === 'SM') 
        { ?>
        <?php 
        }
        } else 
        {
            echo "<div>Aucun planning poker n'est en cours.</div>";
        }
        ?>
    </div>
</body>

</html>
