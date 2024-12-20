<?php
class UsersModel {
    public static function getByUsername($username) {
        global $pdo;
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        global $pdo;
        // On suppose que $data['username'] et $data['password'] existent
        // $data['password'] devrait être un mot de passe haché avec password_hash()
        $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->execute([$data['username'], $data['password']]);
        return $pdo->lastInsertId();
    }

    public static function update($id, $data) {
        global $pdo;
        // Mettre à jour username et/ou password
        $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ? WHERE id = ?");
        $stmt->execute([$data['username'], $data['password'], $id]);
        return $stmt->rowCount() > 0;
    }

    public static function delete($id) {
        global $pdo;
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
