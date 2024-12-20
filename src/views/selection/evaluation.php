<?php require_once __DIR__ . "/../layout/header.php"; ?>

<h2>Évaluation du match</h2>
<p>Match : <?= htmlspecialchars($match['dateMatch']." vs ".$match['nomEquipeAdverse']) ?></p>

<form method="POST">
    <label>Score Équipe :</label>
    <input type="number" name="scoreEquipe" value="<?= htmlspecialchars($match['scoreEquipe']) ?>"><br><br>

    <label>Score Équipe Adverse :</label>
    <input type="number" name="scoreEquipeAdverse" value="<?= htmlspecialchars($match['scoreEquipeAdverse']) ?>"><br><br>

    <h3>Évaluation des joueurs</h3>
    <table>
        <tr>
            <th>Joueur</th>
            <th>Titulaire</th>
            <th>Poste</th>
            <th>Évaluation (1 à 5)</th>
        </tr>
        <?php foreach($participants as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['nomJoueur']." ".$p['prenomJoueur']) ?></td>
            <td><?= $p['titulaire'] ? 'Oui' : 'Non' ?></td>
            <td><?= htmlspecialchars($p['poste']) ?></td>
            <td><input type="number" name="evaluation[<?= $p['idJoueur'] ?>]" min="1" max="5" value="<?= htmlspecialchars($p['evaluation']) ?>"></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <button class="btn" type="submit">Enregistrer</button>
</form>

<?php require_once __DIR__ . "/../layout/footer.php"; ?>
