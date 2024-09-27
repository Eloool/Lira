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
            // Stocker l'ID de l'utilisateur dans la session
            $_SESSION['user_id'] = $row['IdU'];

            // Redirection selon le type d'utilisateur
            if ($row['is_admin'] == 1) {
                header("refresh:3;url=Admin.php");
            } else {
                header("refresh:3;url=Accueil.php");
            }
            exit();
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
    <link rel="stylesheet" href="style.css">
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