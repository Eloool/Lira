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
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Préparer la requête SQL
    $sql = "SELECT * FROM utilisateurs WHERE NomU = ? AND MotDePAsseU = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifier si l'utilisateur existe
    if ($result->num_rows > 0) {
        // Définir un cookie valable 7 jours
        setcookie("username", $username, time() + (7 * 24 * 60 * 60), "/");
        
        // Afficher un message et rediriger vers la page d'accueil après 3 secondes
        echo "Connexion réussie. Bienvenue, $username!";
        header("refresh:3;url=accueil.php");  // Redirection après 3 secondes vers accueil.php
        exit();  // Terminer le script pour éviter que le reste de la page ne s'exécute
    } else {
        echo "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
</head>
<body>

<?php include 'include/header.php'; ?>

<h2>Page de connexion</h2>
<form method="POST" action="index.php">
    <label for="username">Nom d'utilisateur:</label>
    <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required><br>

    <label for="password">Mot de passe:</label>
    <input type="password" id="password" name="password" required><br>

    <input type="submit" value="Se connecter">
</form>

<?php include 'include/footer.php'; ?>

</body>
</html>
