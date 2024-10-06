    <!-- Saisie retrospective de sprint -->        
    <h1>Saisie rétrospective de sprint</h1>
    <form method="POST">

        <label for="retSpr">Rétrospective :</label>
        <textarea name="retSpr" id="retSpr"  placeholder="Saisissez la rétrospective de sprint ici..." style="width: 340px; height: 17px;"></textarea>
        <input type="submit" value="Valider">
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
                echo "<p class='error-message'>Erreur lors de l'ajout : " . $conn->error . "</p>";
            }
        }
    ?>

    <!-- Création sprint -->
    <h1>Créer un nouveau sprint</h1>
    <form method="POST">
        <label for="dDebut">Date début :</label>
        <input type="text" name="dDebut" id="dDebut" placeholder="au format AAAA-MM-JJ" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
        <label for="dFin">Date fin :</label>
        <input type="text" name="dFin" id="dFin" placeholder="au format AAAA-MM-JJ" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">
        <input type="submit" value="Valider">
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
                echo "<p class='error-message'>Erreur lors de l'ajout : " . $conn->error . "</p>";
            }
        }

        include "Include/Ajout_MF.php";
    ?>


