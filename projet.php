<?php
session_start();

// Inclure le fichier de connexion à la base de données
include 'include/db_connect.php';

if (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username'];
} else {
    
    header("refresh:0;url=connexion.php");
}

function get_roles_for_user_for_project($conn,$user_id,$project_id){
    $sql = "SELECT IdR
            FROM rolesutilisateurprojet
            WHERE IdU = ? AND IdEq = ?";

    // Préparation de la requête
    if ($stmt = $conn->prepare($sql)) {
        // Liaison du paramètre (le ? correspond à $user_id)
        $stmt->bind_param('ii', $user_id, $project_id);

        // Exécution de la requête
        $stmt->execute();

        // Récupération des résultats
        $result = $stmt->get_result();

        // Récupération des enregistrements sous forme de tableau associatif
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        // Gestion de l'erreur si la requête échoue
        die("Erreur dans la requête : " . $conn->error);
    }
}

function get_taches_for_user_by_project($conn,$user_id,$project_id){
    $sql = "SELECT TitreT
            FROM taches
            JOIN equipesprj ON taches.IdEq = equipesprj.IdEq
            JOIN rolesutilisateurprojet ON rolesutilisateurprojet.IdEq = equipesprj.IdEq
            WHERE rolesutilisateurprojet.IdU = ? AND equipesprj.IdEq = ?";

    // Préparation de la requête
    if ($stmt = $conn->prepare($sql)) {
        // Liaison du paramètre (le ? correspond à $user_id)
        $stmt->bind_param('ii', $user_id, $project_id);

        // Exécution de la requête
        $stmt->execute();

        // Récupération des résultats
        $result = $stmt->get_result();

        // Récupération des enregistrements sous forme de tableau associatif
        return $result->fetch_all(MYSQLI_ASSOC);
    } else {
        // Gestion de l'erreur si la requête échoue
        die("Erreur dans la requête : " . $conn->error);
    }
}
$ID_user = 2;
$project_id = 2;
$taches = get_taches_for_user_by_project($conn,$ID_user,$project_id);

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
    </tr>
<?php endforeach; ?>

<?php
if(get_roles_for_user_for_project($conn,$ID_user,$project_id)[0]['IdR']==='PO'){
    include 'projectowner.php';
}
if(get_roles_for_user_for_project($conn,$ID_user,$project_id)[0]['IdR']==='SM'){
    include 'projectowner.php';
}
?>
</body>
</html>