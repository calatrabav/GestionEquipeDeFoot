<?php
class JoueursController {
    public function index() {
        require_once __DIR__ "../models/JoueursModel.php";
        $joueurs = JoueursModel::getAll();
        require_once __DIR__ "../views/joueurs/index.php";
    }

    public function ajouter() {
        require_once __DIR__ "/../models/JoueursModel.php";
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'idJoueur' => $_POST['idJoueur'],
                'nomJoueur' => $_POST['nomJoueur'],
                'prenomJoueur' => $_POST['prenomJoueur'],
                'numLicence' => $_POST['numLicence'],
                'statut' => $_POST['statut'],
                'dateNaissanceJoueur' => $_POST['dateNaissanceJoueur'],
                'tailleJoueur' => $_POST['tailleJoueur'],
                'poidsJoueur' => $_POST['poidsJoueur'],
                'postePrincipal' => $_POST['postePrincipal'],
                'commentaire' => $_POST['commentaire']
            ];
            JoueursModel::create($data);
            header("Location: index.php?controller=joueurs&action=index");
            exit();
        }
        require_once __DIR__ "../views/joueurs/ajouter.php";
    }

    public function modifier() {
        require_once __DIR__ "../models/JoueursModel.php";
        $id = $_GET['id'] ?? null;
        $joueur = JoueursModel::getById($id);
        if (!$joueur) {
            header("Location: index.php?controller=joueurs&action=index");
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nomJoueur' => $_POST['nomJoueur'],
                'prenomJoueur' => $_POST['prenomJoueur'],
                'numLicence' => $_POST['numLicence'],
                'statut' => $_POST['statut'],
                'dateNaissanceJoueur' => $_POST['dateNaissanceJoueur'],
                'tailleJoueur' => $_POST['tailleJoueur'],
                'poidsJoueur' => $_POST['poidsJoueur'],
                'postePrincipal' => $_POST['postePrincipal'],
                'commentaire' => $_POST['commentaire']
            ];
            JoueursModel::update($id, $data);
            header("Location: index.php?controller=joueurs&action=index");
            exit();
        }
        require_once __DIR__ "../views/joueurs/modifier.php";
    }

    public function supprimer() {
        require_once __DIR__ "../models/JoueursModel.php";
        $id = $_GET['id'] ?? null;
        if ($id && JoueursModel::getById($id)) {
            JoueursModel::delete($id);
        }
        header("Location: index.php?controller=joueurs&action=index");
        exit();
    }
}
