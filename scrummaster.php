<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Ajouter une tâche et assigner un utilisateur</title>
</head>
<body>
    <div class="container">
        <h1>Saisie Rétrospective de Sprint</h1>
        <form method="POST" class="form">
            <textarea name="retSpr" rows="5" cols="50" placeholder="Entrez la rétrospective ici..."></textarea>
            <input type="submit" value="Valider">
        </form>

        <?php
        $idSpr = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $retSpr = $_POST['retSpr'] ?? null;

        if ($retSpr !== null) {
            $retSpr = $conn->real_escape_string($retSpr);
            $sql = "UPDATE sprints SET RetrospectiveS = '$retSpr' WHERE IdS = $idSpr";

            if ($conn->query($sql) === FALSE) {
                echo "<p class='error'>Erreur lors de l'ajout : " . $conn->error . "</p>";
            }
        }
        ?>

        <h2>Créer un Nouveau Sprint</h2>
        <form method="POST" class="form">
            <label for="dDebut">Date début :</label>
            <input type="text" name="dDebut" placeholder="au format AAAA-MM-JJ" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" required>
            <label for="dFin">Date fin :</label>
            <input type="text" name="dFin" placeholder="au format AAAA-MM-JJ" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" required>
            <input type="submit" value="Valider">
        </form>

        <?php
        $dDebut = $_POST['dDebut'] ?? null;
        $dFin = $_POST['dFin'] ?? null;
        $idEq = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($dDebut !== null && $dFin !== null) {
            $dDebut = $conn->real_escape_string($dDebut);
            $dFin = $conn->real_escape_string($dFin);
            $sql = "INSERT INTO sprints (DateDebS, DateFinS, IdEq) VALUES ('$dDebut', '$dFin', '$idEq')";
            if ($conn->query($sql) !== TRUE) {
                echo "<p class='error'>Erreur lors de l'ajout : " . $conn->error . "</p>";
            }
        }
        include "Include/Ajout_MF.php";
        ?>

        <h2>Ajouter une Tâche et Assigner un Utilisateur</h2>
        <?php if (isset($message)) : ?>
            <p class="message"><?= htmlspecialchars($message) ?></p> 
        <?php endif; ?>

        <form method="POST" action="" class="form">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Sélection</th>
                        <th>Id</th>
                        <th>Titre</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($data)) : ?>
                        <?php foreach ($data as $project) : ?>
                            <tr>
                                <td>
                                    <input type="radio" name="task_id" value="<?= htmlspecialchars($project['IdT']) ?>" required>
                                </td>
                                <td><?= htmlspecialchars($project['IdT']) ?></td>
                                <td><?= htmlspecialchars($project['TitreT']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="3">Aucune tâche disponible à ajouter.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <label for="user_id">Attribuer à l'utilisateur :</label>
            <select name="user_id" id="user_id" required>
                <option value="">-- Sélectionner un utilisateur --</option>
                <?php foreach ($users as $user) : ?>
                    <option value="<?= htmlspecialchars($user['IdU']) ?>"><?= htmlspecialchars($user['NomU']) ?></option>
                <?php endforeach; ?>
            </select>

            <!-- <button type="submit" class="button">Ajouter la tâche et l'assigner</button> -->
            <input type="submit" value="Ajouter la tâche et l'assigner">
        </form>
    </div>
</body>
</html>
