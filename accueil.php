<?php
session_start();
require_once 'functions.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$conn = db_connect();

if ($user_id) {
    // on récupère les données de l'utilisateur
    $all_projects = get_all_projects($conn); // tous les projets
    $user_projects = get_user_projects($conn, $user_id); // projets que de l'utilisateur connecté
    $user_tasks = get_user_tasks($conn, $user_id); // tâches de l'utilisateur connecté
} else {
    // si utilisateur pas connecté
    header("Location: connexion.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tableau de bord</title>
</head>
<body>
    <?php include "header.php"; ?>
    <h1>Tableau de bord</h1>

    <!-- div de tous les projets -->
    <div class="admin">
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
    </div>

    <?php if ($user_id) : ?>
        <!-- div du tableau de bord personnel -->
        <div class="admin">
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
                            <td>
                                <a href="projet.php?id=<?= $project['IdEq'] ?>">
                                    <?= htmlspecialchars($project['NomEq']) ?>
                                </a>
                            </td>
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
        </div>
    <?php endif; ?>

</body>
</html>

