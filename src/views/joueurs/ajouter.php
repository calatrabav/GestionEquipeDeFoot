<?php require_once __DIR__ . "/../layout/header.php"; ?>

<h2>Ajouter un joueur</h2>
<form action="index.php?controller=joueurs&action=ajouter" method="POST" style="width:300px;margin:0 auto;">
    <label>ID Joueur :</label>
    <input type="text" name="idJoueur" required><br><br>

    <label>Nom :</label>
    <input type="text" name="nomJoueur" required><br><br>

    <label>Prénom :</label>
    <input type="text" name="prenomJoueur" required><br><br>

    <label>Num Licence :</label>
    <input type="number" name="numLicence" required><br><br>

    <label>Statut :</label>
    <select name="statut">
        <option value="Actif">Actif</option>
        <option value="Blessé">Blessé</option>
        <option value="Suspendu">Suspendu</option>
        <option value="Absent">Absent</option>
    </select><br><br>

    <label>Date Naissance :</label>
    <input type="date" name="dateNaissanceJoueur"><br><br>

    <label>Taille (cm) :</label>
    <input type="number" name="tailleJoueur"><br><br>

    <label>Poids (kg) :</label>
    <input type="number" name="poidsJoueur"><br><br>

    <label>Poste Principal :</label>
    <input type="text" name="postePrincipal"><br><br>

    <label>Commentaire :</label>
    <textarea name="commentaire"></textarea><br><br>

    <button type="submit" class="btn">Ajouter</button>
</form>

<?php require_once __DIR__ . "/../layout/footer.php"; ?>
