<?php
require_once __DIR__ . "/../layout/header.php";

// Récupérer l'id du match (depuis GET ou POST)
$idMatch = $_GET['idMatch'] ?? ($_POST['idMatch'] ?? null);

// Si aucun match n'est sélectionné, on prend le premier match à venir par défaut
if ($idMatch === null) {
    $matchsAVenir = MatchModel::getFuturs();
    if (!empty($matchsAVenir)) {
        $idMatch = $matchsAVenir[0]['idMatch'];
    }
}

// Définir une valeur par défaut pour $selectedPlayersDetails
$selectedPlayersDetails = [];

// Charger les joueurs sélectionnés si on a un match
if ($idMatch !== null) {
    $selectedPlayersDetails = ParticiperModel::getParticipants($idMatch);
    // Récupérer les joueurs actifs non participants
    $joueursActifsNonParticipants = ParticiperModel::getNonParticipants($idMatch);
}
?>

<h2>Feuille de Match</h2>

<!-- Formulaire pour changer de match -->
<form method="POST">
    <label>Match à venir :</label>
    <label>
        <select name="idMatch" required onchange="this.form.submit()">
            <?php
            $matchsAVenir = MatchModel::getFuturs();
            foreach ($matchsAVenir as $m): ?>
                <option value="<?= htmlspecialchars($m['idMatch']) ?>"
                    <?= isset($idMatch) && $idMatch == $m['idMatch'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($m['dateMatch'] . " vs " . $m['nomEquipeAdverse']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </label>
</form>

<br><br>

<?php if ($idMatch === null): ?>
    <p style="color: red;">Aucun match sélectionné. Veuillez choisir un match.</p>
<?php else: ?>
    <!-- Formulaire principal pour sélectionner les joueurs -->
    <h3>Joueurs Actifs</h3>
    <form method="POST">
        <input type="hidden" name="idMatch" value="<?= htmlspecialchars($idMatch) ?>">
        <button type="button" id="selectAllBtn" class="btn">Sélectionner tous les joueurs</button>
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
                    <!-- Case à cocher pour inclure/exclure le joueur -->
                    <td>
                        <input type="checkbox" 
                               name="selection[<?= $idJoueur ?>][selected]"
                               <?= isset($selectedPlayersDetails[$idJoueur]) ? 'checked' : '' ?>>
                    </td>
                    <td><?= htmlspecialchars($j['nomJoueur'] ?? '') ?></td>
                    <td><?= htmlspecialchars($j['prenomJoueur'] ?? '') ?></td>
                    <td><?= htmlspecialchars($j['tailleJoueur'] ?? '') ?></td>
                    <td><?= htmlspecialchars($j['poidsJoueur'] ?? '') ?></td>
                    <td><?= htmlspecialchars($j['postePrincipal'] ?? '') ?></td>
                    <td><?= htmlspecialchars($j['commentaire'] ?? '') ?></td>
                    <td>
                        <!-- Champ caché qui enverra 0 si la case n'est pas cochée -->
                        <input type="hidden" name="selection[<?= $idJoueur ?>][titulaire]" value="0">
                        <!-- Case à cocher pour envoyer 1 si cochée -->
                        <input type="checkbox" 
                               name="selection[<?= $idJoueur ?>][titulaire]" value="1"
                               <?= !empty($selectedPlayersDetails[$idJoueur]['titulaire']) ? 'checked' : '' ?>>
                    </td>
                    <td>
                        <input type="text"
                               name="selection[<?= $idJoueur ?>][poste]"
                               value="<?= htmlspecialchars($selectedPlayersDetails[$idJoueur]['poste'] ?? '') ?>">
                    </td>
                    <td>
                        <input type="text"
                               name="selection[<?= $idJoueur ?>][commentaire]"
                               value="<?= htmlspecialchars($selectedPlayersDetails[$idJoueur]['commentaire'] ?? '') ?>">
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

    <!-- Tableau des joueurs déjà sélectionnés, avec possibilité de modifier la note -->
    <?php if(!empty($selectedPlayersDetails)): ?>
        <h2>Joueurs Sélectionnés</h2>
        <table border="1">
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Titulaire ?</th>
                <th>Poste</th>
                <th>Commentaire Staff</th>
                <th>Évaluation (1 à 10)</th>
                <th>Action</th>
            </tr>
            <?php foreach ($selectedPlayersDetails as $player): 
                $evaluation = $player['evaluation'] ?? null; // Valeur de la colonne `évaluation`
            ?>
                <tr>
                    <td><?= htmlspecialchars($player['nomJoueur']) ?></td>
                    <td><?= htmlspecialchars($player['prenomJoueur']) ?></td>
                    <td>
                        <label>
                            <input type="checkbox"
                                   class="editableTitulaire"
                                   name="titulaire[<?= $player['idJoueur'] ?>]"
                                   <?= $player['titulaire'] ? 'checked' : '' ?>
                                   disabled>
                        </label>
                    </td>
                    <td contenteditable="false" class="editable poste"><?= htmlspecialchars($player['poste']) ?></td>
                    <td contenteditable="false" class="editable commentaire"><?= htmlspecialchars($player['commentaire']) ?></td>
                    <!-- Nouvelle colonne : évaluation -->
                    <td contenteditable="false" class="editable evaluation">
                        <?= $evaluation !== null ? htmlspecialchars($evaluation) : '' ?>
                    </td>
                    <td>
                        <!-- Bouton pour modifier -->
                        <button type="button" class="btn modifier" onclick="enableEditing(this)">Modifier</button>
                        
                        <!-- Form pour retirer le joueur -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="idMatch" value="<?= htmlspecialchars($idMatch) ?>">
                            <input type="hidden" name="idJoueur" value="<?= htmlspecialchars($player['idJoueur']) ?>">
                            <input type="hidden" name="action" value="removePlayer">
                            <button type="submit" class="btn btn-danger">Retirer</button>
                        </form>

                        <!-- Form pour remplacer le joueur -->
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="idMatch" value="<?= htmlspecialchars($idMatch) ?>">
                            <input type="hidden" name="idJoueurARemplacer" value="<?= htmlspecialchars($player['idJoueur']) ?>">
                            <label for="idJoueurToAdd">Remplacer par :</label>
                            <label>
                                <select name="idJoueur" required>
                                    <?php
                                    foreach ($joueursActifsNonParticipants as $j):
                                        ?>
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

        <!-- Form pour retirer tous les joueurs -->
        <form method="POST" style="margin-top: 20px;">
            <input type="hidden" name="idMatch" value="<?= htmlspecialchars($idMatch) ?>">
            <input type="hidden" name="action" value="removeAllPlayers">
            <button type="submit" class="btn btn-danger" id="removeAllBtn">Retirer tous les joueurs</button>
        </form>
    <?php else: ?>
        <p>Aucun joueur n'est encore sélectionné pour ce match.</p>
    <?php endif; ?>
<?php endif; ?>


<script>
    /**
     * Fonction pour activer l'édition de la ligne (poste, commentaire, évaluation).
     * Puis sauver en AJAX (action=updatePlayer) pour inclure la note.
     */
    function enableEditing(button) {
        const row = button.closest("tr");
        const editableCells = row.querySelectorAll(".editable");
        const editableTitulaire = row.querySelector(".editableTitulaire");

        // Rendre les cellules éditables
        editableCells.forEach(cell => {
            cell.contentEditable = true;
            cell.style.backgroundColor = "#f0f8ff"; // Indicate editing
        });

        if (editableTitulaire) {
            editableTitulaire.disabled = false;
            editableTitulaire.style.backgroundColor = "#f0f8ff";
        }

        button.textContent = "Enregistrer";
        button.onclick = function () {
            // Collecter les données modifiées
            const idMatch = <?= json_encode($idMatch) ?>;
            const idJoueurInput = row.querySelector("input[name='idJoueur']");
            const idJoueur = idJoueurInput ? idJoueurInput.value : null;
            let titulaire = 0;
            if (editableTitulaire && editableTitulaire.checked) {
                titulaire = 1;
            }

            const poste = row.querySelector(".poste").textContent.trim();
            const commentaire = row.querySelector(".commentaire").textContent.trim();
            const evalCell = row.querySelector(".evaluation");
            const evaluation = evalCell ? evalCell.textContent.trim() : '';

            // Envoi au contrôleur (action=updatePlayer)
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
                    titulaire: titulaire,
                    evaluation: evaluation  // On envoie la note
                }),
            })
            .then(response => response.text())
            .then(data => {
                console.log("Mise à jour réussie:", data);

                // Désactiver l'édition
                editableCells.forEach(cell => {
                    cell.contentEditable = false;
                    cell.style.backgroundColor = ""; 
                });
                if (editableTitulaire) {
                    editableTitulaire.disabled = true;
                    editableTitulaire.style.backgroundColor = ""; 
                }

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

    // Fonction pour sélectionner/désélectionner tous les joueurs
    document.getElementById('selectAllBtn').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('input[name^="selection["][type="checkbox"]');
        const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);

        checkboxes.forEach(checkbox => {
            checkbox.checked = !allChecked;
        });

        const button = document.getElementById('selectAllBtn');
        button.textContent = allChecked
            ? 'Sélectionner tous les joueurs'
            : 'Désélectionner tous les joueurs';
    });

    // Vérification avant remplacement
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function (event) {
            const idJoueurARemplacer = form.querySelector('input[name="idJoueurARemplacer"]');
            const idJoueur = form.querySelector('select[name="idJoueur"]');
            if (idJoueurARemplacer && idJoueur && idJoueur.value === '') {
                event.preventDefault();
                alert("Veuillez sélectionner un joueur valide pour le remplacement.");
            }
        });
    });
</script>

<?php require_once __DIR__ . "/../layout/footer.php"; ?>
