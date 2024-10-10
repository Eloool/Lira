<!-- formulaire pour ajouter une tâche au PB -->
<h1>Ajouter une nouvelle tâche au PB</h1>
<form method="POST" class="task-form">

    <label for="titre">Titre de la tâche :</label>
    <input type="text" id="titre" name="titre" placeholder="Titre de la tâche">

    <label for="userStory">User story :</label>
    <input type="text" id="userStory" name="userStory" placeholder="Décrivez l'user story">

    <label for="idPrio">Priorité de la tâche :</label>
    <input type="text" id="idPrio" name="idPrio" placeholder="Priorité de la tâche">

    <input type="submit" value="Valider">
</form>

<?php
    $titre = $_POST['titre'] ?? null;
    $userStory = $_POST['userStory'] ?? null;
    $idEq = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $idcout = 1;
    $idPrio = $_POST['idPrio'] ?? null;
    if (!strlen($idPrio)) $idPrio = "null";

    if ($titre !== null && $userStory !== null && $idPrio !== null) {
        $titre = $conn->real_escape_string($titre);
        $userStory = $conn->real_escape_string($userStory);
        $idPrio = $conn->real_escape_string($idPrio);
        // requête d'insertion dans le PB
        $sql = "INSERT INTO taches (TitreT, UserStoryT, IdEq, IdCout, IdPriorite)
                VALUES ('$titre', '$userStory', '$idEq', '$idcout', $idPrio)";
        if ($conn->query($sql) !== TRUE) {
            echo "Erreur lors de l'ajout : " . $conn->error;
        }
    }
?>

