<?php
// Inclure le fichier de connexion à la base de données
include 'db_connect.php'; // Assurez-vous que le chemin est correct

// Vérifier si le formulaire a été soumis
if (isset($_POST['ajouter'])) {
    // Récupérer les données du formulaire
    $userId = $_POST['utilisateur'];
    $role = $_POST['role'];
    $projetId = $_POST['projet']; // Récupérer l'ID du projet depuis le champ caché 

    // Assigner le rôle à l'utilisateur pour le projet sélectionné
    $insertRoleQuery = "INSERT INTO rolesutilisateurprojet (IdU, IdR, IdEq) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertRoleQuery);
    $stmt->bind_param('isi', $userId, $role, $projetId);
    $stmt->execute();

    echo "<p>L'utilisateur a été ajouté avec succès au projet!</p>";
    header("Location: ../projet.php");
}
?>

<body>
<h1>Ajouter un membre au projet</h1>
    
    <form action="include/Ajout_MF.php" method="post">
        <!-- Champ caché pour transmettre le project_id -->
        <input type="hidden" name="projet" value="<?= $project_id ?>">

        <!-- Sélectionner un utilisateur existant -->
        <label for="utilisateur">Sélectionner un utilisateur :</label>
        <select name="utilisateur" id="utilisateur" required>
            <?php
            // Récupérer la liste des utilisateurs dans la base de données
            $utilisateurQuery = "SELECT IdU, NomU, PrenomU FROM utilisateurs";
            $utilisateurResult = $conn->query($utilisateurQuery);
                
            // Afficher la liste des utilisateurs
            while ($row = $utilisateurResult->fetch_assoc()) {
                echo "<option value='{$row['IdU']}'>{$row['NomU']} {$row['PrenomU']}</option>";
            }
            ?>
        </select>

        <!-- Sélectionner un rôle pour l'utilisateur -->
        <label for="role">Sélectionner un rôle :</label>
        <select name="role" required>
            <?php
            // Récupérer la liste des rôles dans la base de données
            $rolesQuery = "SELECT IdR, DescR FROM roles";
            $rolesResult = $conn->query($rolesQuery);
                
            // Parcourir les résultats des rôles
            while ($row = $rolesResult->fetch_assoc()) {
                // Désactiver le rôle "Scrum Master"
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
