<?php
session_start();
require_once "../db_connect.php";
require_once "../controllers/AuthController.php";
require_once "../controllers/JoueursController.php";
require_once "../controllers/MatchsController.php";
require_once "../controllers/SelectionController.php";
require_once "../controllers/StatsController.php";

// Contrôleur et action par défaut
$controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'auth';
$action = isset($_GET['action']) ? $_GET['action'] : 'login';

// Vérification de l'authentification
if ($controllerName !== 'auth' && !isset($_SESSION['user'] && !preg_match('/\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$/', $_SERVER['REQUEST_URI']))) {
    header("Location: index.php?controller=auth&action=login");
    exit();
}

// Instanciation du contrôleur
switch ($controllerName) {
    case 'auth':
        $controller = new AuthController();
        break;
    case 'joueurs':
        $controller = new JoueursController();
        break;
    case 'matchs':
        $controller = new MatchsController();
        break;
    case 'selection':
        $controller = new SelectionController();
        break;
    case 'stats':
        $controller = new StatsController();
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
