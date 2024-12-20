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
            // Récupérer les données du formulaire
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
                'victoire' => isset($_POST['victoire']) ? $_POST['victoire'] : null
            ];
            JoueursModel::create($data);
            header("Location: index.php?controller=joueurs&action=index");
            exit();
        }
        require_once "../views/joueurs/ajouter.php";
    }

    public function modifier() {
        require_once "../models/JoueursModel.php";
        $id = isset($_GET['id']) ? $_GET['id'] : null;

        if (!$id || !($joueur = JoueursModel::getById($id))) {
            // Joueur introuvable ou ID invalide
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
                'victoire' => isset($_POST['victoire']) ? $_POST['victoire'] : null
            ];
            JoueursModel::update($id, $data);
            header("Location: index.php?controller=joueurs&action=index");
            exit();
        }

        require_once "../views/joueurs/modifier.php";
    }

    public function supprimer() {
        require_once "../models/JoueursModel.php";
        $id = isset($_GET['id']) ? $_GET['id'] : null;
        if ($id && JoueursModel::getById($id)) {
            JoueursModel::delete($id);
        }
        header("Location: index.php?controller=joueurs&action=index");
        exit();
    }
}
