<?php
class joueursModel {
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM joueurs ORDER BY idJoueur ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function getSelectedPlayers($idMatch) {
        global $pdo;

        // Requête SQL pour récupérer les joueurs sélectionnés pour le match
        $query = "
        SELECT j.idJoueur, j.nomJoueur, j.prenomJoueur, j.numLicence, p.poste, p.commentaire
        FROM joueurs j
        JOIN participer p ON j.idJoueur = p.idJoueur
        WHERE p.idMatch = ? 
    ";

        // Préparation et exécution de la requête avec le paramètre $idMatch
        $stmt = $pdo->prepare($query);
        $stmt->execute([$idMatch]);

        // Récupérer les résultats sous forme de tableau associatif
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function getNonParticipants($idMatch) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT * FROM joueurs 
            WHERE idJoueur NOT IN (SELECT idJoueur FROM participer WHERE idMatch = ?)
        ");
        $stmt->execute($idMatch);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM joueurs WHERE idJoueur=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO joueurs (idJoueur, nomJoueur, prenomJoueur, numLicence, statut, dateNaissanceJoueur, tailleJoueur, poidsJoueur, postePrincipal, commentaire) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['idJoueur'],
            $data['nomJoueur'],
            $data['prenomJoueur'],
            $data['numLicence'],
            $data['statut'],
            $data['dateNaissanceJoueur'],
            $data['tailleJoueur'],
            $data['poidsJoueur'],
            $data['postePrincipal'],
            $data['commentaire']
        ]);
    }

    public static function update($id, $data) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE joueurs SET nomJoueur=?, prenomJoueur=?, numLicence=?, statut=?, dateNaissanceJoueur=?, tailleJoueur=?, poidsJoueur=?, postePrincipal=?, commentaire=? WHERE idJoueur=?");
        $stmt->execute([
            $data['nomJoueur'],
            $data['prenomJoueur'],
            $data['numLicence'],
            $data['statut'],
            $data['dateNaissanceJoueur'],
            $data['tailleJoueur'],
            $data['poidsJoueur'],
            $data['postePrincipal'],
            $data['commentaire'],
            $id
        ]);
    }

    public static function delete($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM joueurs WHERE idJoueur=?");
        $stmt->execute([$id]);
    }

    public static function getActifs() {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM joueurs WHERE statut='Actif' ORDER BY nomJoueur ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
