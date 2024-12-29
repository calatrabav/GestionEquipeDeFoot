<?php
require_once __DIR__ . "/../layout/header.php";

// Récupérer l'id du match à partir de GET, POST ou utiliser un match par défaut
$idMatch = $_GET['idMatch'] ?? ($_POST['idMatch'] ?? null);

// Si aucun idMatch n'est passé et qu'il existe des matchs disponibles, sélectionner le premier match par défaut
if ($idMatch === null) {
    $matchsAVenir = MatchModel::getFuturs();
    if (!empty($matchsAVenir)) {
        $idMatch = $matchsAVenir[0]['idMatch'];
    }
}

// Définir une valeur par défaut pour $selectedPlayersDetails
$selectedPlayersDetails = [];

// Si un idMatch valide est défini, récupérer les joueurs sélectionnés pour ce match
if ($idMatch !== null) {
    $selectedPlayersDetails = ParticiperModel::getParticipants($idMatch);
}
if($idMatch !== null){
    $joueursActifsNonParticipants = ParticiperModel::getNonParticipants($idMatch);
}
?>

<h2>Feuille de Match</h2>
<form method="POST">
    <label>Match à venir :</label>
    <label>
        <select name="idMatch" required onchange="this.form.submit()">
            <?php
            $matchsAVenir = MatchModel::getFuturs();
            foreach ($matchsAVenir as $m): ?>
                <option value="<?= htmlspecialchars($m['idMatch']) ?>" <?= isset($idMatch) && $idMatch == $m['idMatch'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($m['dateMatch'] . " vs " . $m['nomEquipeAdverse']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
</form>

<br><br>

<?php if ($idMatch === null): ?>
    <p style="color: red;">Aucun match sélectionné. Veuillez choisir un match dans la liste déroulante.</p>
<?php else: ?>
    <!-- Affichage des joueurs actifs et formulaire -->
    <h3>Joueurs Actifs</h3>
    <form method="POST">
        <input type="hidden" name="idMatch" value="<?= htmlspecialchars($idMatch) ?>">
        <button type="button" id="toggleSelectButton" class="btn">Sélectionner tous les joueurs</button>
        <table>
            <tr>
                <th>Sélection</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Taille</th>
                <th>Poids</th>
                <th>Poste Principal</th>
                <th>Commentaire Joueur</th>
                <th>Titulaire ?</th>
                <th>Poste sur ce match</th>
                <th>Commentaire Staff</th>
            </tr>
            <?php
            $joueursActifs = JoueursModel::getActifs();
            foreach ($joueursActifs as $j):
                $idJoueur = $j['idJoueur'];
                ?>
                <tr>
                    <td>
                        <input type="checkbox" name="selection[<?= $idJoueur ?>][selected]" <?= isset($selectedPlayersDetails[$idJoueur]) ? 'checked' : '' ?>>
                    </td>
                    <td><?= htmlspecialchars($j['nomJoueur'] ?? '') ?></td>
                    <td><?= htmlspecialchars($j['prenomJoueur'] ?? '') ?></td>
                    <td><?= htmlspecialchars($j['tailleJoueur'] ?? '') ?></td>
                    <td><?= htmlspecialchars($j['poidsJoueur'] ?? '') ?></td>
                    <td><?= htmlspecialchars($j['postePrincipal'] ?? '') ?></td>
                    <td><?= htmlspecialchars($j['commentaire'] ?? '') ?></td>
                    <td>
                        <input type="checkbox" name="selection[<?= $idJoueur ?>][titulaire]" <?= isset($selectedPlayersDetails[$idJoueur]['titulaire']) && $selectedPlayersDetails[$idJoueur]['titulaire'] ? 'checked' : '' ?>>
                    </td>
                    <td>
                        <input type="text" name="selection[<?= $idJoueur ?>][poste]" value="<?= htmlspecialchars($selectedPlayersDetails[$idJoueur]['poste'] ?? '') ?>">
                    </td>
                    <td>
                        <input type="text" name="selection[<?= $idJoueur ?>][commentaire]" value="<?= htmlspecialchars($selectedPlayersDetails[$idJoueur]['commentaire'] ?? '') ?>">
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br>

        <?php if (isset($error)): ?>
            <p id="errorMessage" style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <button type="submit" class="btn">Valider la sélection</button>
    </form>

    <?php if (empty($selectedPlayersDetails)): ?>
        <p>Aucun joueur n'a encore été sélectionné pour ce match.</p>
    <?php else: ?>
        <h2>Joueurs Sélectionnés</h2>
        <table border="1">
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Titulaire ?</th>
                <th>Poste</th>
                <th>Commentaire Staff</th>
                <th>Action</th>
            </tr>
            <?php foreach ($selectedPlayersDetails as $player): ?>
                <tr>
                    <td><?= htmlspecialchars($player['nomJoueur']) ?></td>
                    <td><?= htmlspecialchars($player['prenomJoueur']) ?></td>
                    <td>
                        <label>
                            <input type="checkbox" class="editableTitulaire" name="titulaire[<?= $player['idJoueur'] ?>]" <?= $player['titulaire'] ? 'checked' : '' ?> disabled>
                        </label>
                    </td>
                    <td contenteditable="false" class="editable poste"><?= htmlspecialchars($player['poste']) ?></td>
                    <td contenteditable="false" class="editable commentaire"><?= htmlspecialchars($player['commentaire']) ?></td>
                    <td>
                        <button type="button" class="btn modifier" onclick="enableEditing(this)">Modifier</button>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="idMatch" value="<?= htmlspecialchars($idMatch) ?>">
                            <input type="hidden" name="idJoueur" value="<?= htmlspecialchars($player['idJoueur']) ?>">
                            <input type="hidden" name="action" value="removePlayer">
                            <button type="submit" class="btn btn-danger">Retirer</button>
                        </form>

                        <!-- Bouton pour remplacer un joueur -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="idMatch" value="<?= htmlspecialchars($idMatch) ?>">
                            <input type="hidden" name="idJoueurToRemove" value="<?= htmlspecialchars($player['idJoueur']) ?>">
                            <label for="idJoueurToAdd">Remplacer par :</label>
                            <label>
                                <select name="idJoueurToAdd" required>
                                    <?php
                                    foreach ($joueursActifsNonParticipants  as $j): ?>
                                        <option value="<?= htmlspecialchars($j['idJoueur']) ?>">
                                            <?= htmlspecialchars($j['nomJoueur'] . ' ' . $j['prenomJoueur']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                            <input type="hidden" name="action" value="replacePlayer">
                            <button type="submit" class="btn btn-warning">Remplacer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
<?php endif; ?>

<script>
    function enableEditing(button) {
        const row = button.closest("tr");
        const editableCells = row.querySelectorAll(".editable");
        const editableTitulaire = row.querySelector(".editableTitulaire");


        // Rendre les cellules éditables
        editableCells.forEach(cell => {
            cell.contentEditable = true;
            cell.style.backgroundColor = "#f0f8ff"; // Indicate editing
        });
        editableTitulaire.disabled = false;
        editableTitulaire.style.backgroundColor = "#f0f8ff"; // Indicate editing


        button.textContent = "Enregistrer";
        button.onclick = function () {
            // Collecter les données modifiées
            const idMatch = <?= json_encode($idMatch) ?>;
            const idJoueur = row.querySelector("input[name='idJoueur']").value;
            const titulaire = editableTitulaire.checked ? 1 : 0;
            const poste = row.querySelector(".poste").textContent;
            const commentaire = row.querySelector(".commentaire").textContent;

            // Envoyer les données via une requête POST
            fetch("", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: new URLSearchParams({
                    action: "updatePlayer",
                    idMatch: idMatch,
                    idJoueur: idJoueur,
                    poste: poste,
                    commentaire: commentaire,
                    titulaire: titulaire
                }),
            })
                .then(response => response.text())
                .then(data => {
                    // Vérifier la réponse et actualiser l'affichage
                    console.log("Mise à jour réussie:", data);

                    // Désactiver l'édition après mise à jour
                    editableCells.forEach(cell => {
                        cell.contentEditable = false;
                        cell.style.backgroundColor = ""; // Reset background color
                    });
                    editableTitulaire.disabled = true;
                    editableTitulaire.style.backgroundColor = ""; // Reset background color


                    // Réinitialiser le bouton
                    button.textContent = "Modifier";
                    button.onclick = function () {
                        enableEditing(button);
                    };
                })
                .catch(error => {
                    console.error("Erreur lors de la mise à jour :", error);
                });
        };
    }
    // Fonction pour sélectionner ou désélectionner tous les joueurs
    document.getElementById('selectAllBtn').addEventListener('click', function() {
        // Récupérer toutes les cases à cocher des joueurs
        const checkboxes = document.querySelectorAll('input[name^="selection["]');

        // Vérifier si toutes les cases sont déjà cochées
        const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

        // Cocher/décocher toutes les cases
        checkboxes.forEach(checkbox => {
            checkbox.checked = !allChecked; // Si toutes sont cochées, on les décoche, sinon on les coche
        });
    });
</script>
<?php require_once __DIR__ . "/../layout/footer.php"; ?>
