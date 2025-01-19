<?php
$host = 'mysql-pigneaux.alwaysdata.net';
$dbname = 'gestion_matchs'; // Assure-toi que ce nom correspond à ta base
$username = 'pigneaux';
$password = 'Loris2707';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
