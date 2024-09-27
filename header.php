<?php
function Suppr_cookie(){
    // Suppression du cookie de username
    if(isset($_COOKIE['username'])){
        setcookie('username');
        unset($_COOKIE['username']);
        header("Location: connexion.php");
    }
    
}
if (isset($_GET['push_deconnextion'])) {
    Suppr_cookie();
}
?>
    <header class="header">
        <div class="logo">
            <img src="logo.png" alt="Logo" class="logo">
        </div>
        <h1 class="title">LIRA</h1>
        <div>
            <button class="button">Accueil</button>
            <a  href='header.php?push_deconnextion=true'>
            <button class="button">Déconnexion</button>
        </a>
            
        </div>
    </header>
