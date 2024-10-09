<?php
// on inclut le fichier de connection à la base
include 'db_connect.php';

if (isset($_POST['ajouter'])) {
    $userId = $_POST['utilisateur'];
    $role = $_POST['role'];
    $projetId = $_POST['projet'];

    // assigne un rôle à l'utilisateur pour le projet sélectionné
    $insertRoleQuery = "INSERT INTO rolesutilisateurprojet (IdU, IdR, IdEq) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertRoleQuery);
    $stmt->bind_param('isi', $userId, $role, $projetId);
    $stmt->execute();
    $idproj = strval($projetId);

    header("Location: ../projet.php?id=".$idproj);
}
?>

<body>
<h1>Ajouter un membre au projet</h1>
    
    <form action="include/Ajout_MF.php" method="post">
        <input type="hidden" name="projet" value="<?= $project_id ?>">

        <!-- sélection d'un utilisateur -->
        <label for="utilisateur">Sélectionner un utilisateur :</label>
        <select name="utilisateur" id="utilisateur" required>
            <?php
            // on récupère la liste des utilisateurs dans la base de données
            $utilisateurQuery = "SELECT IdU, NomU, PrenomU FROM utilisateurs";
            $utilisateurResult = $conn->query($utilisateurQuery);
                
            // on affiche la liste
            while ($row = $utilisateurResult->fetch_assoc()) {
                echo "<option value='{$row['IdU']}'>{$row['NomU']} {$row['PrenomU']}</option>";
            }
            ?>
        </select>

        <!-- sélection d'un rôle pour l'utilisateur -->
        <label for="role">Sélectionner un rôle :</label>
        <select name="role" required>
            <?php
            // on récupère la liste des rôles
            $rolesQuery = "SELECT IdR, DescR FROM roles";
            $rolesResult = $conn->query($rolesQuery);
                
            // on parcourt tous les rôles et déselectionne le rôle SM
            while ($row = $rolesResult->fetch_assoc()) {
                if ($row['DescR'] === 'Scrum Master') {
                    echo "<option value='{$row['IdR']}' disabled>{$row['DescR']}</option>";
                } else {
                    echo "<option value='{$row['IdR']}'>{$row['DescR']}</option>";
                }
            }
            ?>
        </select>

        <input type="submit" name="ajouter" value="Ajouter">
    </form>
</body>

<?php
