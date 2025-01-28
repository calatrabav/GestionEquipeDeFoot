<?php
class MatchModel {
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM matchs ORDER BY dateMatch ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM matchs WHERE idMatch=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getFuturs() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM matchs WHERE dateMatch >= CURDATE() ORDER BY dateMatch ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO matchs (idMatch, dateMatch, heureMatch, nomEquipeAdverse, lieuRencontre, competition, scoreEquipe, scoreEquipeAdverse, victoire, matchNul)
    VALUES (?, ?, ?, ?, ?, ?, NULL, NULL, 0, 0)");
        $stmt->execute([
            $data['idMatch'],
            $data['dateMatch'],
            $data['heureMatch'],
            $data['nomEquipeAdverse'],
            $data['lieuRencontre'], // La valeur de 'lieuRencontre' est déjà récupérée du formulaire
            $data['competition']
        ]);
    }


    public static function update($id, $data) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE matchs SET dateMatch=?, heureMatch=?, nomEquipeAdverse=?, lieuRencontre=?, competition=? WHERE idMatch=?");
        $stmt->execute([
            $data['dateMatch'],
            $data['heureMatch'],
            $data['nomEquipeAdverse'],
            $data['lieuRencontre'], // Mise à jour du 'lieuRencontre'
            $data['competition'],
            $id
        ]);
    }


    public static function delete($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM matchs WHERE idMatch=?");
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

        $stmt = $pdo->prepare("UPDATE matchs SET scoreEquipe=?, scoreEquipeAdverse=?, victoire=?, matchNul=? WHERE idMatch=?");
        $stmt->execute([$scoreEquipe, $scoreEquipeAdverse, $victoire, $matchNul, $idMatch]);
    }

    public static function getPlayerStats($idJoueur) {
        global $pdo;
    
        $sql = "
        SELECT
          COUNT(*) AS totalMatches, 
          SUM(CASE WHEN m.victoire = 1 THEN 1 ELSE 0 END) AS wins,
          SUM(CASE WHEN m.matchNul = 1 THEN 1 ELSE 0 END) AS draws,
          SUM(CASE WHEN p.titulaire=1 THEN 1 ELSE 0 END) AS titulaireCount,
          SUM(CASE WHEN p.titulaire=0 THEN 1 ELSE 0 END) AS remplacantCount,
    
          COUNT(p.evaluation) AS nbMatchsEval,  -- nb lignes où evaluation n'est PAS NULL
          AVG(p.evaluation)   AS avgEval        -- moyenne sur ces lignes
        FROM participer p
        JOIN matchs m ON p.idMatch = m.idMatch
        WHERE p.idJoueur = ?
        ";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idJoueur]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$res) {
            // Si aucun match trouvé, on renvoie un tableau vide ou par défaut
            return [
                'totalMatches' => 0,
                'wins' => 0,
                'draws' => 0,
                'titulaireCount' => 0,
                'remplacantCount' => 0,
                'nbMatchsEval' => 0,
                'avgEval' => null,
                'losses' => 0
            ];
        }
    
        // Calcul des défaites = totalMatches - wins - draws
        $defaites = $res['totalMatches'] - $res['wins'] - $res['draws'];
        $res['losses'] = $defaites;
    
        return $res;
    }
    
    

    public static function getGlobalStats()
{
    global $pdo;
    // Victoire = match où victoire=1
    // Nul = match où matchNul=1
    // Défaite = total - victoires - nuls
    // On ne considère que les matchs terminés (scoreEquipe IS NOT NULL)
    // si tu gères le score dans la table Matchs.
    $stmt = $pdo->query("SELECT
        SUM(CASE WHEN victoire = 1 THEN 1 ELSE 0 END) AS victoires,
        SUM(CASE WHEN matchNul = 1 THEN 1 ELSE 0 END) AS nuls,
        COUNT(*) AS total
    FROM matchs
    WHERE scoreEquipe IS NOT NULL
      AND scoreEquipeAdverse IS NOT NULL");

    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$res) {
        return [
            'victoires' => 0,
            'nuls' => 0,
            'defaites' => 0,
            'total' => 0
        ];
    }

    // Calcul des défaites
    $defaites = $res['total'] - $res['victoires'] - $res['nuls'];
    $res['defaites'] = $defaites;

    return $res;  // On retourne un tableau [victoires, nuls, defaites, total]
}

    
}