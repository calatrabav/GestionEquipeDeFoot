<?php
class JoueursModel {
    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM joueurs ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getById($id) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM joueurs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        global $pdo;
        // On suppose que $data['nom'] est présent
        $stmt = $pdo->prepare("INSERT INTO joueurs (nom) VALUES (?)");
        $stmt->execute([$data['nom']]);
        return $pdo->lastInsertId();
    }

    public static function update($id, $data) {
        global $pdo;
        // On suppose que $data['nom'] est présent
        $stmt = $pdo->prepare("UPDATE joueurs SET nom = ? WHERE id = ?");
        $stmt->execute([$data['nom'], $id]);
        return $stmt->rowCount() > 0;
    }

    public static function delete($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM joueurs WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
