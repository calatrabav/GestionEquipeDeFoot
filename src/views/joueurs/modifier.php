<?php require_once __DIR__ ."/../layout/header.php"; ?>

<h2>Modifier un joueur</h2>

<?php if (isset($joueur)): ?>
<form action="index.php?controller=joueurs&action=modifier&id=<?= urlencode($joueur['idJoueur']) ?>" method="POST" style="width:300px;margin:0 auto;">
    <label>ID Joueur (non modifiable) :</label>
    <input type="text" value="<?= htmlspecialchars($joueur['idJoueur']) ?>" disabled><br><br>

    <label>Nom :</label>
    <input type="text" name="nomJoueur" value="<?= htmlspecialchars($joueur['nomJoueur']) ?>" required><br><br>

    <label>Prénom :</label>
    <input type="text" name="prenomJoueur" value="<?= htmlspecialchars($joueur['prenomJoueur']) ?>" required><br><br>

    <label>Num Licence :</label>
    <input type="number" name="numLicence" value="<?= htmlspecialchars($joueur['numLicence']) ?>" required><br><br>

    <label>Statut :</label>
    <select name="statut">
        <option value="Actif" <?= $joueur['statut']=='Actif'?'selected':'' ?>>Actif</option>
        <option value="Blessé" <?= $joueur['statut']=='Blessé'?'selected':'' ?>>Blessé</option>
        <option value="Suspendu" <?= $joueur['statut']=='Suspendu'?'selected':'' ?>>Suspendu</option>
        <option value="Absent" <?= $joueur['statut']=='Absent'?'selected':'' ?>>Absent</option>
    </select><br><br>

    <label>Date Naissance :</label>
    <input type="date" name="dateNaissanceJoueur" value="<?= htmlspecialchars($joueur['dateNaissanceJoueur']) ?>"><br><br>

    <label>Taille (cm) :</label>
    <input type="number" name="tailleJoueur" value="<?= htmlspecialchars($joueur['tailleJoueur']) ?>"><br><br>

    <label>Poids (kg) :</label>
    <input type="number" name="poidsJoueur" value="<?= htmlspecialchars($joueur['poidsJoueur']) ?>"><br><br>

    <label>Poste Principal :</label>
    <input type="text" name="postePrincipal" value="<?= htmlspecialchars($joueur['postePrincipal']) ?>"><br><br>

    <label>Commentaire :</label>
    <textarea name="commentaire"><?= htmlspecialchars($joueur['commentaire']) ?></textarea><br><br>

    <button type="submit" class="btn">Modifier</button>
</form>
<?php else: ?>
<p>Joueur introuvable.</p>
<?php endif; ?>

<?php require_once __DIR__ . "../layout/footer.php"; ?>
