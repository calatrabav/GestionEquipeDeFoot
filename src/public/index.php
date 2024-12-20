<?php
session_start(); // Démarrage de la session

require_once "../db_connect.php";
require_once "../controllers/AuthController.php";
require_once "../controllers/JoueursController.php";
require_once "../controllers/MatchsController.php";

// Contrôleur et action par défaut
$controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'auth';
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

// Instanciation du contrôleur
switch ($controllerName) {
    case 'auth':
        $controller = new AuthController();
        break;
    case 'joueurs':
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
        $controller = new JoueursController();
        break;
    case 'matchs':
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?controller=auth&action=login");
            exit();
        }
        $controller = new MatchsController();
        break;
    default:
        die("Contrôleur inconnu !");
}

// Appel de l'action
if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    die("Action inconnue !");
}
