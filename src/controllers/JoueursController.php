<?php
class JoueursController {
    public function index() {
        require_once "../models/JoueursModel.php";
        $joueurs = JoueursModel::getAll();
        require_once "../views/joueurs/index.php"; // Inclut le menu ici
    }
    

    public function ajouter() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Ajouter un joueur
        }
        require_once "../views/joueurs/ajouter.php";
    }

    public function modifier() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($id && $_SERVER['REQUEST_METHOD'] === 'POST') {
            // Modifier un joueur
        }
        require_once "../views/joueurs/modifier.php";
    }

    public function supprimer() {
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($id) {
            // Supprimer un joueur
        }
        header("Location: index.php?controller=joueurs&action=index");
        exit();
    }
}
