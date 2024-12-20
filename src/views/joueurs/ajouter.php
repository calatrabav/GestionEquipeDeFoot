<?php require_once "../views/layout/header.php"; ?>

<h2>Ajouter un joueur</h2>
<form action="index.php?controller=joueurs&action=ajouter" method="POST" style="text-align:center;">
    <input type="text" name="nom" placeholder="Nom du joueur" required style="padding:10px; width:300px;"><br><br>
    <button type="submit" class="btn">Ajouter</button>
</form>

<?php require_once "../views/layout/footer.php"; ?>
