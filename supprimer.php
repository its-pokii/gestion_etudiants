<?php
require_once "functions.php";

exiger_connexion();

$id = (int)($_GET["id"] ?? 0);

$liste = lire_etudiants();

if (isset($liste[$id])) {

    $photo = $liste[$id]["photo"];

    if ($photo != "" && file_exists("uploads/" . $photo)) {
        unlink("uploads/" . $photo);
    }

    unset($liste[$id]);

    sauver_etudiants($liste);

    $_SESSION["message"] = "Étudiant supprimé avec succès";
}

header("Location: liste.php");
exit;
?>