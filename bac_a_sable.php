<?php
// on récupère la description de l'idée, l'ID d'équipe, et l'ID de l'utilisateur
$descIdea = $_POST['descIdea'] ?? null;
$idEq = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$idU = $_SESSION['user_id'] ?? null;

if ($descIdea !== null && $idEq !== null && $idU !== null) {
    $descIdea = $conn->real_escape_string($descIdea);
    
    // on s'assure que les IDs sont bien entiers
    $idEq = (int)$idEq;
    $idU = (int)$idU;

    // insertion de l'idée dans la base de données
    $sql = "INSERT INTO idees_bac_a_sable (desc_Idee_bas, IdU, IdEq) 
            VALUES ('$descIdea', $idU, $idEq)";

    if ($conn->query($sql) !== TRUE) {
        echo "<p class='error-message'>Erreur lors de l'ajout : " . $conn->error . "</p>";
    } else {
        echo "<p class='success-message'>Idée ajoutée avec succès !</p>";
    }
}

// système de suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];

    // on vérifie si l'idée appartient à l'équipe de l'utilisateur
    $sqlCheck = "SELECT IdEq FROM idees_bac_a_sable WHERE Id_Idee_bas = $delete_id AND IdU = $idU";
    $resultCheck = $conn->query($sqlCheck);

    if ($resultCheck->num_rows > 0) {
        $sqlDelete = "DELETE FROM idees_bac_a_sable WHERE Id_Idee_bas = $delete_id";

        if ($conn->query($sqlDelete) === TRUE) {
            echo "<p class='success-message'>Idée supprimée avec succès !</p>";
        } else {
            echo "<p class='error-message'>Erreur lors de la suppression : " . $conn->error . "</p>";
        }
    }
}
?>

<!-- div d'ajout d'une idée au bac à sable -->
<div class="idee-container">
    <h1>Ajouter une nouvelle idée au bac à sable</h1>
    <form method="POST">
        <label for="descIdea">Description de l'idée :</label>
        <textarea name="descIdea" id="descIdea" placeholder="Décrivez votre idée ici..." required style="width: 340px; height: 100px;"></textarea><br>

        <input type="submit" value="Valider">
    </form>
</div>

<h2>Liste des idées du bac à sable</h2>
<table border="1">
    <tr>
        <th>ID de l'idée</th>
        <th>Description</th>
        <th>ID de l'utilisateur</th>
        <th>ID de l'équipe</th>
        <th>Actions</th>
    </tr>
    <?php
    // on récupère les idées du bac à sable
    $sql = "SELECT Id_Idee_bas, desc_Idee_bas, IdU, IdEq FROM idees_bac_a_sable";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // affichage des idées
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['Id_Idee_bas'] . "</td>
                    <td>" . htmlspecialchars($row['desc_Idee_bas']) . "</td>
                    <td>" . $row['IdU'] . "</td>
                    <td>" . $row['IdEq'] . "</td>
                    <td>";
            if ($row['IdU'] == $idU) {
                // bouton de suppression
                echo "<form method='POST' style='display:inline;'>
                        <input type='hidden' name='delete_id' value='" . $row['Id_Idee_bas'] . "'>
                        <input type='submit' value='Supprimer'>
                    </form>";
            }
            echo "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>Aucune idée trouvée.</td></tr>";
    }
    ?>
</table>

<?php
$conn->close();
?>
