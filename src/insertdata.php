<?php
$host = 'localhost'; 
$username = 'root';
$password = '';
$dbname = 'gestion_matchs';

try {
    // Connexion à la base gestion_matchs
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Insertion d'un utilisateur (admin)
    $hashedPassword = password_hash('admin', PASSWORD_DEFAULT);
    $pdo->exec("INSERT INTO users (username, password) VALUES ('admin', '$hashedPassword');");

    // Insertion de joueurs
    // idJoueur, nomJoueur, prenomJoueur, numLicence, statut, dateNaissanceJoueur, tailleJoueur, poidsJoueur, postePrincipal, victoire
    $pdo->exec("INSERT INTO Joueurs VALUES
        ('J1', 'Doe', 'John', 12345, 'Actif', '1990-05-10', 180, 75, 'Attaquant', TRUE),
        ('J2', 'Smith', 'Anna', 12346, 'Actif', '1992-11-02', 170, 60, 'Milieu', FALSE),
        ('J3', 'Brown', 'Chris', 12347, 'Actif', '1988-03-15', 185, 80, 'Défenseur', FALSE)
    ;");

    // Insertion de matchs
    // idMatch, dateMatch, heureMatch, nomEquipeAdverse, lieuRencontre, competition, scoreEquipe, victoire, matchNul, scoreEquipeAdverse
    $pdo->exec("INSERT INTO Matchs VALUES
        ('M1', '2024-01-15', '15:00:00', 'Équipe A', 'Stade Municipal', 'Championnat', 2, TRUE, FALSE, 1),
        ('M2', '2024-01-20', '18:30:00', 'Équipe B', 'Stade Jean Moulin', 'Championnat', 1, FALSE, TRUE, 1),
        ('M3', '2024-01-25', '20:00:00', 'Équipe C', 'Stade Pierre de Coubertin', 'Coupe', 0, FALSE, FALSE, 2)
    ;");

    // Insertion de participations
    // idJoueur, idMatch, evaluation, titulaire, poste, nbRemplacements, commentaire
    $pdo->exec("INSERT INTO Participer VALUES
        ('J1', 'M1', 8, TRUE, 'Attaquant', 0, 'Bon match'),
        ('J2', 'M1', 6, FALSE, 'Milieu', 1, 'A fait une passe décisive'),
        ('J3', 'M1', 7, TRUE, 'Défenseur', 0, 'Solide en défense'),

        ('J1', 'M2', 5, TRUE, 'Attaquant', 0, 'Doit améliorer ses tirs'),
        ('J2', 'M2', 7, TRUE, 'Milieu', 0, 'Contrôle du milieu correct'),
        ('J3', 'M2', 6, FALSE, 'Défenseur', 1, 'Remplace en seconde mi-temps'),

        ('J1', 'M3', 4, TRUE, 'Attaquant', 0, 'Match difficile'),
        ('J2', 'M3', 5, TRUE, 'Milieu', 0, 'Peu d’impact'),
        ('J3', 'M3', 6, TRUE, 'Défenseur', 0, 'A tenté de défendre son équipe')
    ;");

    echo "Données insérées avec succès.";

} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
