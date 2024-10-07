<?php
session_start();

include 'functions.php';

$conn = db_connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $password = $_POST['password'];

    if ($conn->connect_error) {
        die("Erreur de connexion à la base de données: " . $conn->connect_error);
    }

    // requête pour récupérer l'utilisateur dans la base
    $sql = "SELECT * FROM utilisateurs WHERE NomU = ? AND PrenomU = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Erreur dans la requête SQL: " . $conn->error);
    }

    $stmt->bind_param("ss", $nom, $prenom);
    $stmt->execute();
    $result = $stmt->get_result();

    // on vérifie si l'utilisateur existe
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // on vérifie si le mot de passe est correct
        if (password_verify($password, $row['MotDePasseU'])) {
            // appel à notre procedure
            $sql = "CALL Change_State_User(?);";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $row['IdU']);
            $stmt->execute();

            // on vérifie si l'utilisateur n'est pas déjà connecté
            if($row['is_connected'] == 0){
                // on stocke donc l'ID utilisateur dans la session
                $_SESSION['user_id'] = $row['IdU'];

                // on vérifie si l'utilisateur est admin ou non
                if ($row['is_admin'] == 1) {
                    echo '<div style="background-color: #0055A4;color: white;padding: 15px;border-radius: 5px;text-align: center;font-weight: bold;box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
                    position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);">Connexion réussie en tant qu\'administrateur. Redirection vers la page d\'administration...</div>';
                    header("refresh:3;url=Admin.php");  // oui : redirection vers la page admin
                } else {
                    echo '<div style="background-color: #0055A4;color: white;padding: 15px;border-radius: 5px;text-align: center;font-weight: bold;box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
                    position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);">Connexion réussie. Bienvenue, '.$nom. ' '. $prenom.' !</div>';
                    header("refresh:3;url=accueil.php");  // non : redirection vers la page d'accueil
                }
                exit();

            }
            else {
                echo "Utilisateur déjà connecté";
            }
            
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


