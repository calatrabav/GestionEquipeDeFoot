<?php

class createDatabase
{
    private $linkpdo;
    private $server = 'localhost';
    private $db = 'gestion_equipe_de_foot_db';
    private $user = 'root';
    private $password = '';
    public function __construct()
    {
        $this->connectDB();
        $this->createTables();
    }
    private function connectDB()
    {
        try {
            $this->linkpdo = new PDO("mysql:host=$this->server;dbname=$this->db", $this->user, $this->password);
            $this->linkpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            die('Erreur de connexion : ' . $e->getMessage());
        }
    }
    private function createTables()
    {
        $query = 'CREATE TABLE IF NOT EXISTS Joueurs (
    idJoueur INT AUTO_INCREMENT PRIMARY KEY,
    nomJoueur VARCHAR(50) NOT NULL,
    prenomJoueur VARCHAR(50) NOT NULL,
    numLicence INT UNIQUE NOT NULL,
    statut VARCHAR(50) NOT NULL,
    dateNaissanceJoueur DATE NOT NULL,
    tailleJoueur INT,
    poidsJoueur INT,
    postePrincipal VARCHAR(50),
    victoire BOOLEAN
);
CREATE TABLE IF NOT EXISTS Matches (
    idMatch INT AUTO_INCREMENT PRIMARY KEY,
    dateMatch DATE NOT NULL,
    heureMatch TIME NOT NULL,
    nomEquipeAdverse VARCHAR(50) NOT NULL,
    lieuRencontre VARCHAR(50),
    competition VARCHAR(50),
    scoreEquipe INT NOT NULL,
    victoire BOOLEAN,
    matchNul BOOLEAN,
    scoreEquipeAdverse INT NOT NULL
);
CREATE TABLE IF NOT EXISTS Participer (
    idMatch INT NOT NULL,
    idJoueur INT NOT NULL,
    evaluation INT,
    titulaire BOOLEAN,
    poste VARCHAR(50),
    nbRemplacements INT,
    commentaire VARCHAR(50),
    PRIMARY KEY (idMatch, idJoueur),
    FOREIGN KEY (idMatch) REFERENCES Matches(idMatch) ON DELETE CASCADE,
    FOREIGN KEY (idJoueur) REFERENCES Joueurs(idJoueur) ON DELETE CASCADE
);';
        try {
            $this->linkpdo->exec($query);
        } catch (Exception $e) {
            die('Erreur de création de table : ' . $e->getMessage());
        }
    }
}