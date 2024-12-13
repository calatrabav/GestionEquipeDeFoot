<?php
class JoueursModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAllJoueurs() {
        $stmt = $this->pdo->query("SELECT * FROM Joueurs");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getJoueurById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM Joueurs WHERE idJoueur = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addJoueur($nom, $prenom, $statut) {
        $stmt = $this->pdo->prepare("INSERT INTO Joueurs (nomJoueur, prenomJoueur, statut) VALUES (?, ?, ?)");
        return $stmt->execute([$nom, $prenom, $statut]);
    }

    public function updateJoueur($id, $nom, $prenom, $statut) {
        $stmt = $this->pdo->prepare("UPDATE Joueurs SET nomJoueur = ?, prenomJoueur = ?, statut = ? WHERE idJoueur = ?");
        return $stmt->execute([$nom, $prenom, $statut, $id]);
    }

    public function deleteJoueur($id) {
        $stmt = $this->pdo->prepare("DELETE FROM Joueurs WHERE idJoueur = ?");
        return $stmt->execute([$id]);
    }
}
?>
