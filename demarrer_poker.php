<?php
session_start();
include 'include/db_connect.php';

// Vérifier si l'utilisateur est le Scrum Master
if (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username'];
    
    // Vérifier si l'utilisateur est un administrateur
    $sql = "SELECT is_admin FROM utilisateurs WHERE NomU = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['is_admin'] == 1) {
            // Logique pour démarrer le planning poker
            echo "Le planning poker a démarré !";
            // Rediriger vers la page de planning poker ou afficher un message
            header('Location: planning_poker_session.php');
        }
    }
}
?>
