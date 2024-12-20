<?php
class MatchModel {
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM matchs ORDER BY date ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM matchs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        global $pdo;
        // On suppose que $data['nom'] et $data['date'] sont présents
        $stmt = $pdo->prepare("INSERT INTO matchs (nom, date) VALUES (?, ?)");
        $stmt->execute([$data['nom'], $data['date']]);
        return $pdo->lastInsertId();
    }

    public static function update($id, $data) {
        global $pdo;
        // On suppose que $data['nom'] et $data['date'] sont présents
        $stmt = $pdo->prepare("UPDATE matchs SET nom = ?, date = ? WHERE id = ?");
        $stmt->execute([$data['nom'], $data['date'], $id]);
        return $stmt->rowCount() > 0;
    }

    public static function delete($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM matchs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
