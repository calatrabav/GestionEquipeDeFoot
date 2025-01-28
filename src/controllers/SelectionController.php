<?php

require_once "../models/ParticiperModel.php";
require_once "../models/JoueursModel.php";

class SelectionController {
    public function selection() {
        require_once "../models/MatchModel.php";

        // Récupérer les matchs à venir
        $matchsAVenir = MatchModel::getFuturs();
        $joueursActifs = JoueursModel::getActifs();
        $selectedPlayersDetails = [];
        $error = '';

        // Récupérer l'ID du match sélectionné
        $idMatch = $_GET['idMatch'] ?? ($_POST['idMatch'] ?? null); // Récupérer l'id du match

        // Si un match est sélectionné, récupérer les joueurs associés à ce match
        if ($idMatch) {
            if (ParticiperModel::hasParticipants($idMatch)) {
                // Si des joueurs sont associés à ce match, récupérez-les
                $selectedPlayersDetails = ParticiperModel::getParticipants($idMatch);
            }
        }

        // Traitement du formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? null;

            if ($action === 'removeAllPlayers') {
                if ($idMatch) {
                    ParticiperModel::deleteAllParticipants($idMatch);
                    $selectedPlayersDetails = [];
                }
            }

            if ($action === 'removePlayer') {
                // Supprimer un joueur de la table "Participer"
                $idJoueur = $_POST['idJoueur'] ?? null;
                if ($idMatch && $idJoueur) {
                    ParticiperModel::deleteParticipant($idMatch, $idJoueur);
                }

                // Mettre à jour la liste des joueurs sélectionnés
                $selectedPlayersDetails = ParticiperModel::getParticipants($idMatch);
            } elseif ($action === 'updatePlayer') {
                // Modifier un joueur dans la table "Participer"
                $idJoueur = $_POST['idJoueur'] ?? null;
                $poste = trim($_POST['poste'] ?? '');
                $commentaire = trim($_POST['commentaire'] ?? '');
                $titulaire = isset($_POST['titulaire']) ? (int) $_POST['titulaire'] : 0;

                if ($idMatch && $idJoueur) {
                    ParticiperModel::updateParticipant($idMatch, $idJoueur,$titulaire, $poste, $commentaire);
                }

                // Mettre à jour la liste des joueurs sélectionnés
                $selectedPlayersDetails = ParticiperModel::getParticipants($idMatch);
            } elseif ($action === 'replacePlayer') {
                $idJoueurARemplacer = $_POST['idJoueurARemplacer'] ?? null;
                $idJoueur = $_POST['idJoueur'] ?? null;
                $titulaire = $_POST['titulaire'] ?? 0;
                $poste = $_POST['poste'] ?? '';
                $commentaire = $_POST['commentaire'] ?? '';

                try {
                    if ($idMatch && $idJoueurARemplacer && $idJoueur) {
                        ParticiperModel::replaceParticipant($idMatch, $idJoueurARemplacer, $idJoueur, $titulaire, $poste, $commentaire);
                        $selectedPlayersDetails = ParticiperModel::getParticipants($idMatch);
                        $joueursNonParticipants = ParticiperModel::getNonParticipants($idMatch);
                    }
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            } else {
                // Logique pour ajouter ou modifier des joueurs
                $selection = $_POST['selection'] ?? [];

                if (count($selection) <= 11 || count($selection) > 16) {
                    $error = 'Vous devez sélectionner entre 11 et 16 joueurs.';
                } else {
                    foreach ($selection as $idJoueur => $details) {
                        $poste = $details['poste'] ?? '';
                        $commentaireStaff = $details['commentaire'] ?? null;
                        $titulaire = isset($details['titulaire']) && $details['titulaire'] === '1' ? 1 : 0;

                        if (isset($details['selected']) && $details['selected'] == 'on') {
                            ParticiperModel::insertParticipant($idMatch, $idJoueur, $titulaire, $poste, $commentaireStaff);
                        } else {
                            ParticiperModel::deleteParticipant($idMatch, $idJoueur);
                        }
                    }

                    // Mettre à jour la liste des joueurs sélectionnés après modification
                    $selectedPlayersDetails = ParticiperModel::getParticipants($idMatch);
                }
            }
        }

        // Envoi des données vers la vue
        require_once "../views/selection/selection.php";
    }
}