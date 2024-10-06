<?php
function Suppr_cookie(){
    // Suppression du cookie de username
    if(isset($_COOKIE['username'])){
        
        $_SESSION = [];
        header("Location: connexion.php");
    } 
}
//Si appui sur le button déconnextion alors lance la fonction Suppr_cookie
if (isset($_GET['push_deconnextion'])) {
    Suppr_cookie();
}
?>

<header class="header">
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