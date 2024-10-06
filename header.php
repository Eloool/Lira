<?php
function Suppr_cookie(){

    session_start();
    require_once 'functions.php';
    $conn = db_connect();
    // Suppression du cookie de username
    if(isset($_COOKIE['username'])){
        
        
        $sql = "CALL Change_State_User(?);";

        // Préparation de la requête
        $stmt = $conn->prepare($sql);

        // Liaison du paramètre (le ? correspond à $user_id)
        $stmt->bind_param('i', $_SESSION['user_id']);

        // Exécution de la requête
        $stmt->execute();
        $_SESSION = [];
        header("Location: connexion.php");
        exit();
    } 
}
//Si appui sur le button déconnextion alors lance la fonction Suppr_cookie
if (isset($_GET['push_deconnextion'])) {
    Suppr_cookie();
}
?>
<header>
    <link rel="stylesheet" href="style.css">
        <div class="logo">
            <img src="logo.png" alt="Logo" class="logo">
        </div>
        <div>
        <a href="accueil.php">
            <button class="button">Accueil</button>
        </a>
            <a href='header.php?push_deconnextion=true'>
            <button class="button">Déconnexion</button>
        </a>
            
        </div>
</header>
