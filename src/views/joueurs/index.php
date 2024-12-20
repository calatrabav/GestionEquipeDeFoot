
<?php require_once __DIR__ . "/../layout/header.php"; ?>

<h2>Liste des joueurs</h2>
<table style="width:100%; border-collapse: collapse; text-align:center;">
    <tr style="background:#eee;">
        <th style="border:1px solid #ccc; padding:8px;">ID</th>
        <th style="border:1px solid #ccc; padding:8px;">Nom</th>
        <th style="border:1px solid #ccc; padding:8px;">Prénom</th>
        <th style="border:1px solid #ccc; padding:8px;">Num Licence</th>
        <th style="border:1px solid #ccc; padding:8px;">Statut</th>
        <th style="border:1px solid #ccc; padding:8px;">Date Naissance</th>
        <th style="border:1px solid #ccc; padding:8px;">Taille</th>
        <th style="border:1px solid #ccc; padding:8px;">Poids</th>
        <th style="border:1px solid #ccc; padding:8px;">Poste</th>
    </tr>
    <?php foreach($joueurs as $joueur): ?>
    <tr>
        <td style="border:1px solid #ccc; padding:8px;"><?= htmlspecialchars($joueur['idJoueur']) ?></td>
        <td style="border:1px solid #ccc; padding:8px;"><?= htmlspecialchars($joueur['nomJoueur']) ?></td>
        <td style="border:1px solid #ccc; padding:8px;"><?= htmlspecialchars($joueur['prenomJoueur']) ?></td>
        <td style="border:1px solid #ccc; padding:8px;"><?= htmlspecialchars($joueur['numLicence']) ?></td>
        <td style="border:1px solid #ccc; padding:8px;"><?= htmlspecialchars($joueur['statut']) ?></td>
        <td style="border:1px solid #ccc; padding:8px;"><?= htmlspecialchars($joueur['dateNaissanceJoueur']) ?></td>
        <td style="border:1px solid #ccc; padding:8px;"><?= htmlspecialchars($joueur['tailleJoueur']) ?></td>
        <td style="border:1px solid #ccc; padding:8px;"><?= htmlspecialchars($joueur['poidsJoueur']) ?></td>
        <td style="border:1px solid #ccc; padding:8px;"><?= htmlspecialchars($joueur['postePrincipal']) ?></td>
        <td style="border:1px solid #ccc; padding:8px;">
            <a class="btn" href="index.php?controller=joueurs&action=modifier&id=<?= urlencode($joueur['idJoueur']) ?>">Modifier</a>
            <a class="btn" style="background:red;" href="index.php?controller=joueurs&action=supprimer&id=<?= urlencode($joueur['idJoueur']) ?>">Supprimer</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<br>
<a class="btn" href="index.php?controller=joueurs&action=ajouter">Ajouter un joueur</a>


<?php require_once __DIR__ . "/../layout/footer.php"; ?>
