<?php
session_start();
include 'Include/db_connection.php'; // Connexion à la base de données

$projectId = $_POST['project_id']; // ID du projet

// Code pour démarrer la session de planning poker
// Créer une table pour stocker les votes
$sql = "INSERT INTO planning_poker (project_id, status) VALUES ($projectId, 'en cours')";
$conn->query($sql);

// Redirection vers la page de vote
header("Location: voting.php?project_id=$projectId");
exit();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Start Planning</title>
    <link rel="stylesheet" href="connexion.css">
</head>