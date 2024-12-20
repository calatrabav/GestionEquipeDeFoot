<?php
class AuthController {
    public function login() {
        // Si le formulaire a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            global $pdo; // On suppose que $pdo est déclaré globalement dans db_connect.php
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            // Vérification dans la BDD
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                // Authentification réussie
                $_SESSION['user'] = $user['username'];
                header('Location: index.php?controller=joueurs&action=index');
                exit();
            } else {
                // Échec de l'authentification
                $error = "Identifiants incorrects.";
            }
        }
        require_once "../views/login.php";
    }

    public function logout() {
        session_destroy();
        header('Location: index.php?controller=auth&action=login');
        exit();
    }
}
