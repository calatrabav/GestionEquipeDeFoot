<?php
$host = 'mysql-pigneaux.alwaysdata.net';
$dbname = 'gestions_matchs';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    echo "Connexion réussie à la base de données !";
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
