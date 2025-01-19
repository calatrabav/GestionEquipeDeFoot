<?php
$host = 'localhost'; 
$username = 'root';
$password = '';

try {

    


    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // Connexion au serveur MySQL
    $pdo = new PDO("mysql:host=$host", $username, $password, [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
    ]);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // On force la suppression puis recréation de la base (optionnel)
    $pdo->exec("DROP DATABASE IF EXISTS gestion_matchs");
    $pdo->exec("CREATE DATABASE gestion_matchs CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    $pdo->exec("USE gestion_matchs");

    // Liste de requêtes de création de tables
    $queriesCreate = [
        // Table Joueurs
        "CREATE TABLE IF NOT EXISTS Joueurs (
            idJoueur VARCHAR(50) PRIMARY KEY,
            nomJoueur VARCHAR(50) NOT NULL,
            prenomJoueur VARCHAR(50) NOT NULL,
            numLicence INT NOT NULL UNIQUE,
            statut VARCHAR(50),
            dateNaissanceJoueur DATE,
            tailleJoueur INT,
            poidsJoueur INT,
            postePrincipal VARCHAR(50),
            commentaire VARCHAR(50)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

        // Table Matchs
        "CREATE TABLE IF NOT EXISTS Matchs (
            idMatch VARCHAR(50) PRIMARY KEY,
            dateMatch DATE NOT NULL,
            heureMatch TIME NOT NULL,
            nomEquipeAdverse VARCHAR(50),
            lieuRencontre VARCHAR(50),
            competition VARCHAR(50),
            scoreEquipe INT,
            victoire TINYINT(1),
            matchNul TINYINT(1),
            scoreEquipeAdverse INT
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

        // Table Participer
        "CREATE TABLE IF NOT EXISTS Participer (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

        // Table users
        "CREATE TABLE IF NOT EXISTS users (
            id INT(11) NOT NULL AUTO_INCREMENT,
            username VARCHAR(100) NOT NULL,
            password VARCHAR(255) NOT NULL,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    ];

    // On exécute chaque requête de création
    foreach ($queriesCreate as $query) {
        $pdo->exec($query);
    }

    // =============================
    // Insertion de données d'exemple
    // =============================

    // 1) Insérer 20 joueurs
    $insertJoueurs = "
        INSERT INTO Joueurs (idJoueur, nomJoueur, prenomJoueur, numLicence, statut, dateNaissanceJoueur, tailleJoueur, poidsJoueur, postePrincipal, commentaire)
        VALUES
        ('J1', 'Dupont', 'Pierre', 10001, 'Actif', '1990-05-12', 180, 75, 'Attaquant', 'Rapide et agile'),
        ('J2', 'Martin', 'Jean', 10002, 'Actif', '1988-03-22', 175, 70, 'Milieu', 'Bon passeur'),
        ('J3', 'Durand', 'Paul', 10003, 'Blessé', '1995-07-10', 182, 78, 'Défenseur', 'Solide en duel'),
        ('J4', 'Lefebvre', 'Lucas', 10004, 'Actif', '1992-11-01', 188, 82, 'Gardien', 'Réflexes rapides'),
        ('J5', 'Moreau', 'Julien', 10005, 'Suspendu', '1987-09-18', 177, 72, 'Milieu', 'Bon leadership'),
        ('J6', 'Simon', 'Thierry', 10006, 'Actif', '2000-12-25', 179, 73, 'Attaquant', 'Frappe puissante'),
        ('J7', 'Michel', 'Antoine', 10007, 'Actif', '1998-04-03', 185, 80, 'Défenseur', 'Fort de la tête'),
        ('J8', 'Thomas', 'Cédric', 10008, 'Blessé', '1991-06-14', 181, 76, 'Attaquant', 'Rapide sur les cotes'),
        ('J9', 'Petit', 'Adrien', 10009, 'Actif', '1999-01-09', 176, 68, 'Milieu', 'Vision de jeu'),
        ('J10','Roux', 'Maxime', 10010, 'Reprise', '1993-05-30', 183, 79, 'Défenseur', 'Puissant'),
        ('J11','Fontaine', 'Kevin', 10011, 'Actif', '1985-02-20', 180, 80, 'Milieu', 'Polyvalent'),
        ('J12','Carpentier', 'Louis', 10012, 'Actif', '1994-10-08', 178, 74, 'Défenseur', 'Jeu de passe sûr'),
        ('J13','Fernandez', 'Hugo', 10013, 'Actif', '2001-03-15', 186, 85, 'Gardien', 'Bon jeu aérien'),
        ('J14','Lambert', 'Nicolas', 10014, 'Blessé', '1997-07-27', 177, 72, 'Attaquant', 'Doit améliorer la finition'),
        ('J15','Garcia', 'Sébastien', 10015, 'Actif', '1989-11-11', 184, 81, 'Défenseur', 'Expérience solide'),
        ('J16','Bernard', 'Florian', 10016, 'Actif', '1996-08-02', 179, 77, 'Milieu', 'Bonne endurance'),
        ('J17','Fournier', 'Mathieu', 10017, 'Blessé', '1992-02-27', 182, 79, 'Attaquant', 'Retour attendu'),
        ('J18','Girard', 'Alex', 10018, 'Actif', '2003-01-01', 178, 70, 'Défenseur', 'Jeune prometteur'),
        ('J19','Renaud', 'Damien', 10019, 'Actif', '1990-12-12', 180, 75, 'Milieu', 'Jeu collectif'),
        ('J20','Legrand', 'Valentin', 10020, 'Suspendu', '1998-09-23', 176, 69, 'Attaquant', 'Doit travailler tactique')
    ";

    // 2) Insérer 8 matchs (3 passés, 5 à venir, par exemple)
    $insertMatchs = "
        INSERT INTO Matchs (
            idMatch, dateMatch, heureMatch, nomEquipeAdverse, lieuRencontre,
            competition, scoreEquipe, victoire, matchNul, scoreEquipeAdverse
        )
        VALUES
        ('M1','2024-01-10','15:00:00','Lyon FC','Stade Municipal','Ligue 1',2,1,0,1),
        ('M2','2024-02-15','19:00:00','Marseille','Stade Municipal','Coupe de France',1,0,1,1),
        ('M3','2024-03-05','20:30:00','Monaco','Stade Municipal','Ligue 1',0,0,0,2),
        ('M4','2025-02-01','20:00:00','PSG','Parc des Princes','Ligue 1',NULL,0,0,NULL),
        ('M5','2025-03-10','18:00:00','Bordeaux','Stade Municipal','Ligue 1',NULL,0,0,NULL),
        ('M6','2025-04-22','17:00:00','Nantes','Stade Municipal','Coupe de France',NULL,0,0,NULL),
        ('M7','2025-05-15','21:00:00','Lille','Stade Municipal','Ligue 1',NULL,0,0,NULL),
        ('M8','2025-06-01','15:00:00','Rennes','Stade Municipal','Ligue 1',NULL,0,0,NULL)
    ";

    // 3) Insérer des participations
    // M1
    $insertParticipations = [
    "INSERT INTO Participer
        (idJoueur, idMatch, evaluation, titulaire, poste, nbRemplacements, commentaire)
     VALUES
        ('J1', 'M1', 7, 1, 'Attaquant', 0, 'Bon match'),
        ('J2', 'M1', 6, 1, 'Milieu', 1, 'A revoir'),
        ('J3', 'M1', 5, 1, 'Défenseur', 2, 'Manque de rythme'),
        ('J4', 'M1', 8, 1, 'Gardien', 0, 'Arrets decisifs'),
        ('J5', 'M1', 5, 0, 'Milieu', 0, 'Entre en fin de match'),
        ('J6', 'M1', 6, 0, 'Attaquant', 0, 'Correct')",

    // M2
    "INSERT INTO Participer
        (idJoueur, idMatch, evaluation, titulaire, poste, nbRemplacements, commentaire)
     VALUES
        ('J7', 'M2', 6, 1, 'Défenseur', 0, 'Bonne défense'),
        ('J8', 'M2', 6, 1, 'Attaquant', 1, 'Devait marquer'),
        ('J9', 'M2', 7, 1, 'Milieu', 1, 'Bon match'),
        ('J10','M2', 5, 1, 'Défenseur', 2, 'Fatigué en 2e mi-temps'),
        ('J11','M2', 6, 0, 'Milieu', 0, 'Remplaçant'),
        ('J12','M2', 6, 0, 'Défenseur', 0, 'Entré tard')",

    // M3
    "INSERT INTO Participer
        (idJoueur, idMatch, evaluation, titulaire, poste, nbRemplacements, commentaire)
     VALUES
        ('J13','M3', 5, 1, 'Gardien', 0, 'Deux buts encaisses'),
        ('J14','M3', 4, 1, 'Attaquant', 1, 'Peu efficace'),
        ('J15','M3', 5, 1, 'Défenseur', 2, 'Quelques erreurs'),
        ('J16','M3', 6, 1, 'Milieu', 2, 'A tente de construire'),
        ('J17','M3', 4, 0, 'Attaquant', 0, 'Entre, pas d impact'),
        ('J18','M3', 5, 0, 'Défenseur', 0, 'Fait ce qu il a pu')",

    // M4 (à venir)
    "INSERT INTO Participer
        (idJoueur, idMatch, evaluation, titulaire, poste, nbRemplacements, commentaire)
     VALUES
        ('J1','M4', NULL, 0, 'Attaquant', 0, 'Match a venir'),
        ('J2','M4', NULL, 0, 'Milieu', 0, 'Match a venir'),
        ('J3','M4', NULL, 0, 'Défenseur', 0, 'Match a venir'),
        ('J4','M4', NULL, 0, 'Gardien', 0, 'Match a venir'),
        ('J19','M4', NULL, 0, 'Milieu', 0, 'Match a venir'),
        ('J20','M4', NULL, 0, 'Attaquant', 0, 'Match a venir')",

    // M5 (à venir)
    "INSERT INTO Participer
        (idJoueur, idMatch, evaluation, titulaire, poste, nbRemplacements, commentaire)
     VALUES
        ('J5','M5', NULL, 0, 'Milieu', 0, 'Match a venir'),
        ('J6','M5', NULL, 0, 'Attaquant', 0, 'Match a venir'),
        ('J7','M5', NULL, 0, 'Défenseur', 0, 'Match a venir'),
        ('J8','M5', NULL, 0, 'Attaquant', 0, 'Match a venir'),
        ('J9','M5', NULL, 0, 'Milieu', 0, 'Match a venir'),
        ('J10','M5', NULL, 0, 'Défenseur', 0, 'Match a venir')",

    // M6 (à venir)
    "INSERT INTO Participer
        (idJoueur, idMatch, evaluation, titulaire, poste, nbRemplacements, commentaire)
     VALUES
        ('J11','M6', NULL, 0, 'Milieu', 0, 'Match a venir'),
        ('J12','M6', NULL, 0, 'Défenseur', 0, 'Match a venir'),
        ('J13','M6', NULL, 0, 'Gardien', 0, 'Match a venir'),
        ('J14','M6', NULL, 0, 'Attaquant', 0, 'Match a venir'),
        ('J15','M6', NULL, 0, 'Défenseur', 0, 'Match a venir'),
        ('J16','M6', NULL, 0, 'Milieu', 0, 'Match a venir')",

    // M7 (à venir)
    "INSERT INTO Participer
        (idJoueur, idMatch, evaluation, titulaire, poste, nbRemplacements, commentaire)
     VALUES
        ('J1','M7', NULL, 0, 'Attaquant', 0, 'Match a venir'),
        ('J2','M7', NULL, 0, 'Milieu', 0, 'Match a venir'),
        ('J3','M7', NULL, 0, 'Défenseur', 0, 'Match a venir'),
        ('J4','M7', NULL, 0, 'Gardien', 0, 'Match a venir'),
        ('J19','M7', NULL, 0, 'Milieu', 0, 'Match a venir'),
        ('J20','M7', NULL, 0, 'Attaquant', 0, 'Match a venir')",

    // M8 (à venir)
    "INSERT INTO Participer
        (idJoueur, idMatch, evaluation, titulaire, poste, nbRemplacements, commentaire)
     VALUES
        ('J5','M8', NULL, 0, 'Milieu', 0, 'Match a venir'),
        ('J6','M8', NULL, 0, 'Attaquant', 0, 'Match a venir'),
        ('J7','M8', NULL, 0, 'Défenseur', 0, 'Match a venir'),
        ('J8','M8', NULL, 0, 'Attaquant', 0, 'Match a venir'),
        ('J9','M8', NULL, 0, 'Milieu', 0, 'Match a venir'),
        ('J10','M8', NULL, 0, 'Défenseur', 0, 'Match a venir')"
    ];

    // 4) Insérer des utilisateurs
    $insertUsers = "
        INSERT INTO users (username, password)
        VALUES
            ('admin', 'admin')
    ";

    // Exécution pas à pas (pour traquer une éventuelle erreur)
    $inserts = [
        $insertJoueurs,
        $insertMatchs,
        // Participations - plusieurs blocs
        ...$insertParticipations,
        $insertUsers
    ];

    foreach ($inserts as $sql) {
        $pdo->exec($sql);
    }

    echo "Base de données créée et jeu de données inséré avec succès !";

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
