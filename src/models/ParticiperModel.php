<?php
class ParticiperModel {
    /**
     * Récupère tous les participants (joueurs) pour un match donné.
     */
    public static function getParticipants($idMatch) {
        global $pdo;
        // Note: On doit entourer `evaluation` de backticks si c'est le nom exact dans la table
        $query = "
            SELECT 
                p.idJoueur,
                j.nomJoueur,
                j.prenomJoueur,
                p.titulaire,
                p.poste,
                p.commentaire,
                p.evaluation
            FROM participer p
            JOIN joueurs j ON p.idJoueur = j.idJoueur
            WHERE p.idMatch = ?
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$idMatch]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**eval
     * Vérifie si un match a déjà des participants.
     */
    public static function hasParticipants($idMatch) {
        global $pdo;
        $query = "SELECT COUNT(*) AS count FROM participer WHERE idMatch = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$idMatch]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    /**
     * Met à jour les infos d'un participant (titulaire, poste, commentaire).
     * Ne gère pas encore l'evaluation ici (possible de le faire).
     */
    public static function updateParticipant($idMatch, $idJoueur, $titulaire, $poste, $commentaire, $evaluation) {
        global $pdo;
        $query = "UPDATE participer 
                  SET titulaire=?, poste=?, commentaire=?, `evaluation`=? 
                  WHERE idMatch=? AND idJoueur=?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$titulaire, $poste, $commentaire, $evaluation, $idMatch, $idJoueur]);
    }

    /**
     * Supprime un participant pour un match donné.
     */
    public static function deleteParticipant($idMatch, $idJoueur) {
        global $pdo;
        $query = "DELETE FROM participer WHERE idMatch = ? AND idJoueur = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$idMatch, $idJoueur]);
    }

    /**
     * Récupère un participant précis (idMatch + idJoueur).
     */
    public static function getParticipantById($idMatch, $idJoueur) {
        global $pdo;
        $query = "SELECT * FROM participer
                  WHERE idMatch = ? AND idJoueur = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$idMatch, $idJoueur]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les joueurs actifs qui ne participent pas encore à ce match.
     */
    public static function getNonParticipants($idMatch) {
        global $pdo;
        $sql = "
            SELECT j.idJoueur, j.nomJoueur, j.prenomJoueur
            FROM joueurs j
            WHERE j.statut = 'Actif'
              AND NOT EXISTS (
                SELECT 1
                FROM participer p 
                WHERE p.idJoueur = j.idJoueur AND p.idMatch = ?
              )
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idMatch]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Supprime tous les participants pour un match.
     */
    public static function deleteAllParticipants($idMatch) {
        global $pdo;
        $query = "DELETE FROM participer WHERE idMatch = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$idMatch]);
    }

    /**
     * Remplace un joueur par un autre (dans la table participer).
     */
    public static function replaceParticipant($idMatch, $idJoueurARemplacer, $idJoueur, $titulaire, $poste, $commentaire) {
        global $pdo;
        // Vérifie si le nouveau joueur n'est pas déjà dans le match
        $sql = "SELECT COUNT(*) as count FROM participer WHERE idMatch = ? AND idJoueur = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idMatch, $idJoueur]);
        $result = $stmt->fetch();

        if ($result['count'] > 0) {
            throw new Exception("Le joueur sélectionné participe déjà à ce match.");
        }

        // Supprime l'ancien joueur
        $sql = "DELETE FROM participer WHERE idMatch = ? AND idJoueur = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idMatch, $idJoueurARemplacer]);

        // Ajoute le nouveau joueur
        $sql = "INSERT INTO participer (idMatch, idJoueur, `evaluation`, titulaire, poste, commentaire) 
                VALUES (?, ?, NULL, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idMatch, $idJoueur, $titulaire, $poste, $commentaire]);
    }

    /**
     * Insère un participant si pas déjà présent. (evaluation initialisée à NULL)
     */
    public static function insertParticipant($idMatch, $idJoueur, $titulaire, $poste, $commentaire) {
        global $pdo;
        // Vérifie si déjà inscrit
        $query = "SELECT COUNT(*) FROM participer WHERE idJoueur = ? AND idMatch = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$idJoueur, $idMatch]);
        $count = $stmt->fetchColumn();

        if ($count > 0) {
            // déjà inscrit
            return;
        }

        // Sinon insertion
        $query = "INSERT INTO participer (idJoueur, idMatch, `evaluation`, titulaire, poste, commentaire)
                  VALUES (?, ?, NULL, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$idJoueur, $idMatch, $titulaire, $poste, $commentaire]);
    }

    /**
     * Met à jour l'evaluation (colonne `evaluation`) pour un participant.
     * Valeur attendue : 1 à 10.
     */
    public static function updateEvaluation($idMatch, $idJoueur, $evaluation) {
        global $pdo;
        // On protège `evaluation` par des backticks
        $query = "UPDATE participer SET `evaluation` = ? WHERE idMatch = ? AND idJoueur = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$evaluation, $idMatch, $idJoueur]);
    }

    /**
     * Récupère le nombre de matchs consécutifs (exemple de stats).
     */
    public static function getConsecutiveMatchCount($idJoueur) {
        global $pdo;
        // Cette requête est un exemple, elle dépend de ta logique
        $query = "
        WITH MatchsJoueur AS (
            SELECT 
                p.idJoueur,
                p.idMatch,
                m.dateMatch,
                ROW_NUMBER() OVER (PARTITION BY p.idJoueur ORDER BY m.dateMatch) AS row_num
            FROM participer p
            JOIN matchs m ON p.idMatch = m.idMatch
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
        ORDER BY nbMatchsConsecutifs DESC
        LIMIT 1;
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$idJoueur]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result['nbMatchsConsecutifs'] : 0;
    }
}
