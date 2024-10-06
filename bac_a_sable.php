<?php
// Récupération de la description de l'idée et de l'ID d'équipe
$descIdea = $_POST['descIdea'] ?? null;
$idEq = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Récupération de l'ID de l'utilisateur connecté depuis la session
$idU = $_SESSION['user_id'] ?? null; // Remplacez 'user_id' par la clé de session appropriée

if ($descIdea !== null && $idEq !== null && $idU !== null) {
    // Échappez les caractères spéciaux pour éviter les injections SQL
    $descIdea = $conn->real_escape_string($descIdea);
    $idEq = (int)$idEq; // Assurez-vous que l'ID d'équipe est un entier
    $idU = (int)$idU; // Assurez-vous que l'ID de l'utilisateur est un entier

    // Insertion de l'idée dans la base de données
    $sql = "INSERT INTO idees_bac_a_sable (desc_Idee_bas, IdU, IdEq) 
            VALUES ('$descIdea', $idU, $idEq)";

    if ($conn->query($sql) !== TRUE) {
        echo "<p class='error-message'>Erreur lors de l'ajout : " . $conn->error . "</p>";
    } else {
        echo "<p class='success-message'>Idée ajoutée avec succès !</p>";
    }
}

// Gestion de la suppression
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = (int)$_POST['delete_id'];

    // Vérifiez si l'idée appartient à l'équipe de l'utilisateur
    $sqlCheck = "SELECT IdEq FROM idees_bac_a_sable WHERE Id_Idee_bas = $delete_id AND IdU = $idU";
    $resultCheck = $conn->query($sqlCheck);

    if ($resultCheck->num_rows > 0) {
        // ID existe, essayez de supprimer
        $sqlDelete = "DELETE FROM idees_bac_a_sable WHERE Id_Idee_bas = $delete_id";

        if ($conn->query($sqlDelete) === TRUE) {
            echo "<p class='success-message'>Idée supprimée avec succès !</p>";
        } else {
            echo "<p class='error-message'>Erreur lors de la suppression : " . $conn->error . "</p>";
        }
    }
}
?>

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
    // Récupération des idées du bac à sable
    $sql = "SELECT Id_Idee_bas, desc_Idee_bas, IdU, IdEq FROM idees_bac_a_sable";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Affichage des idées
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['Id_Idee_bas'] . "</td>
                    <td>" . htmlspecialchars($row['desc_Idee_bas']) . "</td>
                    <td>" . $row['IdU'] . "</td>
                    <td>" . $row['IdEq'] . "</td>
                    <td>";
            // Vérifiez si l'idée appartient à l'utilisateur
            if ($row['IdU'] == $idU) {
                // Bouton de suppression
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
$conn->close(); // Fermez la connexion à la base de données
?>
