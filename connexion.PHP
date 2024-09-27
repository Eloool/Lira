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

    // Préparer la requête SQL pour récupérer l'utilisateur
    $sql = "SELECT * FROM utilisateurs WHERE NomU = ? AND PrenomU = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Erreur dans la requête SQL: " . $conn->error);
    }

    $stmt->bind_param("ss", $nom, $prenom);
    $stmt->execute();

    // Récupérer le résultat
    $result = $stmt->get_result();

    if ($result === false) {
        die("Erreur lors de la récupération des résultats: " . $stmt->error);
    }

    // Vérifier si l'utilisateur existe
    if ($result->num_rows > 0) {
        // L'utilisateur existe, on récupère ses informations
        $row = $result->fetch_assoc();

        // Vérifier si le mot de passe est correct
        if (password_verify($password, $row['MotDePasseU'])) {
            // Définir un cookie valable 7 jours
            setcookie("username", $nom, time() + (7 * 24 * 60 * 60), "/");

            // Vérifier si l'utilisateur est un administrateur
            if ($row['is_admin'] == 1) {
                echo '<div style="background-color: #0055A4;color: white;padding: 15px;border-radius: 5px;text-align: center;font-weight: bold;box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
                position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);">Connexion réussie en tant qu\'administrateur. Redirection vers la page d\'administration...</div>';
                header("refresh:3;url=Admin.php");  // Redirection vers la page admin après 3 secondes
            } else {
                echo '<div style="background-color: #0055A4; color: white; padding: 15px; border-radius: 5px; text-align: center; font-weight: bold; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); 
                position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);"> 
                Connexion réussie. Bienvenue, ' . htmlspecialchars($nom) . ' ' . 
                htmlspecialchars($prenom) . '!</div>';
                header("refresh:3;url=accueil.php");  
            }
            exit();  // Terminer le script pour éviter que le reste de la page ne s'exécute
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Nom ou prénom incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="connexion.css">
</head>
<body>

<h2>Page de connexion</h2>
<form method="POST" action="connexion.php">
    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($username); ?>" required><br>

    <label for="prenom">Prénom :</label>
    <input type="text" id="prenom" name="prenom" required><br>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required><br>

    <input type="submit" value="Se connecter">
</form>

</body>
</html>
