<?php
require_once "functions.php";

exiger_connexion();

$liste = lire_etudiants();

$q = $_GET["q"] ?? "";
$tri = $_GET["tri"] ?? "ajout";

// Recherche
if ($q != "") {
    $liste = array_filter($liste, function($e) use ($q) {
        return stripos($e["nom"], $q) !== false ||
               stripos($e["prenom"], $q) !== false;
    });
}

// Tri
if ($tri == "nom") {
    usort($liste, fn($a, $b) => strcasecmp($a["nom"], $b["nom"]));

} elseif ($tri == "filiere") {
    usort($liste, fn($a, $b) => strcasecmp($a["filiere"], $b["filiere"]));

} elseif ($tri == "note") {
    usort($liste, fn($a, $b) => $b["note"] <=> $a["note"]);

} else {
    usort($liste, fn($a, $b) => $a["id"] <=> $b["id"]);
}

// Statistiques
$nombre = count($liste);

if ($nombre > 0) {
    $notes = array_column($liste, "note");

    $moyenne = array_sum($notes) / $nombre;
    $meilleure = max($notes);
} else {
    $moyenne = 0;
    $meilleure = 0;
}

$titre = "Liste des étudiants";
include "header.php";
?>



<h2>Liste des étudiants</h2>

<?php
if (isset($_SESSION["message"])) {
    echo "<p class='success'>" . h($_SESSION["message"]) . "</p>";
    unset($_SESSION["message"]);
}
?>

<form method="GET">
    <input type="text" name="q"
           id="recherche"
           placeholder="Rechercher..."
           value="<?= h($q) ?>">

    <select name="tri">
        <option value="ajout" <?= $tri == "ajout" ? "selected" : "" ?>>Ordre d'ajout</option>
        <option value="nom" <?= $tri == "nom" ? "selected" : "" ?>>Nom</option>
        <option value="filiere" <?= $tri == "filiere" ? "selected" : "" ?>>Filière</option>
        <option value="note" <?= $tri == "note" ? "selected" : "" ?>>Note décroissante</option>
    </select>

    <button type="submit">Rechercher</button>
</form>

<h3>Statistiques</h3>

<p>Nombre : <?= $nombre ?></p>
<p>Moyenne : <?= number_format($moyenne, 2) ?></p>
<p>Meilleure note : <?= $meilleure ?></p>

<table>
    <tr>
        <th>Photo</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Filière</th>
        <th>Note</th>
        <th>Actions</th>
    </tr>

    <?php if ($nombre == 0): ?>
        <tr><td colspan="6">Aucun étudiant</td></tr>
    <?php endif; ?>

    <?php foreach ($liste as $e): ?>
        <tr>
            <td>
                <?php if ($e["photo"] != ""): ?>
                    <img src="uploads/<?= h($e["photo"]) ?>" width="50">
                <?php endif; ?>
            </td>

            <td><?= strtoupper(h($e["nom"])) ?></td>
            <td><?= ucfirst(h($e["prenom"])) ?></td>
            <td><?= h($e["filiere"]) ?></td>

            <td class="<?= $e["note"] >= 10 ? 'vert' : 'rouge' ?>">
                <?= h($e["note"]) ?>
            </td>

            <td>
                <a href="ajouter.php?id=<?= $e["id"] ?>">Modifier</a>
                <a href="supprimer.php?id=<?= $e["id"] ?>" class="delete">Supprimer</a>
                
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<br><br>
<a href="export.php"> <button type="button">Exporter CSV</button> </a>

<?php include "footer.php"; ?>