<?php
class StatsController {
    public function index() {
        require_once __DIR__ . "/../models/MatchModel.php";
        require_once __DIR__ . "/../models/JoueursModel.php";
        require_once __DIR__ . "/../models/ParticiperModel.php";

        // Stats globales
        $statsGlobales = MatchModel::getGlobalStats();

        // Stats par joueur
        $joueurs = JoueursModel::getAll();
        $statsJoueurs = [];
        foreach ($joueurs as $j) {
            $statsJoueurs[$j['idJoueur']] = MatchModel::getPlayerStats($j['idJoueur']);
            // On peut ajouter un count de matchs consécutifs, etc.
            $statsJoueurs[$j['idJoueur']]['matchs_consecutifs'] = ParticiperModel::getConsecutiveMatchCount($j['idJoueur']);
        }

        // Charger la vue
        require_once "../views/stats/index.php";
    }
}
