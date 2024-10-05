<?php
session_start();

// Inclure le fichier de connexion à la base de données
include 'include/db_connect.php';

if (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username'];
} else {
    $username = '';
    header("refresh:0;url=connexion.php");
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

function is_timer_voting($conn,$project_id){
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

function begin_vote($conn, $project_id) {
    //Changer le booléen dans equipesprj "votingTask" à 1(true)
    $sql = "UPDATE equipesprj
        SET votingTask = 1
        WHERE IdEq = ?";

    // Préparation de la requête
    if ($stmt = $conn->prepare($sql)) {
        // Liaison du paramètre (le ? correspond à $user_id)
        $stmt->bind_param('i', $project_id);

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

$ID_user = 8;
$project_id = 8;
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
    <?php
    include "header.php" ;

    $tasks = get_tasks_list($conn, $project_id);
    //On connecte l'utilisateur aux participants du PP, même si aucune n'es en cours
    set_pp_participant($conn, $project_id, $ID_user, 1);

    //S'il quitte sur le bouton pour quitter le PP, on le déconnecte du PP et redirige vers sa page projet
    if(array_key_exists('leavePP', $_POST)) {
        set_pp_participant($conn, $project_id, $ID_user, 0);
        header("Location: projet.PHP");
        exit;
    }
    if(array_key_exists('beginBtn', $_POST)) {
        begin_vote($conn, $project_id);
        header("Location: ppvote1.PHP");
    }
    ?>
    <br>
    <form method="post">
        <input type="submit" name="leavePP"
        class="button" value="Quitter le PP">
    </form>
    <br>
    <?php
    //Vérifier si un planning poker est en cours
    if(is_planning_poker($conn, $project_id)[0]['PP'] == 1 && count($tasks) > 0){
        //Si oui, on affiche la premiere tache à pouvoir être évaluer
        $taskToEval = $tasks[0];
        //Et on passe son booléen VotePP à 1(true)
        set_task_to_eval($conn,$taskToEval['IdT'],1);

        if(array_key_exists('voteBtn', $_POST)) {
            //Insertion du vote dans la base
            $sql = "INSERT INTO voterpp VALUES (?, ?, ?)";
    
            // Préparation de la requête
            if ($stmt = $conn->prepare($sql)) {
            // Liaison du paramètre (le ? correspond à $user_id)
            $stmt->bind_param('iii', $ID_user, $taskToEval['IdT'], $_POST['choix']);
    
            // Exécution de la requête
            $stmt->execute();
            } else {
            // Gestion de l'erreur si la requête échoue
            die("Erreur dans la requête : " . $conn->error);
            }
            header("Location: recapvote.php");
        }
        
        ?>
        <div>
            Titre : <?=$taskToEval['TitreT']?>
        </div>
        <div>
            Description : <?=$taskToEval['UserStoryT']?>
        </div>
        <br>
        <?php
        
        if(is_timer_voting($conn, $project_id)[0]['votingTask'] == 1){

            //Avoir un bouton pour commencer le vote timé
            ?>
            <form method="post">
                <label for="choix">Sélectionnez votre vote :</label>
                 <?php 
                    echo "<select id=\"choix\" name=\"choix\" required>
                    <option value=\"\">--Choisissez une estimation--</option>
                    ";

                    //Proposer les estimations possibles
                    $estimations = get_task_estimations($conn);
                    foreach ($estimations as $estimation) : ?>
                        <option value=<?= htmlspecialchars($estimation['IdCout'])?>><?= htmlspecialchars($estimation['ValCout'])?></option>
                    <?php endforeach;
                 ?>
                </select>
                <br>
                <input type="submit" name="voteBtn"
                class="button" value="Soumettre le vote">
            </form>
            <br>
            
            <?php
        }
        else {
            if($RoleUser === 'SM'){
                //Avoir un bouton pour commencer le vote
            ?>
            <form method="post">
                <input type="submit" name="beginBtn"
                class="button" value="Commencer le vote">
            </form><br>
            
            <?php
            }
            
        }

        //Vérifier si l'utilisateur est SM ou lambda
        if($RoleUser === 'SM'){
            //Afficher les participants
            echo "<div> Participants : </div><br>";
            $participants = get_pp_participants($conn, $project_id);
            foreach ($participants as $participant) : ?>
                <tr>
                    <td><?= "· ", htmlspecialchars($participant['NomU']), " ",htmlspecialchars($participant['PrenomU'])?></td>
                </tr>
            <?php endforeach;
        }
    }
    else {
        //Aucun planning poker n'es en cours
        echo "<div> Aucun planning poker n'es en cours </div>";
    }

    ?>
</body>
</html>