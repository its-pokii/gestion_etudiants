<?php
session_start();

define("FICHIER", __DIR__ . "/data/etudiants.txt");

define("FILIERES", ["GI", "IDIA", "GE", "GC"]);

define("UTILISATEURS", [
    "admin" => "1234",
    "pokii" => "1234"
]);

function h(string $s): string {
    return htmlspecialchars($s);
}

function connecte(): bool {
    return isset($_SESSION["login"]);
}

function exiger_connexion(): void {
    if (!connecte()) {
        header("Location: index.php");
        exit;
    }
}

function lire_etudiants(): array {
    $liste = [];

    if (file_exists(FICHIER)) {
        $lignes = file(FICHIER, FILE_IGNORE_NEW_LINES);

        foreach ($lignes as $ligne) {
            $e = explode(";", $ligne);

            if (count($e) == 6) {
                $liste[$e[0]] = [
                    "id" => $e[0],
                    "nom" => $e[1],
                    "prenom" => $e[2],
                    "filiere" => $e[3],
                    "note" => $e[4],
                    "photo" => $e[5]
                ];
            }
        }
    }

    return $liste;
}

function sauver_etudiants(array $liste): void {
    $lignes = [];

    foreach ($liste as $e) {
        $lignes[] = implode(";", $e);
    }

    file_put_contents(FICHIER, implode("\n", $lignes));
}

function nettoyer(string $s): string {
    return trim(str_replace([";", "\n", "\r"], "", $s));
}
?>