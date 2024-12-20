<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de matchs</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f9f9f9;
        }
        .header, .footer {
            background: #333;
            color: #fff;
            padding: 15px;
            text-align: center;
        }
        .nav {
            margin: 20px 0;
            text-align: center;
        }
        .nav a {
            display: inline-block;
            margin: 0 10px;
            color: #333;
            text-decoration: none;
            font-weight: bold;
        }
        .content {
            width: 80%;
            margin: 20px auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
        }
        .footer {
            margin-top: 40px;
        }
        h1, h2, h3 {
            text-align: center;
        }
        .center {
            text-align: center;
        }
        .btn {
            display: inline-block;
            background: #007BFF;
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>
<div class="header">
    <h1>Gestion de matchs</h1>
</div>

<?php if (isset($_SESSION['user'])): ?>
    <div class="nav">
        <a href="index.php?controller=joueurs&action=index">Joueurs</a>
        <a href="index.php?controller=matchs&action=index">Matchs</a>
        <a href="index.php?controller=auth&action=logout">Déconnexion</a>
    </div>
<?php endif; ?>

<div class="content">
