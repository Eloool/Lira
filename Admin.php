<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Admin.css">
    <title>Page Admin</title>
</head>
<body>

<?php
include 'header.php';
?>
<div class="admin">
<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


// Démarre la session pour vérifier si l'utilisateur est connecté
session_start();


mysqli_report(MYSQLI_REPORT_OFF); 
$mysqli = @new mysqli("localhost", "root", "", "lira");
// vérifier la connexion connect_errno renvoie un numéro d'erreur , 0 si aucune erreur
if ( $mysqli->connect_errno ) {
        echo "Impossible de se connecter à MySQL: errNum=" . $mysqli->connect_errno .
    " errDesc=". $mysqli -> connect_error;
 exit();
 }

// Fonction pour ajouter un utilisateur
function ajouterUtilisateur($nom, $prenom, $mot_de_passe, $specialite) {
    global $mysqli;

    // Préparer la requête d'insertion
    $stmt = $mysqli->prepare("INSERT INTO utilisateurs (NomU, PrenomU, MotDePasseU, SpecialiteU) VALUES (?, ?, ?, ?)");

    // Vérifier si la préparation a réussi
    if ($stmt === false) {
        die("Erreur de préparation de la requête: " . $mysqli->error);
    }

    // Hashage du mot de passe pour la sécurité
    $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    // Lier les paramètres (nom, prénom, mot de passe, spécialité)
    $stmt->bind_param("ssss", $nom, $prenom, $mot_de_passe_hash, $specialite);

    // Exécuter la requête
    if ($stmt->execute()) {
        echo "Utilisateur ajouté avec succès.<br>";
    } else {
        echo "Erreur lors de l'ajout de l'utilisateur: " . $stmt->error;
    }

    // Fermer la déclaration
    $stmt->close();
}

// Si le formulaire est soumis, ajouter un nouvel utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nom"], $_POST["prenom"], $_POST["mot_de_passe"], $_POST["specialite"])) {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $mot_de_passe = $_POST["mot_de_passe"];
    $specialite = $_POST["specialite"];
    
    ajouterUtilisateur($nom, $prenom, $mot_de_passe, $specialite);
}

// Gestion de la mise à jour d'un utilisateur existant
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $ancien_nom = $_POST['ancien_nom'];
    $ancien_prenom = $_POST['ancien_prenom'];
    $nouveau_nom = $_POST['nouveau_nom'];
    $nouveau_prenom = $_POST['nouveau_prenom'];
    $nouveau_password = password_hash($_POST['nouveau_password'], PASSWORD_DEFAULT); // Hachage du nouveau mot de passe

    $sql = "UPDATE utilisateurs SET NomU = ?, PrenomU = ?, MotDePAsseU = ? WHERE NomU = ? AND PrenomU = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nouveau_nom, $nouveau_prenom, $nouveau_password, $ancien_nom, $ancien_prenom);

    if ($stmt->execute()) {
        echo "Utilisateur mis à jour avec succès.";
    } else {
        echo "Erreur lors de la mise à jour de l'utilisateur : " . $conn->error;
    }
}
// Si le formulaire est soumis, mettre à jour l'utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Nomeq"])) {
    $NomPrj = $_POST["Nomeq"];

    ajouterUtilisateur($NomPrj);
}

// Fonction pour ajouter une équipe et affecter un Scrum Master avec son rôle
function ajouterEquipeEtAffecterRoles($NomEq, $IdScrumMaster) {
    global $mysqli;

    // Vérifier si le Scrum Master existe
    $stmtSM = $mysqli->prepare("SELECT IdU FROM utilisateurs WHERE IdU = ?");
    if ($stmtSM === false) {
        die("Erreur de préparation de la requête (Scrum Master): " . $mysqli->error);
    }

    $stmtSM->bind_param("i", $IdScrumMaster); // Assumer que IdU est de type integer
    if (!$stmtSM->execute()) {
        die("Erreur lors de l'exécution de la requête (Scrum Master): " . $stmtSM->error);
    }
    
    $resultSM = $stmtSM->get_result();

    if ($resultSM->num_rows === 0) {
        echo "Erreur : Aucun Scrum Master trouvé.<br>";
        return; // Sortir de la fonction si le Scrum Master n'existe pas
    }

    // Sélectionner le rôle par défaut (ex: 'SM')
    $stmtRole = $mysqli->prepare("SELECT IdR FROM roles WHERE IdR = 'SM'"); // Modifie la condition selon tes besoins
    if ($stmtRole === false) {
        die("Erreur de préparation de la requête (Rôle): " . $mysqli->error);
    }

    if (!$stmtRole->execute()) {
        die("Erreur lors de l'exécution de la requête (Rôle): " . $stmtRole->error);
    }

    $resultRole = $stmtRole->get_result();
    
    if ($resultRole->num_rows === 0) {
        echo "Erreur : Aucun rôle trouvé.<br>";
        return; // Sortir de la fonction si le rôle n'existe pas
    }

    $rowRole = $resultRole->fetch_assoc();
    $IdRole = $rowRole['IdR'];

    // Préparer la requête d'insertion pour l'équipe
    $stmtEquipe = $mysqli->prepare("INSERT INTO equipesprj (NomEq) VALUES (?)");
    if ($stmtEquipe === false) {
        die("Erreur de préparation de la requête (Équipe): " . $mysqli->error);
    }

    // Lier les paramètres
    $stmtEquipe->bind_param("s", $NomEq);

    // Exécuter la requête
    if ($stmtEquipe->execute()) {
        echo "Équipe ajoutée avec succès.<br>";
        // Obtenir l'ID auto-incrémenté de la nouvelle équipe
        $idEquipe = $stmtEquipe->insert_id;
        echo "ID de l'équipe ajoutée : " . $idEquipe . "<br>";

        // Associer le Scrum Master et son rôle à l'équipe
        $stmtAssociation = $mysqli->prepare("INSERT INTO rolesutilisateurprojet (IdU, IdR, IdEq) VALUES (?, ?, ?)");
        if ($stmtAssociation === false) {
            die("Erreur de préparation de la requête (Association): " . $mysqli->error);
        }

        $stmtAssociation->bind_param("ssi", $IdScrumMaster, $IdRole, $idEquipe);
        if ($stmtAssociation->execute()) {
            echo "Scrum Master et rôle associés à l'équipe avec succès.<br>";
        } else {
            echo "Erreur lors de l'association du Scrum Master et du rôle: " . $stmtAssociation->error;
        }
        $stmtAssociation->close();

    } else {
        echo "Erreur lors de l'ajout de l'équipe: " . $stmtEquipe->error;
    }

    // Fermer les déclarations
    $stmtEquipe->close();
    $stmtSM->close();
    $stmtRole->close();
}

// Gestion de la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["NomEq"]) && isset($_POST["IdScrumMaster"])) {
    $NomEq = $_POST["NomEq"];
    $IdScrumMaster = $_POST["IdScrumMaster"];
    ajouterEquipeEtAffecterRoles($NomEq, $IdScrumMaster);
}

// Récupérer les utilisateurs pour le formulaire
$stmtUsers = $mysqli->prepare("SELECT IdU, CONCAT(NomU, ' ', PrenomU) AS FullName FROM utilisateurs");
if ($stmtUsers === false) {
    die("Erreur de préparation de la requête (Utilisateurs): " . $mysqli->error);
}

if (!$stmtUsers->execute()) {
    die("Erreur lors de l'exécution de la requête (Utilisateurs): " . $stmtUsers->error);
}

$resultUsers = $stmtUsers->get_result();    

$mysqli->close(); 
?>
    <div>
        <p><b><u>Tableau de bord des activités</u></b></p>
    </div>

    <!-- Formulaire pour ajouter un utilisateur -->
    <form action="" method="post">
            <h3>Ajouter un nouvel utilisateur</h3>

        <label for="nom">Nom :</label>
        <input type="text" id="nom" name="nom" required><br>

        <label for="prenom">Prénom :</label>
        <input type="text" id="prenom" name="prenom" required><br>

        <label for="mot_de_passe">Mot de passe :</label>
        <input type="password" id="mot_de_passe" name="mot_de_passe" required><br>

        <label for="specialite">Spécialité :</label>
        <select id="specialite" name="specialite" required>
            <option value="Développeur">Développeur</option>
            <option value="Modeleur">Modeleur</option>
            <option value="Animateur">Animateur</option>
            <option value="UI">UI</option>
            <option value="IA">IA</option>
            <option value="WebComm">WebComm</option>
            <option value="Polyvalent" selected>Polyvalent</option>
        </select><br>

        <input type="submit" value="Ajouter un nouvel utilisateur">

    </form>
    <!-- Formulaire pour modifier un utilisateur -->
    <form action="" method="POST" >
    <h3>Mise à jour d'un utilisateur existant</h3>
    <label for="ancien_nom">Ancien nom:</label>
    <input type="text" id="ancien_nom" name="ancien_nom" required><br>

    <label for="ancien_prenom">Ancien prénom:</label>
    <input type="text" id="ancien_prenom" name="ancien_prenom" required><br>

    <label for="nouveau_nom">Nouveau nom:</label>
    <input type="text" id="nouveau_nom" name="nouveau_nom" required><br>

    <label for="nouveau_prenom">Nouveau prénom:</label>
    <input type="text" id="nouveau_prenom" name="nouveau_prenom" required><br>

    <label for="nouveau_password">Nouveau mot de passe:</label>
    <input type="password" id="nouveau_password" name="nouveau_password" required><br>

    <input type="submit" value="Mettre à jour l'utilisateur">
</form>
    
<!-- Formulaire pour ajouter une équipe -->
<form method="POST">
    <h3> Ajouter une équipe + ScrumMaster</h3>
        Nom de l'équipe : <input type="text" name="NomEq" required><br>
        Scrum Master :
        <select name="IdScrumMaster" required>
            <option value="">Sélectionner un Scrum Master</option>
            <?php while ($row = $resultUsers->fetch_assoc()): ?>
                <option value="<?= $row['IdU'] ?>"><?= htmlspecialchars($row['FullName']) ?></option>
            <?php endwhile; ?>
        </select><br>
        <input type="submit" value="Ajouter Équipe">
    </form>

</div>
</body> 
</html>
