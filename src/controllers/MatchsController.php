<?php
class MatchsController {
    public function index() {
        require_once "../models/MatchModel.php";
        $matchs = MatchModel::getAll();
        require_once "../views/matchs/index.php";
    }

    public function ajouter() {
        require_once "../models/MatchModel.php";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'idMatch' => $_POST['idMatch'],
                'dateMatch' => $_POST['dateMatch'],
                'heureMatch' => $_POST['heureMatch'],
                'nomEquipeAdverse' => $_POST['nomEquipeAdverse'],
                'lieuRencontre' => $_POST['lieuRencontre'],
                'competition' => $_POST['competition']
            ];
            MatchModel::create($data);

            // Si score fourni, on met à jour le score
            $scoreEquipe = isset($_POST['scoreEquipe']) && $_POST['scoreEquipe'] !== '' ? (int)$_POST['scoreEquipe'] : null;
            $scoreEquipeAdverse = isset($_POST['scoreEquipeAdverse']) && $_POST['scoreEquipeAdverse'] !== '' ? (int)$_POST['scoreEquipeAdverse'] : null;
            if ($scoreEquipe !== null && $scoreEquipeAdverse !== null) {
                MatchModel::updateScore($_POST['idMatch'], $scoreEquipe, $scoreEquipeAdverse);
            }

            header("Location: index.php?controller=matchs&action=index");
            exit();
        }
        require_once "../views/matchs/ajouter.php";
    }

    public function modifier() {
        require_once "../models/MatchModel.php";
        $id = $_GET['id'] ?? null;
        $match = MatchModel::getById($id);
        if (!$match) {
            header("Location: index.php?controller=matchs&action=index");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Mettre à jour les infos du match (hors score)
            $data = [
                'dateMatch' => $_POST['dateMatch'],
                'heureMatch' => $_POST['heureMatch'],
                'nomEquipeAdverse' => $_POST['nomEquipeAdverse'],
                'lieuRencontre' => $_POST['lieuRencontre'],
                'competition' => $_POST['competition']
            ];
            MatchModel::update($id, $data);

            // Mise à jour du score si fourni
            $scoreEquipe = isset($_POST['scoreEquipe']) && $_POST['scoreEquipe'] !== '' ? (int)$_POST['scoreEquipe'] : null;
            $scoreEquipeAdverse = isset($_POST['scoreEquipeAdverse']) && $_POST['scoreEquipeAdverse'] !== '' ? (int)$_POST['scoreEquipeAdverse'] : null;
            if ($scoreEquipe !== null && $scoreEquipeAdverse !== null) {
                MatchModel::updateScore($id, $scoreEquipe, $scoreEquipeAdverse);
            }

            header("Location: index.php?controller=matchs&action=index");
            exit();
        }

        require_once "../views/matchs/modifier.php";
    }

    public function supprimer() {
        require_once "../models/MatchModel.php";
        $id = $_GET['id'] ?? null;
        if ($id && MatchModel::getById($id)) {
            MatchModel::delete($id);
        }
        header("Location: index.php?controller=matchs&action=index");
        exit();
    }
}
