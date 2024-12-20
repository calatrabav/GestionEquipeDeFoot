<?php require_once __DIR__ . "/../layout/header.php"; ?>

<h2>Feuille de Match</h2>
<form method="POST">
    <label>Match à venir :</label>
    <select name="idMatch" required>
        <?php foreach($matchsAVenir as $m): ?>
            <option value="<?= htmlspecialchars($m['idMatch']) ?>"><?= htmlspecialchars($m['dateMatch']." vs ".$m['nomEquipeAdverse']) ?></option>
        <?php endforeach; ?>
    </select>
    <br><br>

    <h3>Joueurs Actifs</h3>
    <table>
        <tr>
            <th>Sélection</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Taille</th>
            <th>Poids</th>
            <th>Poste Principal</th>
            <th>Commentaire Joueur</th>
            <th>Titulaire ?</th>
            <th>Poste sur ce match</th>
            <th>Commentaire Staff</th>
        </tr>
        <?php foreach($joueursActifs as $j): ?>
        <tr>
            <td><input type="checkbox" name="selection[<?= $j['idJoueur'] ?>]"></td>
            <td><?= htmlspecialchars($j['nomJoueur']) ?></td>
            <td><?= htmlspecialchars($j['prenomJoueur']) ?></td>
            <td><?= htmlspecialchars($j['tailleJoueur']) ?></td>
            <td><?= htmlspecialchars($j['poidsJoueur']) ?></td>
            <td><?= htmlspecialchars($j['postePrincipal']) ?></td>
            <td><?= htmlspecialchars($j['commentaire']) ?></td>
            <td><input type="checkbox" name="selection[<?= $j['idJoueur'] ?>][titulaire]"></td>
            <td><input type="text" name="selection[<?= $j['idJoueur'] ?>][poste]"></td>
            <td><input type="text" name="selection[<?= $j['idJoueur'] ?>][commentaire]"></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <button type="submit" class="btn">Valider la sélection</button>
</form>

<?php require_once __DIR__ . "/../layout/footer.php"; ?>
