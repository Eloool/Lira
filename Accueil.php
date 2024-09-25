<?php
// Démarre la session pour vérifier si l'utilisateur est connecté
session_start();

// Vérifier si l'utilisateur est connecté via le cookie
if (!isset($_COOKIE['username'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: index.php");
    exit();
}

$username = $_COOKIE['username'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
</head>
<body>

<?php include 'include/header.php'; ?>

<h2>Oui, vous êtes sur l'accueil</h2>
<p>Bienvenue sur la page d'accueil, <?php echo htmlspecialchars($username); ?>.</p>

<?php include 'include/footer.php'; ?>

</body>
</html>
