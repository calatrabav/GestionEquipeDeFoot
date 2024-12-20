<?php require_once "../views/layout/header.php"; ?>

<h2>Liste des matchs</h2>
<table style="width:100%; border-collapse: collapse;">
    <tr style="background:#eee;">
        <th style="border:1px solid #ccc; padding:8px;">ID</th>
        <th style="border:1px solid #ccc; padding:8px;">Nom</th>
        <th style="border:1px solid #ccc; padding:8px;">Date</th>
        <th style="border:1px solid #ccc; padding:8px;">Actions</th>
    </tr>
    <?php foreach($matchs as $match): ?>
    <tr>
        <td style="border:1px solid #ccc; padding:8px; text-align:center;"><?= $match['id'] ?></td>
        <td style="border:1px solid #ccc; padding:8px;"><?= htmlspecialchars($match['nom']) ?></td>
        <td style="border:1px solid #ccc; padding:8px;"><?= htmlspecialchars($match['date']) ?></td>
        <td style="border:1px solid #ccc; padding:8px; text-align:center;">
            <a class="btn" href="index.php?controller=matchs&action=modifier&id=<?= $match['id'] ?>">Modifier</a>
            <a class="btn" style="background:red;" href="index.php?controller=matchs&action=supprimer&id=<?= $match['id'] ?>">Supprimer</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<br>
<a class="btn" href="index.php?controller=matchs&action=ajouter">Ajouter un match</a>

<?php require_once "../views/layout/footer.php"; ?>
