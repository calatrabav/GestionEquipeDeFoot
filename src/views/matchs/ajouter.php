<?php require_once __DIR__ . "/../layout/header.php"; ?>

<h2>Ajouter un match</h2>
<form action="index.php?controller=matchs&action=ajouter" method="POST" style="width:300px;margin:0 auto;">
    <label>ID Match :</label><br>
    <input type="text" name="idMatch" required><br><br>

    <label>Date Match :</label><br>
    <input type="date" name="dateMatch" required><br><br>

    <label>Heure Match :</label><br>
    <input type="time" name="heureMatch" required><br><br>

    <label>Équipe Adverse :</label><br>
    <input type="text" name="nomEquipeAdverse"><br><br>

    <label>Lieu Rencontre :</label><br>
    <select name="lieuRencontre" required>
        <option value="Domicile" <?= isset($match['lieuRencontre']) && $match['lieuRencontre'] == 'domicile' ? 'selected' : '' ?>>Domicile</option>
        <option value="Extérieur" <?= isset($match['lieuRencontre']) && $match['lieuRencontre'] == 'extérieur' ? 'selected' : '' ?>>Extérieur</option>
    </select><br><br>

    <label>Compétition :</label><br>
    <input type="text" name="competition"><br><br>

    <label>Score Équipe :</label><br>
    <input type="number" name="scoreEquipe"><br><br>

    <label>Score Équipe Adverse :</label><br>
    <input type="number" name="scoreEquipeAdverse"><br><br>

    <button type="submit" class="btn">Ajouter</button>
</form>

<?php require_once __DIR__ . "/../layout/footer.php"; ?>