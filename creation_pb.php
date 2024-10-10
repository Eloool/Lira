<!-- formulaire pour ajouter une tâche au PB -->
<h1>Ajouter une nouvelle tâche au PB</h1>
<form method="POST" class="task-form">

    <label for="titre">Titre de la tâche :</label>
    <input type="text" id="titre" name="titre" placeholder="Titre de la tâche">

    <label for="userStory">User story :</label>
    <input type="text" id="userStory" name="userStory" placeholder="Décrivez l'user story">

    <label for="prio">Priorité de la tâche :</label>
        <select name="prio" id="prio" required>
            <?php
            // on récupère la liste des priorités dans la base de données
            $prioQuery = "SELECT idPriorite, DescPriorite, valPriorite FROM prioritestaches";
            $prioResult = $conn->query($prioQuery);

            // on affiche la liste
            while ($row = $prioResult->fetch_assoc()) {
                echo "<option value='{$row['idPriorite']}'>{$row['DescPriorite']}</option>";
            }
            ?>
        </select>

    <input type="submit" value="Valider">
</form>

<?php
    $titre = $_POST['titre'] ?? null;
    $userStory = $_POST['userStory'] ?? null;
    $idEq = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $idcout = 1;
    $prio = $_POST['prio'] ?? null;

    if ($titre !== null && $userStory !== null) {
        $titre = $conn->real_escape_string($titre);
        $userStory = $conn->real_escape_string($userStory);
        // requête d'insertion dans le PB
        $sql = "INSERT INTO taches (TitreT, UserStoryT, IdEq, IdCout, IdPriorite)
                VALUES ('$titre', '$userStory', '$idEq', '$idcout', $prio)";

        if ($conn->query($sql) !== TRUE) {
            echo "Erreur lors de l'ajout : " . $conn->error;
        }
    }
?>
