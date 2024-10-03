<?php
session_start();
include 'db_connection.php'; // Connexion à la base de données

$taskId = $_POST['task_id'];
$cost = $_POST['cost'];
$userId = $_SESSION['userId'];

// Enregistrement du vote
$sql = "INSERT INTO votes (task_id, user_id, cost) VALUES ($taskId, $userId, $cost)";
$conn->query($sql);

// Logique pour vérifier si tous les votes sont faits et calculer la moyenne
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Start Planning</title>
    <link rel="stylesheet" href="connexion.css">
</head>