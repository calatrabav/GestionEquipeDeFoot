<?php require_once __DIR__ . "/../layout/header.php"; ?>

<h2>Statistiques (Notes de 1 à 10)</h2>

<?php
// Récupération des données globales
$victoires = $statsGlobales['victoires'];
$nuls = $statsGlobales['nuls'];
$defaites = $statsGlobales['defaites'];
$total = $statsGlobales['total'];
?>

<h3>Matchs globaux</h3>
<p>Victoires : <?= $victoires ?> (<?= $total > 0 ? round(($victoires / $total) * 100, 2) : 0 ?>%)</p>
<p>Nuls : <?= $nuls ?> (<?= $total > 0 ? round(($nuls / $total) * 100, 2) : 0 ?>%)</p>
<p>Défaites : <?= $defaites ?> (<?= $total > 0 ? round(($defaites / $total) * 100, 2) : 0 ?>%)</p>

<h3>Statistiques par Joueur</h3>
<table>
    <tr>
        <th>Joueur</th>
        <th>Statut</th>
        <th>Poste principal</th>
        <th>Titularisations</th>
        <th>Remplacements</th>
        <th>Moy. Évaluation (sur 10)</th>
        <th>% Matchs Gagnés (avec lui)</th>
        <th>Matchs Consécutifs</th>
    </tr>
    <?php foreach ($joueurs as $j): 
        $s = $statsJoueurs[$j['idJoueur']]; // Les stats calculées pour ce joueur
        //$totalMatches = $s['totalMatches'];
        $wins = $s['wins'];
        $avgEval = $s['avgEval'];
        $winPercent = $totalMatches > 0 ? round(($wins / $totalMatches) * 100, 2) : 0;
        $matchsConsecutifs = $s['matchs_consecutifs'];
    ?>
    <tr>
        <td><?= htmlspecialchars($j['nomJoueur'] . " " . $j['prenomJoueur']) ?></td>
        <td><?= htmlspecialchars($j['statut']) ?></td>
        <td><?= htmlspecialchars($j['postePrincipal']) ?></td>
        <td><?= $s['titulaireCount'] ?></td>
        <td><?= $s['remplacantCount'] ?></td>
        <!-- Si avgEval = null, on affiche '-' -->
        <td><?= $avgEval !== null ? round($avgEval, 2) : '-' ?></td>
        <td><?= $winPercent ?>%</td>
        <td><?= $matchsConsecutifs ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<?php require_once __DIR__ . "/../layout/footer.php"; ?>
