<?php require_once "../views/layout/header.php"; ?>

<h2>Liste des joueurs</h2>
<table style="width:100%; border-collapse: collapse;">
    <tr style="background:#eee;">
        <th style="border:1px solid #ccc; padding:8px;">ID</th>
        <th style="border:1px solid #ccc; padding:8px;">Nom</th>
        <th style="border:1px solid #ccc; padding:8px;">Actions</th>
    </tr>
    <?php foreach($joueurs as $joueur): ?>
    <tr>
        <td style="border:1px solid #ccc; padding:8px; text-align:center;"><?= $joueur['id'] ?></td>
        <td style="border:1px solid #ccc; padding:8px;"><?= htmlspecialchars($joueur['nom']) ?></td>
        <td style="border:1px solid #ccc; padding:8px; text-align:center;">
            <a class="btn" href="index.php?controller=joueurs&action=modifier&id=<?= $joueur['id'] ?>">Modifier</a>
            <a class="btn" style="background:red;" href="index.php?controller=joueurs&action=supprimer&id=<?= $joueur['id'] ?>">Supprimer</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<br>
<a class="btn" href="index.php?controller=joueurs&action=ajouter">Ajouter un joueur</a>

<?php require_once "../views/layout/footer.php"; ?>
