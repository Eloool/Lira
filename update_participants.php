<?php
// Connexion à la base de données
$bdd = new PDO('mysql:host=localhost;dbname=lira;charset=utf8', 'root', '');

// Vérifier si des participants ont été sélectionnés
if (!empty($_POST['participants'])) {
    $participants = $_POST['participants'];

    // Mettre à jour les participants dans une session (ou tu peux aussi les stocker temporairement dans une table si nécessaire)
    session_start();
    $_SESSION['participants'] = $participants;

    echo "Participants mis à jour avec succès.";
} else {
    echo "Aucun participant sélectionné.";
}
?>
