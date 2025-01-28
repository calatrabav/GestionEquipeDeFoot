<?php require_once __DIR__ . "/../layout/header.php"; ?>

<h2>Évaluation du match</h2>
<?php if (isset($match)): ?>
    <p><strong>Match :</strong> <?= htmlspecialchars($match['dateMatch']." vs ".$match['nomEquipeAdverse']) ?></p>

    <form method="POST">
        <table style="width:100%; border-collapse: collapse;">
            <tr style="background:#eee;">
                <th>Joueur</th>
                <th>Titulaire</th>
                <th>Poste</th>
                <th>Évaluation (1 à 10)</th>
            </tr>
            <?php foreach($participants as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['nomJoueur']." ".$p['prenomJoueur']) ?></td>
                <td><?= $p['titulaire'] ? 'Oui' : 'Non' ?></td>
                <td><?= htmlspecialchars($p['poste']) ?></td>
                <td>
                    <!-- Valeur actuelle (si non NULL) -->
                    <input type="number" name="evaluation[<?= $p['idJoueur'] ?>]"
                           value="<?= $p['evaluation'] !== null ? htmlspecialchars($p['evaluation']) : '' ?>"
                           min="1" max="10">
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <br>
        <button type="submit" class="btn">Enregistrer</button>
    </form>
<?php else: ?>
    <p>Match introuvable.</p>
<?php endif; ?>

<?php require_once __DIR__ . "/../layout/footer.php"; ?>
