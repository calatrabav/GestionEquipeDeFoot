<?php
class MatchsController {
    public function index() {
        require_once "../models/MatchModel.php";
        $matchs = MatchModel::getAll();
        require_once "../views/matchs/index.php"; // Inclut le menu ici
    }
    

    public function ajouter() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once "../models/MatchModel.php";
            MatchModel::ajouter($_POST);
            header("Location: index.php?controller=matchs&action=index");
            exit();
        }
        require_once "../views/matchs/ajouter.php";
    }

    public function supprimer() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($id) {
            require_once "../models/MatchModel.php";
            MatchModel::supprimer($id);
        }
        header("Location: index.php?controller=matchs&action=index");
        exit();
    }

    public function modifier() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($id) {
            require_once "../models/MatchModel.php";
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                MatchModel::modifier($id, $_POST);
                header("Location: index.php?controller=matchs&action=index");
                exit();
            }
            $match = MatchModel::getById($id);
            require_once "../views/matchs/modifier.php";
        }
    }
}
?>
