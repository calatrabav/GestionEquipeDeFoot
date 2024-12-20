<?php
class JoueursModel {
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM Joueurs ORDER BY idJoueur ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM Joueurs WHERE idJoueur=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO Joueurs (idJoueur, nomJoueur, prenomJoueur, numLicence, statut, dateNaissanceJoueur, tailleJoueur, poidsJoueur, postePrincipal, commentaire) 
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
        $stmt = $pdo->prepare("UPDATE Joueurs SET nomJoueur=?, prenomJoueur=?, numLicence=?, statut=?, dateNaissanceJoueur=?, tailleJoueur=?, poidsJoueur=?, postePrincipal=?, commentaire=? WHERE idJoueur=?");
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
        $stmt = $pdo->prepare("DELETE FROM Joueurs WHERE idJoueur=?");
        $stmt->execute([$id]);
    }

    public static function getActifs() {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM Joueurs WHERE statut='Actif' ORDER BY nomJoueur ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
