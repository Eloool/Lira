<?php
session_start();  // Assure-toi que la session est bien démarrée
require_once 'functions.php';

// Récupération de l'utilisateur connecté
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Connexion à la base de données
$conn = db_connect();

if ($user_id) {
    // Récupération des données de l'utilisateur
    $all_projects = get_all_projects($conn); // Tous les projets
    $user_projects = get_user_projects($conn, $user_id); // Projets de l'utilisateur connecté
    $user_tasks = get_user_tasks($conn, $user_id); // Tâches de l'utilisateur connecté
} else {
    // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
    header("Location: login.php");
    exit();
}
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
        <th>Description</th>
    </tr>
</thead>
<tbody>
    <?php foreach ($all_projects as $project) : ?>
        <tr>
            <td><?= htmlspecialchars($project['NomEq']) ?></td>
            <td><?= htmlspecialchars($project['descProj']) ?></td>
        </tr>
    <?php endforeach; ?>
</tbody>

</table>

    <?php
    if ($user_id) : ?>
        <h2>Votre tableau de bord personnel</h2>
        <table>
        <thead>
            <tr>
                <th>Nom du projet</th>
                <th>Vos tâches</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($user_projects as $project) : ?>
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
        <?php endif; ?>
</body>
</html>
