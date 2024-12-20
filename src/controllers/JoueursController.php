<?php
class JoueursController {
    public function index() {
        require_once "../models/JoueursModel.php";
        $joueurs = JoueursModel::getAll();
        require_once "../views/joueurs/index.php";
    }

    public function ajouter() {
        require_once "../models/JoueursModel.php";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => $_POST['nom']
            ];
            JoueursModel::create($data);
            header("Location: index.php?controller=joueurs&action=index");
            exit();
        }
        require_once "../views/joueurs/ajouter.php";
    }

    public function modifier() {
        require_once "../models/JoueursModel.php";
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;

        if (!$id || !($joueur = JoueursModel::getById($id))) {
            // Joueur introuvable ou ID non valide
            header("Location: index.php?controller=joueurs&action=index");
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom' => $_POST['nom']
            ];
            JoueursModel::update($id, $data);
            header("Location: index.php?controller=joueurs&action=index");
            exit();
        }

        // $joueur contient les données du joueur à modifier
        require_once "../views/joueurs/modifier.php";
    }

    public function supprimer() {
        require_once "../models/JoueursModel.php";
        $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
        if ($id && JoueursModel::getById($id)) {
            JoueursModel::delete($id);
        }
        header("Location: index.php?controller=joueurs&action=index");
        exit();
    }
}
