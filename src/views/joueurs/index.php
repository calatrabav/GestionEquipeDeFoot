<?php include "../includes/menu.php"; ?>
<?php include 'includes/header.php'; ?>

<h1>Liste des joueurs</h1>
<a href="index.php?controller=joueurs&action=ajouter">Ajouter un joueur</a>

<?php
// Connect to the database using PDO
try {
    $pdo = new PDO('mysql:host=host;dbname=database;charset=utf8', 'user', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

// Query the joueurs table
$query = "SELECT * FROM Joueurs";
try {
    $stmt = $pdo->query($query);
    $joueurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Erreur lors de la requête : ' . $e->getMessage());
}

// Display the table if there are players
if (!empty($joueurs)):
    ?>
    <table border="1">
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($joueurs as $joueur): ?>
            <tr>
                <td><?= htmlspecialchars($joueur['nomJoueur']) ?></td>
                <td><?= htmlspecialchars($joueur['prenomJoueur']) ?></td>
                <td><?= htmlspecialchars($joueur['statut']) ?></td>
                <td>
                    <a href="index.php?controller=joueurs&action=modifier&id=<?= $joueur['idJoueur'] ?>">Modifier</a>
                    <a href="index.php?controller=joueurs&action=supprimer&id=<?= $joueur['idJoueur'] ?>"
                       onclick="return confirm('Confirmer ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Aucun joueur trouvé.</p>
<?php endif; ?>

<?php
// Close the database connection
$pdo = null;
?>

<?php include 'src/includes/footer.php'; ?>
