<?php
class StatsController {
    public function index() {
        require_once  "../models/MatchModel.php";
        require_once  "../models/JoueursModel.php";
        require_once  "../models/ParticiperModel.php";

        $statsGlobales = MatchModel::getGlobalStats();
        $joueurs = JoueursModel::getAll();
        $statsJoueurs = [];

        foreach ($joueurs as $j) {
            $statsJoueurs[$j['idJoueur']] = MatchModel::getPlayerStats($j['idJoueur']);
            $statsJoueurs[$j['idJoueur']]['matchs_consecutifs'] = ParticiperModel::getConsecutiveMatchCount($j['idJoueur']);
        }

        require_once  "../views/stats/index.php";
    }
}
