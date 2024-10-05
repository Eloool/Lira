<?php
include "db_connect.php";

// Suppression d'une idée
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $deleteSql = "DELETE FROM idees_bac_a_sable WHERE Id_Idee_bas = $id";
    if ($conn->query($deleteSql) === TRUE) 
    {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Erreur lors de la suppression de l'idée : " . $conn->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) 
{
    $desc_Idee_bas = $conn->real_escape_string($_POST["desc_Idee_bas"]);

    $IdU = 1; 
    $IdEq = 1; 

    $sql = "INSERT INTO idees_bac_a_sable (desc_Idee_bas, IdU, IdEq) VALUES ('$desc_Idee_bas', '$IdU', '$IdEq')";

    if ($conn->query($sql) === TRUE) {
        // Redirection après l'ajout pour éviter le double envoi
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Erreur : " . $sql . "<br>" . $conn->error;
    }
}

// Requête pour récupérer les idées
$sql = "SELECT Id_Idee_bas, desc_Idee_bas FROM idees_bac_a_sable ORDER BY Id_Idee_bas ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Gestion des idées</title>
</head>
<body>
    <h1>Ajouter une idée au bac à sable</h1>
    <form method="post" action="">
        <label for="desc_Idee_bas">Description de l'idée :</label><br>
        <textarea id="desc_Idee_bas" name="desc_Idee_bas" rows="4" cols="50" required></textarea><br><br>
        <input type="submit" name="submit" value="Ajouter l'idée">
    </form>

    <h2>Liste des idées :</h2>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Description</th>
            <th>Action</th>
        </tr>
        <?php
        // Affichage des idées sous forme de tableau
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["desc_Idee_bas"] . "</td>";
                echo '<td><a href="?delete=' . $row["Id_Idee_bas"] . '" onclick="return confirm(\'Êtes-vous sûr de vouloir supprimer cette idée ?\');">Supprimer</a></td>';
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>Aucune idée n'a été trouvée.</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
// Fermeture de la connexion
$conn->close();
?>
