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
                'nom' => $_POST['nom'],
                'date' => $_POST['date']
            ];
            MatchModel::create($data);
            header("Location: index.php?controller=matchs&action=index");
            exit();
        }
        require_once "../views/matchs/ajouter.php";
    }

    public function modifier() {
        require_once "../models/MatchModel.php";
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

        if (!$id || !($match = MatchModel::getById($id))) {
            // Match introuvable
            header("Location: index.php?controller=matchs&action=index");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => $_POST['nom'],
                'date' => $_POST['date']
            ];
            MatchModel::update($id, $data);
            header("Location: index.php?controller=matchs&action=index");
            exit();
        }

        // $match contient les données du match à modifier
        require_once "../views/matchs/modifier.php";
    }

    public function supprimer() {
        require_once "../models/MatchModel.php";
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if ($id && MatchModel::getById($id)) {
            MatchModel::delete($id);
        }
        header("Location: index.php?controller=matchs&action=index");
        exit();
    }
}
