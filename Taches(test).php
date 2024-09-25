<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="style.css" rel="stylesheet" media="all" type="text/css">
    <title>Document</title>
</head>

<body>
    <?php
    $mysqli = @new mysqli("localhost", "root", "", "agiletools");
    $requete = "SELECT Etat,idEtat FROM etatstaches";
    $resultat = $mysqli->query($requete, MYSQLI_USE_RESULT );
    if ( $resultat ){
        echo ("<select name=").("tache").(" id=").("tache-select").(">");
        while ($row = $resultat->fetch_row()){
            echo ("<option value=").$row[1].(">").$row[0].("</option>");
        }
        echo "</select>";
    }
    ?>
</select>
</select>
</body>
</html>