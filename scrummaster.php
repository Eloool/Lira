<!-- Saisie retrospective de sprint -->
<form method="POST">
        <br>Saisie retrospective de sprint<br>
        <textarea name="retSpr" rows="5" cols="50"></textarea><br>
        <button type="submit">Valider</button>
        <br><br><br>
    </form>

    <?php
        $idSpr = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $retSpr = $_POST['retSpr'] ?? null;

        if ($retSpr !== null) {
            $retSpr = $conn->real_escape_string($retSpr);
            $sql = "UPDATE sprints
                    SET RetrospectiveS = '$retSpr'
                    WHERE IdS = $idSpr";
        
            if ($conn->query($sql) === FALSE) {
                echo "Erreur lors de l'ajout : " . $conn->error;
            }
        }
    ?>

<!-- Création sprint -->
<form method="POST">
        <br>Créer un nouveau sprint<br><br>
        Date début : <input type="text" name="dDebut"
            placeholder="au format AAAA-MM-JJ" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}"><br>
        Date fin : <input type="text" name="dFin"
            placeholder="au format AAAA-MM-JJ" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}"><br>
        <button type="submit">Valider</button>
        <br><br><br>
    </form>

    <?php
        $dDebut = $_POST['dDebut'] ?? null;
        $dFin = $_POST['dFin'] ?? null;
        $idEq = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($dDebut !== null && $dFin !== null) {
            $dDebut = $conn->real_escape_string($dDebut);
            $dFin = $conn->real_escape_string($dFin);
            $sql = "INSERT INTO sprints
                    (DateDebS, DateFinS, IdEq)
                    VALUES ('$dDebut', '$dFin', '$idEq')";
            if ($conn->query($sql) !== TRUE) {
                echo "Erreur lors de l'ajout : " . $conn->error;
            }
        }
        include "Include/Ajout_MF.php";
    ?>

<!-- PB vers SB -->
<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['task_id']) && isset($_POST['user_id'])) {
        $task_id = (int)$_POST['task_id'];
        $user_id = (int)$_POST['user_id'];
        $idEq = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $idP = 1;
        
        $sql_insert = "INSERT INTO sprintbacklog (IdT, IdS, IdU, IdEtat) VALUES (?, ?, ?, ?)";
        $sql_delete = "DELETE FROM taches WHERE IdT = $task_id";

        // ajout dans SB
        if ($stmt_insert = $conn->prepare($sql_insert)) {
            $stmt_insert->bind_param('iiii', $task_id, $idEq, $user_id, $idP);
            if ($stmt_insert->execute()) {
                $message = "Tâche ajoutée et assignée à l'utilisateur avec succès !";
            } else {
                $message = "Erreur lors de l'ajout de la tâche : " . $stmt_insert->error;
            }
        } else {
            $message = "Erreur dans la requête d'insertion : " . $conn->error;
        }
        
        // // suppression dans PB
        // if ($stmt_delete = $conn->prepare($sql_delete)) {
        //     if ($stmt_delete->execute()) {
        //         $message = "Tâche supprimée de la table taches";
        //     } else {
        //         $message = "Erreur lors de la suppression dans la table taches : " . $stmt_delete->error;
        //     }
        // } else {
        //     $message = "Erreur de suppression dans la table taches : " . $conn->error;
        // }
    } else {
        $message = "Aucune tâche ou utilisateur sélectionné.";
    }
}

// on récupère les tâches dans sprintbacklog qui ne sont pas dans taches
$sql = "SELECT * 
        FROM sprintbacklog
        RIGHT JOIN taches ON sprintbacklog.IdT = taches.IdT
        WHERE sprintbacklog.IdT IS NULL AND taches.IdEq = ?";

if ($stmt = $conn->prepare($sql)) {
    $project_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $stmt->bind_param('i', $project_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
} else {
    die("Erreur dans la requête : " . $conn->error);
}

// on recupere les utilisateurs du projet
$sql_users = "SELECT * FROM rolesutilisateurprojet
                NATURAL JOIN utilisateurs
                WHERE rolesutilisateurprojet.IdEq = ?";
$users = [];
if ($stmt_users = $conn->prepare($sql_users)) {
    $stmt_users->bind_param('i', $project_id);
    $stmt_users->execute();
    $result_users = $stmt_users->get_result();
    $users = $result_users->fetch_all(MYSQLI_ASSOC); 
} else {
    die("Erreur dans la requête des utilisateurs : " . $conn->error);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajouter une tâche et assigner un utilisateur</title>
</head>
<body>

<?php if (isset($message)) : ?>
    <p><?= htmlspecialchars($message) ?></p> 
<?php endif; ?>

<form method="POST" action="">
    <table border="1">
        <thead>
            <tr>
                <th>Sélection</th>
                <th>Id</th>
                <th>Titre</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($data)) : ?>
                <?php foreach ($data as $project) : ?>
                    <tr>
                        <td>
                            <input type="radio" name="task_id" value="<?= htmlspecialchars($project['IdT']) ?>" required>
                        </td>
                        <td><?= htmlspecialchars($project['IdT']) ?></td>
                        <td><?= htmlspecialchars($project['TitreT']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="3">Aucune tâche disponible à ajouter.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <label for="user_id">Attribuer à l'utilisateur :</label>
    <select name="user_id" id="user_id" required>
        <option value="">-- Sélectionner un utilisateur --</option>
        <?php foreach ($users as $user) : ?>
            <option value="<?= htmlspecialchars($user['IdU']) ?>"><?= htmlspecialchars($user['NomU']) ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Ajouter la tâche et l'assigner</button>
</form>

</body>
</html>
