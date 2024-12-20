<?php require_once __DIR__ . "/../layout/header.php"; ?>

<h2>Liste des matchs</h2>
<table>
    <tr style="background:#eee;">
        <th>ID Match</th>
        <th>Date</th>
        <th>Heure</th>
        <th>Équipe Adverse</th>
        <th>Lieu</th>
        <th>Compétition</th>
        <th>Score Équipe</th>
        <th>Score Adverse</th>
        <th>Victoire</th>
        <th>Match Nul</th>
        <th>Actions</th>
    </tr>
    <?php foreach($matchs as $m): ?>
    <tr>
        <td><?= htmlspecialchars($m['idMatch']) ?></td>
        <td><?= htmlspecialchars($m['dateMatch']) ?></td>
        <td><?= htmlspecialchars($m['heureMatch']) ?></td>
        <td><?= htmlspecialchars($m['nomEquipeAdverse']) ?></td>
        <td><?= htmlspecialchars($m['lieuRencontre']) ?></td>
        <td><?= htmlspecialchars($m['competition']) ?></td>
        <td><?= $m['scoreEquipe'] !== null ? htmlspecialchars($m['scoreEquipe']) : '-' ?></td>
        <td><?= $m['scoreEquipeAdverse'] !== null ? htmlspecialchars($m['scoreEquipeAdverse']) : '-' ?></td>
        <td><?= $m['victoire'] == 1 ? 'Oui' : 'Non' ?></td>
        <td><?= $m['matchNul'] == 1 ? 'Oui' : 'Non' ?></td>
        <td>
            <a class="btn" href="index.php?controller=matchs&action=modifier&id=<?= urlencode($m['idMatch']) ?>">Modifier</a>
            <a class="btn" style="background:red;" href="index.php?controller=matchs&action=supprimer&id=<?= urlencode($m['idMatch']) ?>">Supprimer</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<br>
<a class="btn" href="index.php?controller=matchs&action=ajouter">Ajouter un match</a>

<?php require_once __DIR__ . "/../layout/footer.php"; ?>
