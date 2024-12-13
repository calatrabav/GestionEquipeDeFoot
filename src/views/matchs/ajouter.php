
<?php include "../includes/menu.php"; ?>
<h1>Ajouter un Match</h1>
<form method="POST" action="index.php?controller=matchs&action=ajouter">
    <label>Date :</label><input type="date" name="dateMatch" required><br>
    <label>Heure :</label><input type="time" name="heureMatch" required><br>
    <label>Lieu :</label><input type="text" name="lieuRencontre" required><br>
    <label>Équipe Adverse :</label><input type="text" name="nomEquipeAdverse" required><br>
    <button type="submit">Ajouter</button>
</form>
