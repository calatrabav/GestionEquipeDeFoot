<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion Matchs</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <h1>Gestion d'une équipe de sport</h1>
</header>
<?php if (isset($_SESSION['user'])): ?>
    <?php require_once "menu.php"; ?>
<?php endif; ?>
<div class="content">
