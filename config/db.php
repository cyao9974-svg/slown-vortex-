<?php
// Configuration de la base de données
$host = 'localhost';
$dbname = 'crochet_art_by_orly';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    if ($e->getCode() == 1049) {
        die("La base de données n'existe pas. Veuillez lancer le script d'installation : <a href='setup.php'>setup.php</a>");
    }
    die("Erreur de connexion : " . $e->getMessage());
}
?>