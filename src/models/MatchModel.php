<?php
require_once "../db_connect.php";

class MatchModel {
    public static function getAll() {
        $db = db_connect();
        $query = $db->query("SELECT * FROM Matchs");
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ajouter($data) {
        $db = db_connect();
        $query = $db->prepare("INSERT INTO Matchs (dateMatch, heureMatch, lieuRencontre, nomEquipeAdverse) VALUES (:dateMatch, :heureMatch, :lieuRencontre, :nomEquipeAdverse)");
        $query->execute($data);
    }

    public static function supprimer($id) {
        $db = db_connect();
        $query = $db->prepare("DELETE FROM Matchs WHERE idMatch = :id");
        $query->execute(['id' => $id]);
    }

    public static function getById($id) {
        $db = db_connect();
        $query = $db->prepare("SELECT * FROM Matchs WHERE idMatch = :id");
        $query->execute(['id' => $id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public static function modifier($id, $data) {
        $db = db_connect();
        $query = $db->prepare("UPDATE Matchs SET dateMatch = :dateMatch, heureMatch = :heureMatch, lieuRencontre = :lieuRencontre, nomEquipeAdverse = :nomEquipeAdverse WHERE idMatch = :id");
        $data['id'] = $id;
        $query->execute($data);
    }
}
?>
