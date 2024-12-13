<?php
class AuthController {
    public function login() {
        // Afficher la page de connexion
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Authentifier l'utilisateur (ex : vérifier le nom d'utilisateur et mot de passe)
            $_SESSION['user'] = $_POST['username'];  // Exemple basique, à améliorer
            header('Location: index.php?controller=joueurs&action=index');
            exit();
        }
        require_once "../views/login.php";
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?controller=auth&action=login');
        exit();
    }
}
?>
