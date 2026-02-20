<?php
/**
 * Setup script to initialize the database for Crochet art by Orly
 */

$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'crochet_art_by_orly';

try {
    // 1. Connect to MySQL without database
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connexion au serveur MySQL réussie.<br>";

    // 2. Create Database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Base de données `$dbname` créée ou déjà existante.<br>";

    // 3. Select Database
    $pdo->exec("USE `$dbname` ");

    // 4. Read and Execute schema.sql
    $sql = file_get_contents('sql/schema.sql');

    // Split SQL into individual statements
    // This is a simple split, might not work with complex triggers but fine for this schema
    $statements = array_filter(array_map('trim', explode(';', $sql)));

    foreach ($statements as $statement) {
        if (!empty($statement)) {
            $pdo->exec($statement);
        }
    }
    echo "Schéma de la base de données importé avec succès.<br>";

    // 5. Create a default Admin account
    $adminUser = 'admin';
    $adminEmail = 'admin@orly.com';
    $adminPass = 'admin123';
    $hashedPass = password_hash($adminPass, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$adminUser]);
    if (!$stmt->fetch()) {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'admin')");
        $stmt->execute([$adminUser, $adminEmail, $hashedPass]);
        echo "Compte administrateur créé :<br>";
        echo "- Utilisateur : $adminUser<br>";
        echo "- Mot de passe : $adminPass<br>";
    } else {
        echo "Le compte administrateur existe déjà.<br>";
    }

    echo "<br><strong>Installation terminée avec succès !</strong><br>";
    echo "<a href='index.php'>Accéder au site</a>";

} catch (PDOException $e) {
    die("Erreur d'installation : " . $e->getMessage());
} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}
?>