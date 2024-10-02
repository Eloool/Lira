<?php
    include "accueil.php";
    echo "<p>Page scrum master</p><br>";
?>


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
