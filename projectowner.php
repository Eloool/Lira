    <form method="POST">
        <br>Définition du projet<br>
        <textarea name="defProj" rows="5" cols="50"></textarea><br>
        <button type="submit">Valider</button>
        <br><br><br>
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

    <!-- Saisie revues de sprint -->
    <form method="POST">
        <br>Saisie revues de sprint<br>
        <textarea name="revSpr" rows="5" cols="50"></textarea><br>
        <button type="submit">Valider</button>
        <br><br><br>
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
    <br>
    <br>
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
    <?php include "bac_a_sable.php";?>

