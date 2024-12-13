<?php include "../includes/menu.php"; ?>

<h1>Modifier un Match</h1>

<?php
try {
    $pdo = new PDO('mysql:host=host;dbname=database;charset=utf8', 'user', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

if (isset($_GET['idMatch']) && ctype_digit($_GET['id'])) {
    $idMatch = $_GET['idMatch'];
    $query = "SELECT * FROM Matchs WHERE idMatch = :idMatch";
    try {
        $stmt = $pdo->prepare($query);
        $stmt->execute(['idMatch' => $idMatch]);
        $match = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$match) {
            die("Aucun match trouvé pour cet ID.");
        }
    } catch (PDOException $e) {
        die('Erreur lors de la récupération du match : ' . $e->getMessage());
    }
} else {
    die("ID de match invalide.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dateMatch = isset($_POST['dateMatch']) ? $_POST['dateMatch'] : '';
    $heureMatch = isset($_POST['heureMatch']) ? $_POST['heureMatch'] : '';
    $lieuRencontre = isset($_POST['lieuRencontre']) ? $_POST['lieuRencontre'] : '';
    $nomEquipeAdverse = isset($_POST['nomEquipeAdverse']) ? $_POST['nomEquipeAdverse'] : '';

    if (!empty($dateMatch) && !empty($heureMatch) && !empty($lieuRencontre) && !empty($nomEquipeAdverse)) {
        $updateQuery = "
            UPDATE Matchs
            SET dateMatch = :dateMatch,
                heureMatch = :heureMatch,
                lieuRencontre = :lieuRencontre,
                nomEquipeAdverse = :nomEquipeAdverse
            WHERE idMatch = :idMatch
        ";
        try {
            $stmt = $pdo->prepare($updateQuery);
            $stmt->execute([
                'dateMatch' => $dateMatch,
                'heureMatch' => $heureMatch,
                'lieuRencontre' => $lieuRencontre,
                'nomEquipeAdverse' => $nomEquipeAdverse,
                'idMatch' => $idMatch
            ]);
            echo "<p>Le match a été modifié avec succès.</p>";
        } catch (PDOException $e) {
            die('Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    } else {
        echo "<p>Tous les champs sont obligatoires.</p>";
    }
}
?>

<form method="POST" action="index.php?controller=matchs&action=modifier&id=<?= htmlspecialchars($match['idMatch']) ?>">
    <label>Date :</label>
    <input type="date" name="dateMatch" value="<?= htmlspecialchars($match['dateMatch']) ?>" required><br>

    <label>Heure :</label>
    <input type="time" name="heureMatch" value="<?= htmlspecialchars($match['heureMatch']) ?>" required><br>

    <label>Lieu :</label>
    <input type="text" name="lieuRencontre" value="<?= htmlspecialchars($match['lieuRencontre']) ?>" required><br>

    <label>Équipe Adverse :</label>
    <input type="text" name="nomEquipeAdverse" value="<?= htmlspecialchars($match['nomEquipeAdverse']) ?>" required><br>

    <button type="submit">Modifier</button>
</form>
