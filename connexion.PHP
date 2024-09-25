<?php
// Démarre la session pour gérer les cookies
session_start();

// Inclure le fichier de connexion à la base de données
include 'include/db_connect.php';

// Vérifier si un cookie existe déjà
if (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username'];
} else {
    $username = '';
}

// Gestion de la connexion
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $password = $_POST['password'];

    // Vérifier si la connexion à la base de données est bien établie
    if ($conn->connect_error) {
        die("Erreur de connexion à la base de données: " . $conn->connect_error);
    }

    // Préparer la requête SQL
    $sql = "SELECT * FROM utilisateurs WHERE NomU = ? AND PrenomU = ? AND MotDePAsseU = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Erreur dans la requête SQL: " . $conn->error);
    }

    $stmt->bind_param("sss", $nom, $prenom, $password);
    $stmt->execute();

    // Récupérer le résultat
    $result = $stmt->get_result();

    if ($result === false) {
        die("Erreur lors de la récupération des résultats: " . $stmt->error);
    }

    // Vérifier si l'utilisateur existe
    if ($result->num_rows > 0) {
        // Définir un cookie valable 7 jours
        setcookie("username", $nom, time() + (7 * 24 * 60 * 60), "/");

        echo "Connexion réussie. Bienvenue, $nom $prenom!";
        header("refresh:3;url=accueil.php");  // Redirection après 3 secondes vers accueil.php
        exit();  // Terminer le script pour éviter que le reste de la page ne s'exécute
    } else {
        echo "Nom, prénom ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Page de connexion</h2>
<form method="POST" action="connexion.php">
    <label for="nom">Nom:</label>
    <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($username); ?>" required><br>

    <label for="prenom">Prénom:</label>
    <input type="text" id="prenom" name="prenom" required><br>

    <label for="password">Mot de passe:</label>
    <input type="password" id="password" name="password" required><br>

    <input type="submit" value="Se connecter">
</form>

</body>
</html>
