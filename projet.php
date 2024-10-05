<?php
session_start(); // Démarre la session

// Inclure le fichier de connexion à la base de données
include 'functions.php';

// Connexion à la base de données
$conn = db_connect();
if (!$conn) {
    die("Erreur de connexion à la base de données.");
}

// Vérifiez si l'utilisateur est connecté en vérifiant la session
if (!isset($_SESSION['user_id'])) {
    // Si l'utilisateur n'est pas connecté, redirigez vers la page de connexion
    header("Location: connexion.php");
    exit(); // Arrête l'exécution du script
}

// Récupération de l'ID de l'utilisateur depuis la session
$ID_user = $_SESSION['user_id'];

// Récupération de l'ID du projet depuis l'URL
$project_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Vérifiez si l'ID du projet est valide
if ($project_id <= 0) {
    echo "ID de projet non spécifié.";
    exit();
}

// Récupérer les tâches pour l'utilisateur et le projet spécifiés
$taches = get_tasks_by_project($conn, $project_id);
$Roleuser = get_roles_for_user_for_project($conn, $ID_user, $project_id)[0]['IdR'];

// Ici, vous pouvez continuer à traiter les tâches et afficher les informations du projet


$etats = get_etats($conn);

if (isset($_POST['Changer'])) {
    // Récupérer les données du formulaire
    $tacheID =$_POST['tache'];
    $etatID =$_POST['etat'];

    // Assigner le rôle à l'utilisateur pour le projet sélectionné
    $UpdateRoleQuery = "UPDATE sprintbacklog SET IdEtat =  ? WHERE IdT = ? ";
    $stmt = $conn->prepare($UpdateRoleQuery);
    $stmt->bind_param('ii', $etatID, $tacheID);
    $stmt->execute();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <?php
    include "header.php" ;
    $sql = "SELECT NomEq
            FROM equipesprj
            WHERE IdEq = ?";
    $nomproj="";

     if ($stmt = $conn->prepare($sql)) {
        // Liaison du paramètre (le ? correspond à $user_id)
        $stmt->bind_param('i',$project_id);

        // Exécution de la requête
        $stmt->execute();

        // Récupération des résultats
        $result = $stmt->get_result();

        // Récupération des enregistrements sous forme de tableau associatif
         $nomproj= $result->fetch_all(MYSQLI_ASSOC)[0]['NomEq'];
    } else {
        // Gestion de l'erreur si la requête échoue
        die("Erreur dans la requête : " . $conn->error);
    }
    echo $nomproj;
    echo "<br>";
    ?>
<?php foreach ($taches as $tache) : ?>
    <tr>
        <td><?= htmlspecialchars($tache['TitreT']) ?></td>

        <form method="post" action="" id="Formulaire_<?= $tache['IdT'] ?>">
            <input type="hidden" name="tache" value="<?= $tache['IdT'] ?>">

            <select name="etat" id="etat-tache">
                <?php
                $ide = get_etat_of_task($conn, $tache['IdT']) - 1;
                echo "<option value='" . $ide . "'>" . $etats[$ide]['DescEtat'] . "</option>";
                foreach ($etats as $etat) :
                    if ($etat['IdEtat'] != $ide + 1) {
                        echo "<option value='" . $etat['IdEtat'] . "'>" . $etat['DescEtat'] . "</option>";
                    }
                endforeach;
                ?>
            </select>

            <input type="submit" value="Changer" name="Changer"/>
        </form>
    </tr>
    <br><br>
<?php endforeach; ?>

<a href="ppvote.php">
<?php
if($Roleuser==='SM'){
    echo "Lancer Planning Poker";
}
else{
    echo "Participer au Planning Poker";
}
echo "</a>";

if($Roleuser==='PO'){
    include 'projectowner.php';
}
if($Roleuser==='SM'){
    include 'projectowner.php';
}
?>
</body>
</html>