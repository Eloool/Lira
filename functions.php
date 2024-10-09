<?php
// fonction de connexion à la base
function db_connect() {
    // infos de connexion
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "agiletools";

    // création de la connexion avec mysqli
    $conn = new mysqli($servername, $username, $password, $dbname);

    // on vérifie les possibles erreurs de connexion
    if ($conn->connect_error) {
        die("Échec de la connexion : " . $conn->connect_error);
    }
    
    return $conn;
}


// fonction pour récupérer tous les projets
function get_all_projects($conn) {
    $sql = "SELECT * FROM equipesprj";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        die("Erreur dans la requête : " . $conn->error);
    }
}


// fonction pour récupérer les projets d'un utilisateur
function get_user_projects($conn, $user_id) {
    $sql = "SELECT * 
            FROM equipesprj
            JOIN rolesutilisateurprojet ON rolesutilisateurprojet.IdEq = equipesprj.IdEq
            WHERE rolesutilisateurprojet.IdU = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        die("Erreur dans la requête : " . $conn->error);
    }
}


// fonction pour récupérer les tâches d'un utilisateur
function get_user_tasks($conn, $user_id) {
    $sql = "SELECT * 
            FROM taches
            JOIN sprintbacklog ON sprintbacklog.IdT = taches.IdT
            WHERE sprintbacklog.IdU = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        die("Erreur dans la requête : " . $conn->error);
    }
}


// fonction pour récupérer les tâches d'un projet
function get_tasks_by_project($conn, $project_id) {
    $sql = "SELECT * 
            FROM taches
            JOIN sprintbacklog ON sprintbacklog.IdT = taches.IdT
            WHERE taches.IdEq = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $project_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        die("Erreur dans la requête : " . $conn->error);
    }
}


// fonction pour récupérer le rôle d'un utilisateur sur un projet
function get_roles_for_user_for_project($conn,$user_id,$project_id){
    $sql = "SELECT IdR
            FROM rolesutilisateurprojet
            WHERE IdU = ? AND IdEq = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('ii', $user_id, $project_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        die("Erreur dans la requête : " . $conn->error);
    }
}


// fonction pour récupérer les tâches d'un utilisateur sur un projet
function get_tasks_for_user_by_project($conn,$user_id,$project_id){
    $sql = "SELECT TitreT,IdT
            FROM taches
            JOIN equipesprj ON taches.IdEq = equipesprj.IdEq
            JOIN rolesutilisateurprojet ON rolesutilisateurprojet.IdEq = equipesprj.IdEq
            WHERE rolesutilisateurprojet.IdU = ? AND equipesprj.IdEq = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('ii', $user_id, $project_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        die("Erreur dans la requête : " . $conn->error);
    }
}


// fonction pour récupérer l'état des tâches
function get_etats($conn){
    $sql = "SELECT DescEtat,IdEtat
            FROM etatstaches";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        die("Erreur dans la requête : " . $conn->error);
    }
}


// fonction pour récupérer l'état d'une tâche spécifique
function get_etat_of_task($conn,$tache_id){
    $sql = "SELECT IdEtat
            FROM sprintbacklog
            WHERE IdT = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $tache_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC)[0]['IdEtat'];
    } else {
        die("Erreur dans la requête : " . $conn->error);
    }
}

// fin des fonctions
?>
