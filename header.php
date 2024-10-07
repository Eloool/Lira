<?php

// fonction de suppression de cookie
function Suppr_cookie(){

    session_start();
    require_once 'functions.php';
    $conn = db_connect();
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    // suppression du cookie d'username
    if($user_id){
        // appel de notre procedure
        $sql = "CALL Change_State_User(?);";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $_SESSION = [];
        header("Location: connexion.php");
        exit();
    } 
}

// si appui sur le button déconnexion alors lance la fonction Suppr_cookie
if (isset($_GET['push_deconnextion'])) {
    Suppr_cookie();
}
?>


<header>
    <link rel="stylesheet" href="style.css">
    <div class="logo">
        <img src="logo.png" alt="Logo" class="logo">
    </div>
    <nav>
        <a href="accueil.php">
            <button class="button">Accueil</button>
        </a>
        <a href='header.php?push_deconnextion=true'>
            <button class="button">Déconnexion</button>
        </a>
    </nav>
</header>
