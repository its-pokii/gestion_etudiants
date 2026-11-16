<?php
require_once "functions.php";
exiger_connexion();

header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=etudiants.csv");

$fp = fopen("php://output", "w");

fputcsv($fp, ["ID", "Nom", "Prenom", "Filiere", "Note", "Photo"]);

$liste = lire_etudiants();

foreach ($liste as $e) {
    fputcsv($fp, $e);
}

fclose($fp);
exit;
?>