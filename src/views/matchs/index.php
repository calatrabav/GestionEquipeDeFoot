<?php include "../includes/menu.php"; ?>

<h1>Liste des Matchs</h1>

<?php
// Connexion à la base de données avec PDO
try {
    $pdo = new PDO('mysql:host=host;dbname=database;charset=utf8', 'user', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

// Requête pour récupérer les matchs
$query = "SELECT * FROM Matchs";
try {
    $stmt = $pdo->query($query);
    $matchs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Erreur lors de la requête : ' . $e->getMessage());
}

// Vérifier s'il y a des matchs
if (!empty($matchs)):
    ?>
    <table>
        <thead>
        <tr>
            <th>Date</th>
            <th>Heure</th>
            <th>Lieu</th>
            <th>Équipe Adverse</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($matchs as $match): ?>
            <tr>
                <td><?= htmlspecialchars($match['dateMatch']) ?></td>
                <td><?= htmlspecialchars($match['heureMatch']) ?></td>
                <td><?= htmlspecialchars($match['lieuRencontre']) ?></td>
                <td><?= htmlspecialchars($match['nomEquipeAdverse']) ?></td>
                <td>
                    <a href="index.php?controller=matchs&action=modifier&id=<?= $match['idMatch'] ?>">Modifier</a> |
                    <a href="index.php?controller=matchs&action=supprimer&id=<?= $match['idMatch'] ?>"
                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce match ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucun match trouvé.</p>
<?php endif; ?>

<a href="index.php?controller=matchs&action=ajouter">Ajouter un match</a>

<?php
// Fermeture de la connexion
$pdo = null;
?>
