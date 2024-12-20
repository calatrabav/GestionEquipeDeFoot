<?php
class StatsController {
    public function index() {
        require_once  "../models/MatchModel.php";
        require_once  "../models/JoueursModel.php";

        $statsGlobales = MatchModel::getGlobalStats();
        $joueurs = JoueursModel::getAll();
        $statsJoueurs = [];
        foreach ($joueurs as $j) {
            $statsJoueurs[$j['idJoueur']] = MatchModel::getPlayerStats($j['idJoueur']);
        }

        require_once  "../views/stats/index.php";
    }
}
