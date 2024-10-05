    <!-- Ajout tâche PB -->
    <form method="POST">
        <br>Ajouter une nouvelle tâche au PB<br><br>
        Titre de la tâche : <input type="text" name="titre"><br>
        User story : <input type="text" name="userStory"><br>
        Cout de la tâche : <input type="text" name="cout"><br>
        Priorité de la tâche : <input type="text" name="idPrio"><br>
        <button type="submit">Valider</button>
        <br><br><br>
    </form>

    <?php
        $titre = $_POST['titre'] ?? null;
        $userStory = $_POST['userStory'] ?? null;
        $idEq = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $cout = $_POST['cout'] ?? null;
        $idPrio = $_POST['idPrio'] ?? null;
        if (!strlen($cout)) $cout="null";
        if (!strlen($idPrio)) $idPrio="null";


        if ($titre !== null && $userStory !== null
            && $cout !== null && $idPrio !== null) {
            $titre = $conn->real_escape_string($titre);
            $userStory = $conn->real_escape_string($userStory);
            $cout = $conn->real_escape_string($cout);
            $idPrio = $conn->real_escape_string($idPrio);
            $sql = "INSERT INTO taches
                    (TitreT, UserStoryT, IdEq, IdCout, IdPriorite)
                    VALUES ('$titre', '$userStory', '$idEq', '$cout', $idPrio)";
            if ($conn->query($sql) !== TRUE) {
                echo "Erreur lors de l'ajout : " . $conn->error;
            }
        }
    ?>
