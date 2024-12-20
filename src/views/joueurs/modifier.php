<?php require_once "../views/layout/header.php"; ?>

<h2>Modifier un joueur</h2>
<!-- Ici, on récupérerait les infos du joueur via JoueursModel::getById($id) -->
<form action="index.php?controller=joueurs&action=modifier&id=<?= isset($_GET['id']) ? $_GET['id'] : '' ?>" method="POST" style="text-align:center;">
    <input type="text" name="nom" placeholder="Nouveau nom" required style="padding:10px; width:300px;"><br><br>
    <button type="submit" class="btn">Modifier</button>
</form>

<?php require_once "../views/layout/footer.php"; ?>
