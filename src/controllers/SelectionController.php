<?php
class SelectionController {
    public function selection() {
        require_once "../models/MatchModel.php";
        require_once "../models/JoueursModel.php";
        require_once "../models/ParticiperModel.php";

        $matchsAVenir = MatchModel::getFuturs();
        $joueursActifs = JoueursModel::getActifs();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idMatch = $_POST['idMatch'];
            ParticiperModel::deleteAllForMatch($idMatch);

            if (isset($_POST['selection'])) {
                foreach ($_POST['selection'] as $idJoueur => $data) {
                    // Si le joueur n'est pas coché, data ne contiendra pas 'titulaire' ni 'poste' => vérifier
                    $titulaire = isset($data['titulaire']) ? 1 : 0;
                    $poste = isset($data['poste']) ? $data['poste'] : '';
                    $commentaire = isset($data['commentaire']) ? $data['commentaire'] : '';
                    ParticiperModel::insertParticipant($idMatch, $idJoueur, $titulaire, $poste, $commentaire);
                }
            }
            header("Location: index.php?controller=selection&action=selection");
            exit();
        }

        require_once "../views/selection/selection.php";
    }

    public function evaluation() {
        require_once "../models/ParticiperModel.php";
        require_once "../models/MatchModel.php";

        $idMatch = $_GET['idMatch'] ?? null;
        $match = MatchModel::getById($idMatch);
        if (!$match) {
            header("Location: index.php?controller=selection&action=selection");
            exit();
        }

        $participants = ParticiperModel::getParticipants($idMatch);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $scoreEquipe = $_POST['scoreEquipe'];
            $scoreEquipeAdverse = $_POST['scoreEquipeAdverse'];
            MatchModel::updateScore($idMatch, $scoreEquipe, $scoreEquipeAdverse);

            if (isset($_POST['evaluation'])) {
                foreach ($_POST['evaluation'] as $idJoueur => $eval) {
                    ParticiperModel::updateEvaluation($idMatch, $idJoueur, $eval);
                }
            }

            header("Location: index.php?controller=stats&action=index");
            exit();
        }

        require_once "../views/selection/evaluation.php";
    }
}
