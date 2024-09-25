<?php
require_once 'functions.php';

// Récupération de l'utilisateur connecté (à adapter selon la méthode d'authentification)
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Connexion à la base de données
$pdo = db_connect();

// Récupération des données
$all_projects = get_all_projects($pdo); // Tous les projets
$user_projects = get_user_projects($pdo, $user_id); // Projets de l'utilisateur connecté
$user_tasks = get_user_tasks($pdo, $user_id); // Tâches de l'utilisateur connecté

// Affichage du contenu HTML
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tableau de bord</title>
</head>
<body>
    <h1>Tableau de bord</h1>

    <h2>Tous les projets</h2>
    <table>
    <thead>
        <tr>
            <th>Nom du projet</th>
            <th>Tâches</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($all_projects as $project) : ?>
            <tr>
                <td><?= htmlspecialchars($project['NomEq']) ?></td>
                <td>
                    <ul>
                        <?php foreach ($user_tasks as $task) : ?>
                            <?php if ($task['IdEq'] == $project['IdEq']) : ?>
                                <li><?= htmlspecialchars($task['TitreT']) ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

    <?php if ($user_id) : ?>
        <h2>Votre tableau de bord</h2>
        <?php endif; ?>
</body>
</html>
