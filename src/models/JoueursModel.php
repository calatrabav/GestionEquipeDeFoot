<?php
class JoueursModel {
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM Joueurs ORDER BY idJoueur ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM Joueurs WHERE idJoueur = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        global $pdo;
        // On insère tous les champs, `idJoueur` doit être fourni ou généré.
        // On supposera que l'utilisateur saisi un idJoueur unique ou qu'on génère un identifiant.
        $stmt = $pdo->prepare("INSERT INTO Joueurs (idJoueur, nomJoueur, prenomJoueur, numLicence, statut, dateNaissanceJoueur, tailleJoueur, poidsJoueur, postePrincipal, victoire) 
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
            isset($data['victoire']) ? 1 : 0
        ]);
        return $pdo->lastInsertId(); // ou retourner true, selon les besoins
    }

    public static function update($id, $data) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE Joueurs SET nomJoueur = ?, prenomJoueur = ?, numLicence = ?, statut = ?, dateNaissanceJoueur = ?, tailleJoueur = ?, poidsJoueur = ?, postePrincipal = ?, victoire = ? WHERE idJoueur = ?");
        $stmt->execute([
            $data['nomJoueur'],
            $data['prenomJoueur'],
            $data['numLicence'],
            $data['statut'],
            $data['dateNaissanceJoueur'],
            $data['tailleJoueur'],
            $data['poidsJoueur'],
            $data['postePrincipal'],
            isset($data['victoire']) ? 1 : 0,
            $id
        ]);
        return $stmt->rowCount() > 0;
    }

    public static function delete($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM Joueurs WHERE idJoueur = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
