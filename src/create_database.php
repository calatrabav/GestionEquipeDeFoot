<?php
// Script PHP pour créer la base de données et les tables

$host = 'localhost'; // Modifier le port si nécessaire
$username = 'root';
$password = '';

try {
    // Connexion au serveur MySQL
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Création de la base de données
    $pdo->exec("CREATE DATABASE IF NOT EXISTS GestionMatchs;");
    $pdo->exec("USE GestionMatchs;");

    // Création des tables
    $pdo->exec("CREATE TABLE IF NOT EXISTS Joueurs (
        idJoueur VARCHAR(50) PRIMARY KEY,
        nomJoueur VARCHAR(50) NOT NULL,
        prenomJoueur VARCHAR(50) NOT NULL,
        numLicence INT NOT NULL UNIQUE,
        statut VARCHAR(50),
        dateNaissanceJoueur DATE,
        tailleJoueur INT,
        poidsJoueur INT,
        postePrincipal VARCHAR(50),
        victoire BOOLEAN
    );");

    $pdo->exec("CREATE TABLE IF NOT EXISTS Matchs (
        idMatch VARCHAR(50) PRIMARY KEY,
        dateMatch DATE NOT NULL,
        heureMatch TIME NOT NULL,
        nomEquipeAdverse VARCHAR(50),
        lieuRencontre VARCHAR(50),
        competition VARCHAR(50),
        scoreEquipe INT,
        victoire BOOLEAN,
        matchNul BOOLEAN,
        scoreEquipeAdverse INT
    );");

    $pdo->exec("CREATE TABLE IF NOT EXISTS Participer (
        idJoueur VARCHAR(50),
        idMatch VARCHAR(50),
        evaluation INT,
        titulaire BOOLEAN,
        poste VARCHAR(50),
        nbRemplacements INT,
        commentaire VARCHAR(50),
        PRIMARY KEY (idJoueur, idMatch),
        FOREIGN KEY (idJoueur) REFERENCES Joueurs(idJoueur) ON DELETE CASCADE,
        FOREIGN KEY (idMatch) REFERENCES Matchs(idMatch) ON DELETE CASCADE
    );");

    echo "Base de données et tables créées avec succès.";

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
