<?php
function db_connect() {
    // Informations de connexion
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "lira";

    // Création de la connexion avec mysqli
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérification des erreurs de connexion
    if ($conn->connect_error) {
        die("Échec de la connexion : " . $conn->connect_error);
    }

    // Retourner l'objet de connexion si tout va bien
    return $conn;
}

function get_all_projects($conn) {
    // Requête SQL
    $sql = "SELECT * FROM equipesprj";

    // Préparation et exécution de la requête
    if ($stmt = $conn->prepare($sql)) {
        $stmt->execute();

        // Récupération des résultats
        $result = $stmt->get_result();

        // Récupération de tous les enregistrements sous forme de tableau associatif
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        // Gestion de l'erreur si la requête échoue
        die("Erreur dans la requête : " . $conn->error);
    }
}

function get_user_projects($conn, $user_id) {
    // Requête SQL avec un paramètre
    $sql = "SELECT * 
            FROM equipesprj
            JOIN rolesutilisateurprojet ON rolesutilisateurprojet.IdEq = equipesprj.IdEq
            WHERE rolesutilisateurprojet.IdU = ?";

    // Préparation de la requête
    if ($stmt = $conn->prepare($sql)) {
        // Liaison du paramètre (le ? correspond à $user_id)
        $stmt->bind_param('i', $user_id);

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


function get_user_tasks($conn, $user_id) {
    // Requête SQL pour récupérer les tâches d'un utilisateur donné
    $sql = "SELECT * 
            FROM taches
            JOIN sprintbacklog ON sprintbacklog.IdT = taches.IdT
            WHERE sprintbacklog.IdU = ?";

    // Préparation de la requête
    if ($stmt = $conn->prepare($sql)) {
        // Liaison du paramètre (le ? correspond à $user_id)
        $stmt->bind_param('i', $user_id);

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

function get_tasks_by_project($conn, $project_id) {
    // Requête SQL pour récupérer les tâches liées à un projet spécifique
    $sql = "SELECT * 
            FROM taches
            JOIN sprintbacklog ON sprintbacklog.IdT = taches.IdT
            WHERE sprintbacklog.IdEq = ?";

    // Préparation de la requête
    if ($stmt = $conn->prepare($sql)) {
        // Liaison du paramètre (le ? correspond à $project_id)
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

function get_taches_for_user_by_project($conn,$user_id,$project_id){
    $sql = "SELECT TitreT
            FROM taches
            JOIN equipesprj ON taches.IdEq = equipesprj.IdEq
            JOIN rolesutilisateurprojet ON rolesutilisateurprojet.IdEq = equipesprj.IdEq
            WHERE rolesutilisateurprojet.IdU = ? AND equipesprj.IdEq = ?";

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
?>
