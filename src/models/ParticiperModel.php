<?php
class participerModel {
    public static function getParticipants($idMatch) {
        global $pdo;
        $query = "
        SELECT 
            p.idJoueur,
            j.nomJoueur,
            j.prenomJoueur,
            p.titulaire,
            p.poste,
            p.commentaire
        FROM participer p
        JOIN Joueurs j ON p.idJoueur = j.idJoueur
        WHERE p.idMatch = ?
    ";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$idMatch]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function hasParticipants($idMatch) {
        global $pdo;
        $query = "SELECT COUNT(*) AS count FROM participer WHERE idMatch = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$idMatch]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0; // Retourne true si des joueurs sont associés au match
    }

    public static function updateParticipant($idMatch, $idJoueur, $titulaire, $poste, $commentaire) {
        global $pdo;
        $query = "UPDATE participer SET titulaire = ?, poste = ?, commentaire = ? WHERE idMatch = ? AND idJoueur = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$titulaire, $poste, $commentaire, $idMatch, $idJoueur]);
    }

    public static function deleteParticipant($idMatch, $idJoueur) {
        // Code pour supprimer le joueur de la table "participer"
        global $pdo;
        $query = "DELETE FROM participer WHERE idMatch = ? AND idJoueur = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$idMatch, $idJoueur]);
    }

    public static function getParticipantById($idMatch, $idJoueur) {
        global $pdo;
        $query = "SELECT * FROM participer 
            WHERE idMatch = ? AND idJoueur = ?
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$idMatch, $idJoueur]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getNonParticipants($idMatch) {
        global $pdo;
        // Requête pour sélectionner les joueurs qui ne sont pas dans participer pour ce match
        $sql = "
            SELECT j.idJoueur, nomJoueur, prenomJoueur
            FROM Joueurs j
            WHERE j.statut = 'Actif'
              AND NOT EXISTS (
                SELECT *
                FROM participer p 
                WHERE p.idJoueur = j.idJoueur AND p.idMatch = ?
              )
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idMatch]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function deleteAllParticipants($idMatch)
    {
        global $pdo;
        $query = "DELETE FROM participer WHERE idMatch = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$idMatch]);
    }

    public static function replaceParticipant($idMatch, $idJoueurARemplacer, $idJoueur, $titulaire, $poste, $commentaire) {
        global $pdo;

        // Vérifier si le joueur à ajouter ne participe pas déjà au match
        $sql = "SELECT COUNT(*) as count FROM participer WHERE idMatch = ? AND idJoueur = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idMatch, $idJoueur]);
        $result = $stmt->fetch();

        if ($result['count'] > 0) {
            throw new Exception("Le joueur sélectionné participe déjà à ce match.");
        }

        // Supprimer le joueur existant
        $sql = "DELETE FROM participer WHERE idMatch = ? AND idJoueur = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idMatch, $idJoueurARemplacer]);

        // Ajouter le nouveau joueur
        $sql = "INSERT INTO participer (idMatch, idJoueur, titulaire, poste, commentaire) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idMatch, $idJoueur, $titulaire, $poste, $commentaire]);

    }

    public static function insertParticipant($idMatch, $idJoueur, $titulaire, $poste, $commentaire) {
        global $pdo;

        // Vérifie si le joueur est déjà inscrit pour ce match
        $query = "SELECT COUNT(*) FROM participer WHERE idJoueur = ? AND idMatch = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$idJoueur, $idMatch]);
        $count = $stmt->fetchColumn();

        // Si une ligne existe déjà, ne rien insérer
        if ($count > 0) {
            return; // Le joueur est déjà inscrit pour ce match
        }

        // Sinon, insère le joueur dans la table participer
        $query = "INSERT INTO participer (idJoueur, idMatch, evaluation, titulaire, poste, commentaire) VALUES (?, ?, NULL, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$idJoueur, $idMatch, $titulaire, $poste, $commentaire]);
    }

    public static function updateEvaluation($idMatch, $idJoueur, $evaluation) {
        global $pdo;
        $query = "UPDATE participer SET evaluation=? WHERE idJoueur=? AND idMatch=?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$evaluation, $idJoueur, $idMatch]);
    }

    public static function deleteParticipants($idMatch){
        global $pdo;
        $query = "DELETE FROM participer WHERE idMatch=?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$idMatch]);
    }

    public static function getConsecutiveMatchCount($idJoueur) {
        global $pdo;
        $query = "
        WITH MatchsJoueur AS (
            SELECT 
                p.idJoueur,
                p.idMatch,
                m.dateMatch,
                ROW_NUMBER() OVER (PARTITION BY p.idJoueur ORDER BY m.dateMatch) AS row_num
            FROM participer p
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
    ";
        $stmt = $pdo->prepare($query);

        $stmt->execute([$idJoueur]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['nbMatchsConsecutifs'] : 0; // Si aucun résultat, retourne 0
    }
}
