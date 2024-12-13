<?php include "../includes/menu.php"; ?>

<?php include 'includes/header.php'; ?>
<h1>Ajouter un joueur</h1>
<form method="post">
    <label>Nom :</label>
    <input type="text" name="nom" required>
    <br>
    <label>Prénom :</label>
    <input type="text" name="prenom" required>
    <br>
    <label>Statut :</label>
    <select name="statut">
        <option value="Actif">Actif</option>
        <option value="Inactif">Inactif</option>
    </select>
    <br>
    <button type="submit">Ajouter</button>
</form>
<?php include 'src/includes/footer.php'; ?>
