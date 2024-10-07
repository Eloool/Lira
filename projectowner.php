    <!-- formulaire de définition de projet -->
    <form method="POST" class="formulaire-projet">
        <h3>Définition du projet</h3>
        <textarea name="defProj" rows="5" cols="50"></textarea><br>
        <input type="submit" value="Valider">
    </form>

    <?php
        $idEq = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $defProj = $_POST['defProj'] ?? null;

        if ($defProj !== null) {
            $defProj = $conn->real_escape_string($defProj);
            $sql = "UPDATE equipesprj
                    SET descProj = '$defProj'
                    WHERE IdEq = $idEq";
        
            if ($conn->query($sql) === FALSE) {
                echo "Erreur lors de l'ajout : " . $conn->error;
            }
        }
    ?>

    <!-- formulaire de saisie des revues de sprint -->
    <form method="POST" class="formulaire-sprint">
        <h3>Saisie revues de sprint</h3>
        <textarea name="revSpr" rows="5" cols="50"></textarea><br>    
        <input type="submit" value="Valider">
    </form>

    <?php
        $idSpr = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $revSpr = $_POST['revSpr'] ?? null;

        if ($revSpr !== null) {
            $revSpr = $conn->real_escape_string($revSpr);
            $sql = "UPDATE sprints
                    SET RevueS = '$revSpr'
                    WHERE IdS = $idSpr";
        
            if ($conn->query($sql) === FALSE) {
                echo "Erreur lors de l'ajout : " . $conn->error;
            }
        }

       include "creation_pb.php"; 
    ?>

    <!-- tableau d'affichage des tâches -->
    <table>
        <thead>
            <tr>
                <th>Titre de la tâche</th>
                <th>État</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($taches as $tache) : ?>
            <tr>
                <td><?= htmlspecialchars($tache['TitreT']) ?></td>

                <td>
                    <form method="post" action="" id="Formulaire_<?= $tache['IdT'] ?>" class="form-etat-tache">
                        <input type="hidden" name="tache" value="<?= $tache['IdT'] ?>">

                        <select name="etat" id="etat-tache" class="select-etat">
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
                    </td>

                    <td>
                        <input type="submit" value="Changer" name="Changer" class="button" />
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php include "bac_a_sable.php"; ?>
