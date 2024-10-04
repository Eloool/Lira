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
            WHERE IdEq = ? AND (CoutT = '?' OR CoutT = '999')";

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
            JOIN participantspp ON participantspp.IdU = utilisateurs.IdU
            WHERE participantspp.IdEq = ?";

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

function add_pp_participant($conn, $project_id, $user_id){
    
}

function begin_vote() {
    
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
    <title>Document</title>
</head>
<body>
    <?php
    include "header.php" ;

    $tasks = get_tasks_list($conn, $project_id);
    //Vérifier si un planning poker est en cours
    if(is_planning_poker($conn, $project_id)[0]['PP'] == 1 && count($tasks) > 0){
        $taskToEval = $tasks[0];
        set_task_to_eval($conn,$taskToEval['IdT'],1);

        ?>
        <div>
            <?=$taskToEval['TitreT']?>
        </div>
        <div>
            <?=$taskToEval['UserStoryT']?>
        </div>
        <?php
        
        //Vérifier si l'utilisateur est SM ou lambda
        if($RoleUser === 'SM'){
            echo "<div> Amogus </div>";
            //Afficher les participants
            /*
            $participants = get_pp_participants($conn, $project_id);
            foreach ($participants as $participant) : ?>
                <tr>
                    <td><?= htmlspecialchars($participant['NomU']) htmlspecialchars($participant['PrenomU'])?></td>
                </tr>
            <?php endforeach;*/
            
            if(array_key_exists('beginBtn', $_POST)) {
                begin_vote();
            }
            ?>

            <input type="submit" name="beginBtn"
            class="button" value="Commencer le vote" 
            
            <?php
        }
    }
    else {
        //Aucun planning poker n'es en cours
        echo "<div> Aucun planning poker n'es en cours </div>";
    }

    ?>
</body>
</html>