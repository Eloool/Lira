<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>


<?php
include 'header.php';
?>
<div class="admin">

<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


// démarre la session pour vérifier si l'user est connecté
session_start();
if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(32)); // génère un token sécurisé
}


mysqli_report(MYSQLI_REPORT_OFF); 
$mysqli = @new mysqli("localhost", "root", "", "lira");
// vérifie la connexion (connect_errno renvoie un numéro d'erreur, 0 si pas d'erreur)
if ( $mysqli->connect_errno ) {
        echo "Impossible de se connecter à MySQL: errNum=" . $mysqli->connect_errno .
    " errDesc=". $mysqli -> connect_error;
 exit();
 }


// Fonction pour ajouter un utilisateur
function ajouterUtilisateur($nom, $prenom, $mot_de_passe, $specialite) {
    global $mysqli;

    // requête d'insertion
    $stmt = $mysqli->prepare("INSERT INTO utilisateurs (NomU, PrenomU, MotDePasseU, SpecialiteU) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        die("Erreur de préparation de la requête: " . $mysqli->error);
    }

    // on hashe le mdp pour plus de sécurité
    $mot_de_passe_hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    // on lie les paramètres afin de pouvoir exécuter la requête
    $stmt->bind_param("ssss", $nom, $prenom, $mot_de_passe_hash, $specialite);
    if ($stmt->execute()) {
        echo "Utilisateur ajouté avec succès.<br>";
    } else {
        echo "Erreur lors de l'ajout de l'utilisateur: " . $stmt->error;
    }

    $stmt->close();
}

// ajout d'un nouvel utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nom"], $_POST["prenom"], $_POST["mot_de_passe"], $_POST["specialite"])) {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $mot_de_passe = $_POST["mot_de_passe"];
    $specialite = $_POST["specialite"];
    
    ajouterUtilisateur($nom, $prenom, $mot_de_passe, $specialite);
}



// mise à jour d'un utilisateur existant
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_user'])) {
    $ancien_nom = $_POST['ancien_nom'];
    $ancien_prenom = $_POST['ancien_prenom'];
    $nouveau_nom = $_POST['nouveau_nom'];
    $nouveau_prenom = $_POST['nouveau_prenom'];
    $nouveau_password = password_hash($_POST['nouveau_password'], PASSWORD_DEFAULT);

    $sql = "UPDATE utilisateurs SET NomU = ?, PrenomU = ?, MotDePAsseU = ? WHERE NomU = ? AND PrenomU = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $nouveau_nom, $nouveau_prenom, $nouveau_password, $ancien_nom, $ancien_prenom);

    if ($stmt->execute()) {
        echo "Utilisateur mis à jour avec succès.";
    } else {
        echo "Erreur lors de la mise à jour de l'utilisateur : " . $conn->error;
    }
}

// on met à jour l'utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Nomeq"])) {
    $NomPrj = $_POST["Nomeq"];

    ajouterUtilisateur($NomPrj);
}



// Fonction pour ajouter une équipe et affecter un Scrum Master avec son rôle
function ajouterEquipeEtAffecterRoles($NomEq, $IdScrumMaster) {
    global $mysqli;

    // on vérifie si le Scrum Master existe
    $stmtSM = $mysqli->prepare("SELECT IdU FROM utilisateurs WHERE IdU = ?");
    if ($stmtSM === false) {
        die("Erreur de préparation de la requête (Scrum Master): " . $mysqli->error);
    }

    $stmtSM->bind_param("i", $IdScrumMaster);
    if (!$stmtSM->execute()) {
        die("Erreur lors de l'exécution de la requête (Scrum Master): " . $stmtSM->error);
    }
    
    $resultSM = $stmtSM->get_result();

    if ($resultSM->num_rows === 0) {
        echo "Erreur : Aucun Scrum Master trouvé.<br>";
        return;
    }

    $stmtRole = $mysqli->prepare("SELECT IdR FROM roles WHERE IdR = 'SM'");
    if ($stmtRole === false) {
        die("Erreur de préparation de la requête (Rôle): " . $mysqli->error);
    }

    if (!$stmtRole->execute()) {
        die("Erreur lors de l'exécution de la requête (Rôle): " . $stmtRole->error);
    }

    $resultRole = $stmtRole->get_result();
    
    if ($resultRole->num_rows === 0) {
        echo "Erreur : Aucun rôle trouvé.<br>";
        return;
    }

    $rowRole = $resultRole->fetch_assoc();
    $IdRole = $rowRole['IdR'];

    // requête d'insertion de l'équipe
    $stmtEquipe = $mysqli->prepare("INSERT INTO equipesprj (NomEq) VALUES (?)");
    if ($stmtEquipe === false) {
        die("Erreur de préparation de la requête (Équipe): " . $mysqli->error);
    }
    $stmtEquipe->bind_param("s", $NomEq);

    // on exécute la requête
    if ($stmtEquipe->execute()) {
        echo "Équipe ajoutée avec succès.<br>";
        $idEquipe = $stmtEquipe->insert_id;
        echo "ID de l'équipe ajoutée : " . $idEquipe . "<br>";

        // requête pour lier le Scrum Master avec son rôle dans l'équipe
        $stmtAssociation = $mysqli->prepare("INSERT INTO rolesutilisateurprojet (IdU, IdR, IdEq) VALUES (?, ?, ?)");
        if ($stmtAssociation === false) {
            die("Erreur de préparation de la requête (Association): " . $mysqli->error);
        }

        $stmtAssociation->bind_param("ssi", $IdScrumMaster, $IdRole, $idEquipe);
        if ($stmtAssociation->execute()) {
            echo "Scrum Master et rôle associés à l'équipe avec succès.<br>";
        } else {
            echo "Erreur : ce ScrumMatser est déjà lié à une autre équipe <br>";
        }
        $stmtAssociation->close();

    } else {
        echo "Erreur lors de l'ajout de l'équipe: " . $stmtEquipe->error;
    }

    $stmtEquipe->close();
    $stmtSM->close();
    $stmtRole->close();
}

// soumission du formulaire avec système de tokens
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["NomEq"]) && isset($_POST["IdScrumMaster"]) && isset($_POST['token'])) {
    if (hash_equals($_SESSION['token'], $_POST['token'])) {
        $NomEq = $_POST["NomEq"];
        $IdScrumMaster = $_POST["IdScrumMaster"];
        ajouterEquipeEtAffecterRoles($NomEq, $IdScrumMaster);
        
        // on redirige pour éviter plusieurs soumissions
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "Erreur : Token CSRF invalide.";
    }
}

// requête pour récupérer les utilisateurs pour le formulaire
$stmtUsers = $mysqli->prepare("SELECT IdU, CONCAT(NomU, ' ', PrenomU) AS FullName FROM utilisateurs");
if ($stmtUsers === false) {
    die("Erreur de préparation de la requête (Utilisateurs): " . $mysqli->error);
}

if (!$stmtUsers->execute()) {
    die("Erreur lors de l'exécution de la requête (Utilisateurs): " . $stmtUsers->error);
}

$resultUsers = $stmtUsers->get_result();    

?>


<div>
        <p><b><u>Tableau de bord des activités</u></b></p>
    </div>

    <!-- formulaire d'ajout d'un user -->
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
    

    <!-- formulaire de modification d'un utilisateur -->
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
    

<!-- formulaire d'ajout d'une équipe -->
<form method="POST">
    <h3>Ajouter une équipe + ScrumMaster</h3>
    
    <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>">
    
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


<?php
// on récupère les équipes et les utilisateurs associés
$sql = "SELECT equipesprj.NomEq, utilisateurs.NomU, utilisateurs.PrenomU, roles.IdR 
        FROM equipesprj
        LEFT JOIN rolesutilisateurprojet ON equipesprj.IdEq = rolesutilisateurprojet.IdEq
        LEFT JOIN utilisateurs ON rolesutilisateurprojet.IdU = utilisateurs.IdU
        LEFT JOIN roles ON rolesutilisateurprojet.IdR = roles.IdR
        ORDER BY equipesprj.NomEq, utilisateurs.NomU";

$result = $mysqli->query($sql);
if (!$result) {
    die("Erreur de requête : " . $mysqli->error);
}

// tableau pour afficher les équipes et leurs utilisateurs
echo "<h3>Liste des équipes et utilisateurs associés</h3>";
echo "<table border='1'>";
echo "<tr><th>Équipe</th><th>Utilisateur</th><th>Rôle</th></tr>";

// variables qui stockent l'équipe actuelle et vérifient si elle a des utilisateurs
$currentTeam = null;
$teamHasUser = false;

while ($row = $result->fetch_assoc()) {
    $teamName = $row['NomEq'];
    $userName = $row['NomU'] ? $row['NomU'] . ' ' . $row['PrenomU'] : null;
    $role = $row['IdR'];
    
    if ($teamName !== $currentTeam) {
        echo "<tr><td><strong>$teamName</strong></td>";
        
        if ($userName) {
            echo "<td>$userName</td><td>$role</td></tr>";
            $teamHasUser = true;
        } else {
            echo "<td colspan='2'>Aucun utilisateur lié à cette équipe</td></tr>";
            $teamHasUser = false;
        }
        
        // mise à jour de l'équipe actuelle
        $currentTeam = $teamName;
    } else {
        // affiche les autres utilisateurs de l'équipe
        if ($userName) {
            echo "<tr><td></td><td>$userName</td><td>$role</td></tr>";
            $teamHasUser = true;
        }
    }
}

if (!$teamHasUser) {
    echo "<tr><td></td><td colspan='2'>Aucun utilisateur lié à cette équipe</td></tr>";
}

echo "</table>";

$mysqli->close(); 
?>


</body> 
</html>
