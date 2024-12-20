<?php require_once __DIR__ . "/../layout/header.php"; ?>

<h2>Modifier un match</h2>
<?php if (isset($match)): ?>
<form action="index.php?controller=matchs&action=modifier&id=<?= urlencode($match['idMatch']) ?>" method="POST" style="width:300px;margin:0 auto;">
    <label>ID Match (non modifiable) :</label><br>
    <input type="text" value="<?= htmlspecialchars($match['idMatch']) ?>" disabled><br><br>

    <label>Date Match :</label><br>
    <input type="date" name="dateMatch" value="<?= htmlspecialchars($match['dateMatch']) ?>" required><br><br>

    <label>Heure Match :</label><br>
    <input type="time" name="heureMatch" value="<?= htmlspecialchars($match['heureMatch']) ?>" required><br><br>

    <label>Équipe Adverse :</label><br>
    <input type="text" name="nomEquipeAdverse" value="<?= htmlspecialchars($match['nomEquipeAdverse']) ?>"><br><br>

    <label>Lieu Rencontre :</label><br>
    <input type="text" name="lieuRencontre" value="<?= htmlspecialchars($match['lieuRencontre']) ?>"><br><br>

    <label>Compétition :</label><br>
    <input type="text" name="competition" value="<?= htmlspecialchars($match['competition']) ?>"><br><br>

    <label>Score Équipe :</label><br>
    <input type="number" name="scoreEquipe" value="<?= $match['scoreEquipe'] !== null ? htmlspecialchars($match['scoreEquipe']) : '' ?>"><br><br>

    <label>Score Équipe Adverse :</label><br>
    <input type="number" name="scoreEquipeAdverse" value="<?= $match['scoreEquipeAdverse'] !== null ? htmlspecialchars($match['scoreEquipeAdverse']) : '' ?>"><br><br>

    <button type="submit" class="btn">Modifier</button>
</form>
<?php else: ?>
<p>Match introuvable.</p>
<?php endif; ?>

<?php require_once __DIR__ . "../layout/footer.php"; ?>
