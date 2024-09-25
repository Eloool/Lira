<?php
function db_connect() {
    // Remplacer par les informations de connexion
    $dsn = 'mysql:host=localhost;dbname=lira';
    $user = 'root';
    $password = '';

    try {
        $pdo = new PDO($dsn, $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
    }
}

function get_all_projects($pdo) {
    $sql = "SELECT * FROM equipesprj";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_user_projects($pdo, $user_id) {
    $sql = "SELECT * FROM equipesprj WHERE IdEq = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_user_tasks($pdo, $user_id) {
    // Cette fonction nécessitera une requête SQL plus complexe en fonction de la structure de votre table "taches"
    // Supposons une table "taches" avec les colonnes : id, titre, description, id_projet, id_utilisateur
    $sql = "SELECT * FROM taches WHERE IdEq = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
