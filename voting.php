<?php
session_start();
include 'Include/db_connection.php'; // Connexion à la base de données

$projectId = $_GET['project_id'];
$userId = $_SESSION['userId'];

// Récupération des tâches à évaluer
$sql = "SELECT IdT, TitreT FROM taches WHERE IdEq = $projectId AND CoutT IN ('?', '999')";
$result = $conn->query($sql);

// Affichage des tâches
while ($row = $result->fetch_assoc()) {
    echo "<form action='submit_vote.php' method='POST'>";
    echo "<input type='hidden' name='task_id' value='".$row['IdT']."'>";
    echo $row['TitreT']." : <input type='number' name='cost' required>";
    echo "<input type='submit' value='Voter'>";
    echo "</form>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Start Planning</title>
    <link rel="stylesheet" href="connexion.css">
</head>