<?php
session_start(); // Démarre la session pour gérer les cookies

include 'functions.php';

$conn = db_connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $password = $_POST['password'];

    // Vérifier la connexion à la base de données
    if ($conn->connect_error) {
        die("Erreur de connexion à la base de données: " . $conn->connect_error);
    }

    // Préparer la requête SQL
    $sql = "SELECT * FROM utilisateurs WHERE NomU = ? AND PrenomU = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erreur dans la requête SQL: " . $conn->error);
    }

    $stmt->bind_param("ss", $nom, $prenom);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifier si l'utilisateur existe
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Vérifier si le mot de passe est correct
        if (password_verify($password, $row['MotDePasseU'])) {
            // Requête SQL avec un paramètre
            $sql = "CALL Change_State_User(?);";

            // Préparation de la requête
            $stmt = $conn->prepare($sql);

            // Liaison du paramètre (le ? correspond à $user_id)
            $stmt->bind_param('i', $row['IdU']);

            // Exécution de la requête
            $stmt->execute();

            //Vérifier si l'utilisateur n'est pas déjà connecté
            if($row['is_connected'] == 0){
            // Stocker l'ID de l'utilisateur dans la session
            $_SESSION['user_id'] = $row['IdU'];

            // Vérifier si l'utilisateur est un administrateur
            if ($row['is_admin'] == 1) {
                echo '<div style="background-color: #0055A4;color: white;padding: 15px;border-radius: 5px;text-align: center;font-weight: bold;box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
                position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);">Connexion réussie en tant qu\'administrateur. Redirection vers la page d\'administration...</div>';
                header("refresh:3;url=Admin.php");  // Redirection vers la page admin après 3 secondes
            } else {
                echo '<div style="background-color: #0055A4;color: white;padding: 15px;border-radius: 5px;text-align: center;font-weight: bold;box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
                position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);">Connexion réussie. Bienvenue, $nom $prenom!</div>';
                header("refresh:3;url=acceuil.php");  // Redirection vers la page d'accueil après 3 secondes
            }
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
    <input type="text" id="nom" name="nom" value="" required><br>

    <label for="prenom">Prénom :</label>
    <input type="text" id="prenom" name="prenom" required><br>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required><br>

    <input type="submit" value="Se connecter">
</form>

</body>
</html>


