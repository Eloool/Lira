<?php
session_start();
include 'Include/db_connection.php'; // Connexion à la base de données

$projectId = $_GET['IdEq']; // ID du projet
$role = $_GET['IdR'];// Id du role de l'utilisateur
$userId = $_SESSION['Idu']; // ID de l'utilisateur connecté

// Vérification du rôle de l'utilisateur
$sql = "SELECT IdR FROM rolesutilisateurprojet WHERE IdU = $userId AND IdEq = $projectId";
$result = $conn->query($sql);
$role = $result->fetch_assoc()['IdR'];

// Créer ou rejoindre un salon de planning poker
if ($role === 'SM') {
    // Code pour créer le salon de planning poker
    echo "<form action='start_planning.php' method='POST'>";
    echo "<input type='hidden' name='project_id' value='$projectId'>";
    echo "<input type='submit' value='Commencer le Planning Poker'>";
    echo "</form>";
} else {
    // Code pour rejoindre le salon
    // Vérifie si le salon existe et s'il n'est pas commencé
    // Affiche les tâches à évaluer ici
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Planning Poker</title>
    <link rel="stylesheet" href="connexion.css">
</head>
