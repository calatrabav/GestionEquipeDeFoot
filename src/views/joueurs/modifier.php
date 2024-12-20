<?php require_once __DIR__ . "/../layout/header.php"; ?>

<h2>Modifier un joueur</h2>
<?php if (isset($joueur)): ?>
<form action="index.php?controller=joueurs&action=modifier&id=<?= urlencode($joueur['idJoueur']) ?>" method="POST" style="max-width:400px; margin:0 auto;">
    <label>ID Joueur (non modifiable) : </label>
    <input type="text" value="<?= htmlspecialchars($joueur['idJoueur']) ?>" disabled style="width:100%; margin-bottom:10px;"><br>

    <label>Nom : </label>
    <input type="text" name="nomJoueur" value="<?= htmlspecialchars($joueur['nomJoueur']) ?>" required style="width:100%; margin-bottom:10px;"><br>

    <label>Prénom : </label>
    <input type="text" name="prenomJoueur" value="<?= htmlspecialchars($joueur['prenomJoueur']) ?>" required style="width:100%; margin-bottom:10px;"><br>

    <label>Num Licence : </label>
    <input type="number" name="numLicence" value="<?= htmlspecialchars($joueur['numLicence']) ?>" required style="width:100%; margin-bottom:10px;"><br>

    <label>Statut : </label>
    <input type="text" name="statut" value="<?= htmlspecialchars($joueur['statut']) ?>" style="width:100%; margin-bottom:10px;"><br>

    <label>Date Naissance : </label>
    <input type="date" name="dateNaissanceJoueur" value="<?= htmlspecialchars($joueur['dateNaissanceJoueur']) ?>" style="width:100%; margin-bottom:10px;"><br>

    <label>Taille (cm) : </label>
    <input type="number" name="tailleJoueur" value="<?= htmlspecialchars($joueur['tailleJoueur']) ?>" style="width:100%; margin-bottom:10px;"><br>

    <label>Poids (kg) : </label>
    <input type="number" name="poidsJoueur" value="<?= htmlspecialchars($joueur['poidsJoueur']) ?>" style="width:100%; margin-bottom:10px;"><br>

    <label>Poste Principal : </label>
    <input type="text" name="postePrincipal" value="<?= htmlspecialchars($joueur['postePrincipal']) ?>" style="width:100%; margin-bottom:10px;"><br>


    <button type="submit" class="btn">Modifier</button>
</form>
<?php else: ?>
<p>Joueur introuvable.</p>
<?php endif; ?>

<?php require_once __DIR__ . "/../layout/footer.php"; ?>
