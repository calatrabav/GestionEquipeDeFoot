<?php
class AuthController {
    public function login() {
        require_once "../models/UserModel.php";
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            $user = UsersModel::getByUsername($username);
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user'] = $user['username'];
                header("Location: index.php?controller=joueurs&action=index");
                exit();
            } else {
                $error = "Identifiants incorrects.";
            }
        }
        require_once "../views/login.php";
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?controller=auth&action=login");
        exit();
    }
}
