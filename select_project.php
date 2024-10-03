<?php
session_start();
include 'include/db_connect.php'; // Connexion à la base de données

$userId = $_SESSION['IdU']; // ID de l'utilisateur connecté

// Récupération des projets de l'utilisateur
$sql = "SELECT p.IdEq, p.NomEq, r.IdR FROM projets p 
        JOIN rolesutilisateurprojet r ON p.IdEq = r.IdEq 
        WHERE r.IdU = $userId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<a href='planning_poker.php?project_id=".$row['IdEq']."'>".$row['NomEq']." (".$row['IdR'].")</a><br>";
    }
} else {
    echo "Aucun projet trouvé.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Choix Projets</title>
    <link rel="stylesheet" href="connexion.css">
</head>