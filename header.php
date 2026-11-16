<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $titre ?? "Gestion étudiants" ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <h2>Gestion des étudiants</h2>

    <?php if (connecte()): ?>
        <nav>
            <a href="liste.php">Liste</a>
            <a href="ajouter.php">Ajouter</a>
            <a href="logout.php">Déconnexion</a>
        </nav>
    <?php endif; ?>
</header>

<main></main>