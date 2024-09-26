<?php
    include "accueil.php";
    echo "<p>Page project owner</p><br>";
?>
   <!-- Définition du projet : desc app finale, attente du client -->
   <!-- Besoin d'ajouter la colonne :
        ALTER TABLE equipesprj
        ADD descProj VARCHAR(55); -->

    <form method="POST">
        <br>Définition du projet<br>
        <textarea name="defProj" rows="5" cols="50"></textarea><br>
        <button type="submit">Valider</button>
        <br><br><br>
    </form>

    <?php
        $idEq = 1; //il faut récup l'id du projet actuel
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
        $idSpr = 1; //il faut récup l'id du sprint actuel
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
        $conn->close();
    ?>

<!-- A CONTINUER -->
