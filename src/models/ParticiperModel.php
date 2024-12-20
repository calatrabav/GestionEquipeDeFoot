<?php
class ParticiperModel {
    public static function getParticipants($idMatch) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT p.*, j.nomJoueur, j.prenomJoueur, j.postePrincipal, j.statut, j.tailleJoueur, j.poidsJoueur, j.commentaire AS commentaireJoueur
                               FROM Participer p
                               JOIN Joueurs j ON p.idJoueur=j.idJoueur
                               WHERE p.idMatch=?");
        $stmt->execute([$idMatch]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function insertParticipant($idMatch, $idJoueur, $titulaire, $poste, $commentaire) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO Participer (idJoueur, idMatch, evaluation, titulaire, poste, commentaire) VALUES (?, ?, NULL, ?, ?, ?)");
        $stmt->execute([$idJoueur, $idMatch, $titulaire, $poste, $commentaire]);
    }

    public static function updateEvaluation($idMatch, $idJoueur, $evaluation) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE Participer SET evaluation=? WHERE idJoueur=? AND idMatch=?");
        $stmt->execute([$evaluation, $idJoueur, $idMatch]);
    }

    public static function deleteAllForMatch($idMatch) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM Participer WHERE idMatch=?");
        $stmt->execute([$idMatch]);
    }
}
