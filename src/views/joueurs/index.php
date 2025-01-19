<?php require_once __DIR__ . "/../layout/header.php"; ?>

<h2>Liste des joueurs</h2>
<table>
    <tr style="background:#eee;">
        <th>ID</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Num Licence</th>
        <th>Statut</th>
        <th>Date Naissance</th>
        <th>Taille</th>
        <th>Poids</th>
        <th>Poste</th>
        <th>Commentaire</th>
        <th>Actions</th>
    </tr>
    <?php foreach($joueurs as $joueur): ?>
    <tr>
        <td><?= htmlspecialchars($joueur['idJoueur']) ?></td>
        <td><?= htmlspecialchars($joueur['nomJoueur']) ?></td>
        <td><?= htmlspecialchars($joueur['prenomJoueur']) ?></td>
        <td><?= htmlspecialchars($joueur['numLicence']) ?></td>
        <td><?= htmlspecialchars($joueur['statut']) ?></td>
        <td><?= htmlspecialchars($joueur['dateNaissanceJoueur']) ?></td>
        <td><?= htmlspecialchars($joueur['tailleJoueur']) ?></td>
        <td><?= htmlspecialchars($joueur['poidsJoueur']) ?></td>
        <td><?= htmlspecialchars($joueur['postePrincipal']) ?></td>
        <td><?= htmlspecialchars($joueur['commentaire']) ?></td>
        <td>
            <a class="btn" href="index.php?controller=joueurs&action=modifier&id=<?= urlencode($joueur['idJoueur']) ?>">Modifier</a>
            <a class="btn" style="background:red;" href="index.php?controller=joueurs&action=supprimer&id=<?= urlencode($joueur['idJoueur']) ?>">Supprimer</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<br>
<a class="btn" href="index.php?controller=joueurs&action=ajouter">Ajouter un joueur</a>

<?php require_once __DIR__ . "/../layout/footer.php"; ?>
