<?php
session_start();

include 'functions.php';

$conn = db_connect();
if (!$conn) {
    die("Erreur de connexion à la base de données.");
}

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

// on récupère l'ID de l'utilisateur et du projet
$ID_user = $_SESSION['user_id'];
$project_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($project_id <= 0) {
    echo "ID de projet non spécifié.";
    exit();
}

$sql = "SELECT NomEq
            FROM equipesprj
            WHERE IdEq = ?";
    $nomproj="";

     if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i',$project_id);
        $stmt->execute();
        $result = $stmt->get_result();
         $nomproj= $result->fetch_all(MYSQLI_ASSOC)[0]['NomEq'];
    } else {
        die("Erreur dans la requête : " . $conn->error);
    }

// on récupère les tâches pour l'utilisateur et le projet spécifiés
$tachesuser = get_tasks_for_user_by_project($conn,$ID_user, $project_id);
$taches = get_tasks_by_project($conn, $project_id);
$Roleuser = get_roles_for_user_for_project($conn, $ID_user, $project_id)[0]['IdR'];
// on récupère les états
$etats = get_etats($conn);

if (isset($_POST['Changer'])) {
    $tacheID = $_POST['tache'];
    $etatID = $_POST['etat'];

    // on met à jour l'état de la tâche
    $UpdateRoleQuery = "UPDATE sprintbacklog SET IdEtat =  ? WHERE IdT = ? ";
    $stmt = $conn->prepare($UpdateRoleQuery);
    $stmt->bind_param('ii', $etatID, $tacheID);
    $stmt->execute();
}

if(array_key_exists('beginPP', $_POST)) {
    set_planning_poker($conn,$project_id,1);
    header("Location: ppvote.PHP?id=$project_id");
}

function set_planning_poker($conn,$project_id,$value){
    $sql = "UPDATE equipesprj
        SET equipesprj.PP = ?
        WHERE equipesprj.IdEq = ?";

    // Préparation de la requête
    if ($stmt = $conn->prepare($sql)) {
        // Liaison du paramètre (le ? correspond à $user_id)
        $stmt->bind_param('ii', $value, $project_id);

        // Exécution de la requête
        $stmt->execute();
    } else {
        // Gestion de l'erreur si la requête échoue
        die("Erreur dans la requête : " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title><?= htmlspecialchars($nomproj) ?></title>
</head>
<body>
<?php
    include "header.php" ;
    
    echo "<br>";
    ?>
    <div class="admin">
        <h1><?= htmlspecialchars($nomproj) ?></h1>

        <table>
            <thead>
                <tr>
                    <th>Tâches</th>
                    <th>Changer l'état</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tachesuser as $tache) : ?>
                    <tr>
                        <td><?= htmlspecialchars($tache['TitreT']) ?></td>
                        <td>
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
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="planning-poker">
            <?php
            
                if($Roleuser === 'SM') {
                    
                    echo "<form method=\"post\">
                            <input type=\"submit\" name=\"beginPP\"
                            class=\"button\" value=\"Lancer Planning Poker\">
                        </form>";
                }
                else {
                    echo "<a class=\"button\" href=\"ppvote.php?id=$project_id\">Participer au Planning Poker</a>";
                }
            ?>
        </div>

        <?php if ($Roleuser === 'PO') {
            include 'projectowner.php';
        } elseif ($Roleuser === 'SM') {
            include 'scrummaster.php';
        } ?>
    </div>

</body>
</html>
