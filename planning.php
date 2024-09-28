<?php
// Démarrer la session pour accéder aux informations utilisateur
session_start();

// Inclure le fichier de connexion à la base de données
include 'include/db_connect.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_COOKIE['username'])) {
    header('Location: connexion.php');  // Rediriger vers la page de connexion si pas connecté
    exit();
}

// Récupérer le nom d'utilisateur depuis le cookie
$username = $_COOKIE['username'];

// Vérifier si l'utilisateur est le Scrum Master (ou administrateur)
$isScrumMaster = false;
$sql = "SELECT is_admin FROM utilisateurs WHERE NomU = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $isScrumMaster = $row['is_admin'] == 1;  // On considère que l'admin est le Scrum Master
}

// Récupérer les participants en temps réel
$sql = "SELECT NomU, PrenomU FROM utilisateurs WHERE en_planning_poker = 1";  // "en_planning_poker" est un champ indicatif si l'utilisateur est dans le planning
$participants = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning Poker</title>
    <link rel="stylesheet" href="connexion.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>

<h2>Participants au Planning Poker</h2>

<div id="participants">
    <ul>
        <?php if ($participants->num_rows > 0): ?>
            <?php while ($row = $participants->fetch_assoc()): ?>
                <li><?php echo htmlspecialchars($row['NomU'] . ' ' . $row['PrenomU']); ?></li>
            <?php endwhile; ?>
        <?php else: ?>
            <li>Aucun participant pour le moment.</li>
        <?php endif; ?>
    </ul>
</div>

<?php if ($isScrumMaster): ?>
    <form action="demarrer_poker.php" method="post">
        <button type="submit">Démarrer le Planning Poker</button>
    </form>
<?php endif; ?>

<!-- Script pour actualiser automatiquement la liste des participants toutes les 5 secondes -->
<script>
    $(document).ready(function(){
        setInterval(function(){
            $("#participants").load("update_participants.php");
        }, 5000);  // Actualiser toutes les 5 secondes
    });
</script>

</body>
</html>
