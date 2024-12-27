<?php
class ParticiperModel {
    public static function getParticipants($idMatch) {
        global $pdo;
        $stmt = $pdo->prepare("
        SELECT 
            p.idJoueur,
            j.nomJoueur,
            j.prenomJoueur,
            p.titulaire,
            p.poste,
            p.commentaire
        FROM Participer p
        JOIN Joueurs j ON p.idJoueur = j.idJoueur
        WHERE p.idMatch = ?
    ");
        $stmt->execute([$idMatch]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function hasParticipants($idMatch) {
        global $pdo;
        $query = "SELECT COUNT(*) AS count FROM Participer WHERE idMatch = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$idMatch]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0; // Retourne true si des joueurs sont associés au match
    }

    public static function updateParticipant($idMatch, $idJoueur, $poste, $commentaire) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE Participer SET poste = ?, commentaire = ? WHERE idMatch = ? AND idJoueur = ?");
        $stmt->execute([$poste, $commentaire, $idMatch, $idJoueur]);
    }

    public static function deleteParticipant($idMatch, $idJoueur) {
        // Code pour supprimer le joueur de la table "Participer"
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM Participer WHERE idMatch = ? AND idJoueur = ?");
        $stmt->execute([$idMatch, $idJoueur]);
    }

    public static function getParticipantById($idMatch, $idJoueur) {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT * FROM Participer 
            WHERE idMatch = ? AND idJoueur = ?
        ");
        $stmt->execute([$idMatch, $idJoueur]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public static function insertParticipant($idMatch, $idJoueur, $titulaire, $poste, $commentaire) {
        global $pdo;

        // Vérifie si le joueur est déjà inscrit pour ce match
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM Participer WHERE idJoueur = ? AND idMatch = ?");
        $stmt->execute([$idJoueur, $idMatch]);
        $count = $stmt->fetchColumn();

        // Si une ligne existe déjà, ne rien insérer
        if ($count > 0) {
            return; // Le joueur est déjà inscrit pour ce match
        }

        // Sinon, insère le joueur dans la table Participer
        $stmt = $pdo->prepare("INSERT INTO Participer (idJoueur, idMatch, evaluation, titulaire, poste, commentaire) VALUES (?, ?, NULL, ?, ?, ?)");
        $stmt->execute([$idJoueur, $idMatch, $titulaire, $poste, $commentaire]);
    }

    public static function updateEvaluation($idMatch, $idJoueur, $evaluation) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE Participer SET evaluation=? WHERE idJoueur=? AND idMatch=?");
        $stmt->execute([$evaluation, $idJoueur, $idMatch]);
    }

    public static function deleteParticipants($idMatch){
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM Participer WHERE idMatch=?");
        $stmt->execute([$idMatch]);
    }
    public static function getConsecutiveMatchCount($idJoueur) {
        global $pdo;
        $stmt = $pdo->prepare("
        WITH MatchsJoueur AS (
            SELECT 
                p.idJoueur,
                p.idMatch,
                m.dateMatch,
                ROW_NUMBER() OVER (PARTITION BY p.idJoueur ORDER BY m.dateMatch) AS row_num
            FROM Participer p
            JOIN Matchs m ON p.idMatch = m.idMatch
            WHERE p.titulaire = TRUE
              AND p.idJoueur = ?
        ),
        MatchsConsecutifs AS (
            SELECT 
                idJoueur,
                idMatch,
                dateMatch,
                row_num - ROW_NUMBER() OVER (PARTITION BY idJoueur ORDER BY dateMatch) AS grp
            FROM MatchsJoueur
        )
        SELECT 
            idJoueur,
            COUNT(*) AS nbMatchsConsecutifs
        FROM MatchsConsecutifs
        GROUP BY idJoueur, grp
        ORDER BY idJoueur, nbMatchsConsecutifs DESC
    ");

        $stmt->execute([$idJoueur]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['nbMatchsConsecutifs'] : 0; // Si aucun résultat, retourne 0
    }
}
