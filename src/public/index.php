<?php
require_once "../controllers/AuthController.php";
require_once "../controllers/JoueursController.php";
require_once "../controllers/MatchsController.php";

$controller = isset($_GET['controller']) ? $_GET['controller'] : 'auth'; // Contrôleur par défaut : auth
$action = isset($_GET['action']) ? $_GET['action'] : 'login'; // Action par défaut : login

switch ($controller) {
    case 'auth':
        $controller = new AuthController();
        break;
    case 'joueurs':
        $controller = new JoueursController();
        break;
    case 'matchs':
        $controller = new MatchsController();
        break;
    default:
        die("Contrôleur inconnu !");
}

if (method_exists($controller, $action)) {
    $controller->$action();
} else {
    die("Action inconnue !");
}
?>
