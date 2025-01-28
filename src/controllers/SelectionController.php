<?php

require_once "../models/ParticiperModel.php";
require_once "../models/JoueursModel.php";

class SelectionController {
    <?php
require_once "../models/MatchModel.php";
require_once "../models/JoueursModel.php";
require_once "../models/ParticiperModel.php";

class SelectionController {

    public function selection() {
        // Récupérer les matchs à venir
        $matchsAVenir = MatchModel::getFuturs();
        $joueursActifs = JoueursModel::getActifs();
        $selectedPlayersDetails = [];
        $error = '';

        // Récupérer l'ID du match sélectionné
        $idMatch = $_GET['idMatch'] ?? ($_POST['idMatch'] ?? null);

        // Si un match est sélectionné, récupérer les joueurs associés
        if ($idMatch) {
            if (ParticiperModel::hasParticipants($idMatch)) {
                $participants = ParticiperModel::getParticipants($idMatch);
                // Convertir en [idJoueur => participantData]
                $mapped = [];
                foreach ($participants as $p) {
                    $mapped[$p['idJoueur']] = $p;
                }
                $selectedPlayersDetails = $mapped;
            }
        }

        // Traitement du formulaire
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? null;

            if ($action === 'removeAllPlayers') {
                // Retirer tous les joueurs
                if ($idMatch) {
                    ParticiperModel::deleteAllParticipants($idMatch);
                    $selectedPlayersDetails = [];
                }
            } elseif ($action === 'removePlayer') {
                // Retirer un joueur
                $idJoueur = $_POST['idJoueur'] ?? null;
                if ($idMatch && $idJoueur) {
                    ParticiperModel::deleteParticipant($idMatch, $idJoueur);
                }
                $selectedPlayersDetails = self::reloadParticipants($idMatch);
            } elseif ($action === 'updatePlayer') {
                // Mettre à jour un joueur (poste, commentaire, titulaire, et éventuellement évaluation)
                $idJoueur = $_POST['idJoueur'] ?? null;
                $titulaire = $_POST['titulaire'] ?? 0;
                $poste = $_POST['poste'] ?? '';
                $commentaire = $_POST['commentaire'] ?? '';
                $evaluation = $_POST['evaluation'] ?? null; 

                // Bornage de la note [1..10]
                if ($evaluation !== null) {
                    $evaluation = (int)$evaluation;
                    if ($evaluation < 1 || $evaluation > 10) {
                        $evaluation = null; // ou forcer un msg d'erreur
                    }
                }

                if ($idMatch && $idJoueur) {
                    ParticiperModel::updateParticipant($idMatch, $idJoueur, $titulaire, $poste, $commentaire, $evaluation);
                }
                $selectedPlayersDetails = self::reloadParticipants($idMatch);

            } elseif ($action === 'replacePlayer') {
                // Remplacer un joueur par un autre
                $idJoueurARemplacer = $_POST['idJoueurARemplacer'] ?? null;
                $idJoueur = $_POST['idJoueur'] ?? null;
                $titulaire = $_POST['titulaire'] ?? 0;
                $poste = $_POST['poste'] ?? '';
                $commentaire = $_POST['commentaire'] ?? '';

                try {
                    if ($idMatch && $idJoueurARemplacer && $idJoueur) {
                        ParticiperModel::replaceParticipant($idMatch, $idJoueurARemplacer, $idJoueur, $titulaire, $poste, $commentaire);
                    }
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
                $selectedPlayersDetails = self::reloadParticipants($idMatch);

            } else {
                // Ajouter/modifier la sélection
                $selection = $_POST['selection'] ?? [];
                // Vérifier le nombre de joueurs
                if (count($selection) < 11 || count($selection) > 16) {
                    $error = 'Vous devez sélectionner entre 11 et 16 joueurs.';
                } else {
                    // Parcourir la sélection
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
                }
                $selectedPlayersDetails = self::reloadParticipants($idMatch);
            }
        }

        // Appel de la vue
        require_once __DIR__ . "/../views/selection/selection.php";
    }

    /**
     * Méthode utilitaire pour recharger la liste des participants.
     */
    private static function reloadParticipants($idMatch) {
        $arr = ParticiperModel::getParticipants($idMatch);
        $mapped = [];
        foreach ($arr as $p) {
            $mapped[$p['idJoueur']] = $p;
        }
        return $mapped;
    }
}


    public function evaluation() {
        $idMatch = $_GET['idMatch'] ?? null;
        if (!$idMatch) {
            header("Location: index.php?controller=matchs&action=index");
            exit();
        }

        // On récupère le match
        $match = MatchModel::getById($idMatch);
        if (!$match) {
            header("Location: index.php?controller=matchs&action=index");
            exit();
        }

        // On récupère les participants actuels
        $participants = ParticiperModel::getParticipants($idMatch);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // On suppose que $_POST['evaluation'] = [idJoueur => valeur]
            if (isset($_POST['evaluation'])) {
                foreach ($_POST['evaluation'] as $idJoueur => $note) {
                    // Convertir en entier
                    $note = (int)$note;
                    // Vérifier que la note est entre 1 et 10
                    if ($note >= 1 && $note <= 10) {
                        ParticiperModel::updateEvaluation($idMatch, $idJoueur, $note);
                    }
                }
            }

            // On peut rediriger vers la page de stats, ou recharger la page
            header("Location: index.php?controller=stats&action=index");
            exit();
        }

        // Sinon, on affiche le formulaire
        require_once "../views/selection/evaluation.php";
    }
}