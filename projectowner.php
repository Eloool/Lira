<?php
    include "accueil.php";
    echo "<p>Page project owner</p><br>";
 ?>
   <!-- Définition du projet : desc app finale, attente du client -->
    <button class="button">Définir projet</button>
    <form method="POST">
        <input type="text" name="name"><br>
        <input type="text" name="email"><br>
        <button type="submit">Submit</button>
    </form>

    <?php
    $mon_nom = $_POST['name'];
    $mon_mail = $_POST['email'];

    echo "Votre nom : $mon_nom<br>";
    echo "Votre mail : $mon_mail<br>";
    ?>



    <button class="button">Créer le product backlog</button>
    <button class="button">Saisir revues sprint</button>
    <button class="button">Gérer bac à sable</button>

<!-- A CONTINUER -->