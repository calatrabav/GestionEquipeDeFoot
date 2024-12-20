<?php
class MatchModel {
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM Matchs ORDER BY dateMatch ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM Matchs WHERE idMatch=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getFuturs() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM Matchs WHERE dateMatch >= CURDATE() ORDER BY dateMatch ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO Matchs (idMatch, dateMatch, heureMatch, nomEquipeAdverse, lieuRencontre, competition, scoreEquipe, scoreEquipeAdverse, victoire, matchNul)
        VALUES (?, ?, ?, ?, ?, ?, NULL, NULL, 0, 0)");
        $stmt->execute([
            $data['idMatch'],
            $data['dateMatch'],
            $data['heureMatch'],
            $data['nomEquipeAdverse'],
            $data['lieuRencontre'],
            $data['competition']
        ]);
    }

    public static function update($id, $data) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE Matchs SET dateMatch=?, heureMatch=?, nomEquipeAdverse=?, lieuRencontre=?, competition=? WHERE idMatch=?");
        $stmt->execute([
            $data['dateMatch'],
            $data['heureMatch'],
            $data['nomEquipeAdverse'],
            $data['lieuRencontre'],
            $data['competition'],
            $id
        ]);
    }

    public static function delete($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM Matchs WHERE idMatch=?");
        $stmt->execute([$id]);
    }

    public static function updateScore($idMatch, $scoreEquipe, $scoreEquipeAdverse) {
        global $pdo;
        // Déterminer victoire, matchNul
        $victoire = 0;
        $matchNul = 0;
        if ($scoreEquipe > $scoreEquipeAdverse) {
            $victoire = 1;
        } elseif ($scoreEquipe == $scoreEquipeAdverse) {
            $matchNul = 1;
        }
        // Si pas victoire ni nul, c'est défaite
        // On n'a pas de colonne pour défaite, juste victoire=0 et matchNul=0.

        $stmt = $pdo->prepare("UPDATE Matchs SET scoreEquipe=?, scoreEquipeAdverse=?, victoire=?, matchNul=? WHERE idMatch=?");
        $stmt->execute([$scoreEquipe, $scoreEquipeAdverse, $victoire, $matchNul, $idMatch]);
    }

    public static function getGlobalStats() {
        global $pdo;
        // Victoires = matches où victoire=1
        // Nuls = matches où matchNul=1
        // Défaites = total - victoires - nuls
        // On ne considère que les matchs terminés (scoreEquipe IS NOT NULL)
        $stmt = $pdo->query("SELECT
            SUM(CASE WHEN victoire=1 THEN 1 ELSE 0 END) AS victoires,
            SUM(CASE WHEN matchNul=1 THEN 1 ELSE 0 END) AS nuls,
            COUNT(*) AS total
        FROM Matchs
        WHERE scoreEquipe IS NOT NULL AND scoreEquipeAdverse IS NOT NULL");
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        // Calcul des défaites
        $defaites = $res['total'] - $res['victoires'] - $res['nuls'];

        // On ajoute cette info dans $res
        $res['defaites'] = $defaites;
        return $res;
    }

    public static function getPlayerStats($idJoueur) {
        global $pdo;
        // On compte les matchs auxquels le joueur a participé.
        // On utilise victoire et matchNul pour déterminer le type de résultat.
        $stmt = $pdo->prepare("SELECT 
            COUNT(*) AS totalMatches,
            AVG(evaluation) AS avgEval,
            SUM(CASE WHEN titulaire=1 THEN 1 ELSE 0 END) AS titulaireCount,
            SUM(CASE WHEN titulaire=0 THEN 1 ELSE 0 END) AS remplacantCount,
            SUM(CASE WHEN m.victoire=1 THEN 1 ELSE 0 END) AS wins,
            SUM(CASE WHEN m.matchNul=1 THEN 1 ELSE 0 END) AS draws
        FROM Participer p
        JOIN Matchs m ON p.idMatch=m.idMatch
        WHERE p.idJoueur=? 
          AND m.scoreEquipe IS NOT NULL 
          AND m.scoreEquipeAdverse IS NOT NULL");
        $stmt->execute([$idJoueur]);
        $s = $stmt->fetch(PDO::FETCH_ASSOC);

        // Calcul des défaites
        $losses = $s['totalMatches'] - $s['wins'] - $s['draws'];
        $s['losses'] = $losses;
        return $s;
    }
}
