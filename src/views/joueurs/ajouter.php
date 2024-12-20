<?php require_once __DIR__ . "/../layout/header.php"; ?>

<h2>Ajouter un joueur</h2>
<form action="index.php?controller=joueurs&action=ajouter" method="POST" style="max-width:400px; margin:0 auto;">
    <label>ID Joueur : </label>
    <input type="text" name="idJoueur" required style="width:100%; margin-bottom:10px;"><br>

    <label>Nom : </label>
    <input type="text" name="nomJoueur" required style="width:100%; margin-bottom:10px;"><br>

    <label>Prénom : </label>
    <input type="text" name="prenomJoueur" required style="width:100%; margin-bottom:10px;"><br>

    <label>Num Licence : </label>
    <input type="number" name="numLicence" required style="width:100%; margin-bottom:10px;"><br>

    <label>Statut : </label>
    <input type="text" name="statut" style="width:100%; margin-bottom:10px;"><br>

    <label>Date Naissance : </label>
    <input type="date" name="dateNaissanceJoueur" style="width:100%; margin-bottom:10px;"><br>

    <label>Taille (cm) : </label>
    <input type="number" name="tailleJoueur" style="width:100%; margin-bottom:10px;"><br>

    <label>Poids (kg) : </label>
    <input type="number" name="poidsJoueur" style="width:100%; margin-bottom:10px;"><br>

    <label>Poste Principal : </label>
    <input type="text" name="postePrincipal" style="width:100%; margin-bottom:10px;"><br>


    <button type="submit" class="btn">Ajouter</button>
</form>

<?php require_once __DIR__ . "/../layout/footer.php"; ?>
