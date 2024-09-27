<?php
// Démarre la session pour vérifier si l'utilisateur est connecté
session_start();

// Inclure le fichier de connexion à la base de données
include 'functions.php';

// Vérifier si l'utilisateur est connecté et s'il est admin
if (!isset($_COOKIE['username'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: connexion.php");
    exit();
}

$username = $_COOKIE['username'];

// Vérifier si l'utilisateur connecté est admin
$sql = "SELECT is_admin FROM utilisateurs WHERE NomU = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || $user['is_admin'] != 1) {
    echo "Accès refusé. Vous devez être administrateur pour accéder à cette page.";
    exit();
}

// Message à afficher après une opération
$message = "";

// Gestion de la création d'un nouvel utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_user'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hachage du mot de passe
    $specialite = $_POST['specialite']; // Récupération de la spécialité

    // Insérer l'utilisateur avec la spécialité
    $sql = "INSERT INTO utilisateurs (NomU, PrenomU, MotDePAsseU, SpecialiteU, is_admin) VALUES (?, ?, ?, ?, 0)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nom, $prenom, $password, $specialite);

    if ($stmt->execute()) {
        $message = "Nouvel utilisateur créé avec succès.";
    } else {
        $message = "Erreur lors de la création de l'utilisateur : " . $conn->error;
    }
}

// Gestion de la mise à jour d'un utilisateur existant
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $ancien_nom = $_POST['ancien_nom'];
    $ancien_prenom = $_POST['ancien_prenom'];
    $nouveau_nom = $_POST['nouveau_nom'];
    $nouveau_prenom = $_POST['nouveau_prenom'];
    $nouveau_password = password_hash($_POST['nouveau_password'], PASSWORD_DEFAULT); // Hachage du mot de passe
    $nouvelle_specialite = $_POST['nouvelle_specialite'];

    // Requête pour mettre à jour l'utilisateur
    $sql = "UPDATE utilisateurs 
            SET NomU = ?, PrenomU = ?, MotDePAsseU = ?, SpecialiteU = ?
            WHERE NomU = ? AND PrenomU = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $nouveau_nom, $nouveau_prenom, $nouveau_password, $nouvelle_specialite, $ancien_nom, $ancien_prenom);

    if ($stmt->execute()) {
        $message = "Utilisateur mis à jour avec succès.";
    } else {
        $message = "Erreur lors de la mise à jour de l'utilisateur : " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion des utilisateurs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Création d'un nouvel utilisateur</h2>
<?php if (!empty($message)) { echo "<p>$message</p>"; } ?>
<form method="POST" action="Admin.php">
    <label for="nom">Nom :</label>
    <input type="text" id="nom" name="nom" required><br>

    <label for="prenom">Prénom :</label>
    <input type="text" id="prenom" name="prenom" required><br>

    <label for="password">Mot de passe :</label>
    <input type="password" id="password" name="password" required><br>

    <label for="specialite">Spécialité :</label>
    <select id="specialite" name="specialite" required>
        <option value="Développeur">Développeur</option>
        <option value="Modeleur">Modeleur</option>
        <option value="Animateur">Animateur</option>
        <option value="UI">UI</option>
        <option value="IA">IA</option>
        <option value="WebComm">WebComm</option>
        <option value="Polyvalent">Polyvalent</option>
    </select><br>

    <input type="submit" name="create_user" value="Créer un utilisateur">
</form>

<h2>Mise à jour d'un utilisateur existant</h2>
<form method="POST" action="Admin.php">
    <label for="ancien_nom">Ancien nom :</label>
    <input type="text" id="ancien_nom" name="ancien_nom" required><br>

    <label for="ancien_prenom">Ancien prénom :</label>
    <input type="text" id="ancien_prenom" name="ancien_prenom" required><br>

    <label for="nouveau_nom">Nouveau nom :</label>
    <input type="text" id="nouveau_nom" name="nouveau_nom" required><br>

    <label for="nouveau_prenom">Nouveau prénom :</label>
    <input type="text" id="nouveau_prenom" name="nouveau_prenom" required><br>

    <label for="nouveau_password">Nouveau mot de passe :</label>
    <input type="password" id="nouveau_password" name="nouveau_password" required><br>

    <label for="nouvelle_specialite">Nouvelle spécialité :</label>
    <select id="nouvelle_specialite" name="nouvelle_specialite" required>
        <option value="Développeur">Développeur</option>
        <option value="Modeleur">Modeleur</option>
        <option value="Animateur">Animateur</option>
        <option value="UI">UI</option>
        <option value="IA">IA</option>
        <option value="WebComm">WebComm</option>
        <option value="Polyvalent">Polyvalent</option>
    </select><br>

    <input type="submit" name="update_user" value="Mettre à jour l'utilisateur">
</form>

</body>
</html>
 