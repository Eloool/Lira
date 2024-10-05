
<p>Page scrum master</p><br>



<!-- Saisie retrospective de sprint -->
<form method="POST">
        <br>Saisie retrospective de sprint<br>
        <textarea name="retSpr" rows="5" cols="50"></textarea><br>
        <button type="submit">Valider</button>
        <br><br><br>
    </form>

    <?php
        $idSpr = 1; //il faut récup l'id du sprint actuel
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
        $idEq = 1; //il faut récup l'id du projet/equipe

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
