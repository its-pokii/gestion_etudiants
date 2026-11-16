<?php
require_once "functions.php";

exiger_connexion();

$liste = lire_etudiants();

$id = (int)($_GET["id"] ?? 0);
$modification = isset($liste[$id]);

$etudiant = $modification ? $liste[$id] : [
    "nom" => "",
    "prenom" => "",
    "filiere" => "",
    "note" => "",
    "photo" => ""
];

$erreurs = [];

$nom = $etudiant["nom"];
$prenom = $etudiant["prenom"];
$filiere = $etudiant["filiere"];
$note = $etudiant["note"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nom = nettoyer($_POST["nom"] ?? "");
    $prenom = nettoyer($_POST["prenom"] ?? "");
    $filiere = $_POST["filiere"] ?? "";
    $note = $_POST["note"] ?? "";
    $id = (int)($_POST["id"] ?? 0);

    if ($nom == "") $erreurs[] = "Nom obligatoire";
    if ($prenom == "") $erreurs[] = "Prénom obligatoire";

    if (!in_array($filiere, FILIERES)) {
        $erreurs[] = "Filière invalide";
    }

    if (!is_numeric($note) || $note < 0 || $note > 20) {
        $erreurs[] = "Note invalide";
    }

    $photo = $liste[$id]["photo"] ?? "";

    // Upload photo
    if (isset($_FILES["photo"]) &&
        $_FILES["photo"]["error"] != UPLOAD_ERR_NO_FILE) {

        if ($_FILES["photo"]["error"] != UPLOAD_ERR_OK) {
            $erreurs[] = "Erreur d'envoi";
        } else {
            $extension = strtolower(pathinfo(
                $_FILES["photo"]["name"],
                PATHINFO_EXTENSION
            ));

            if (!in_array($extension, ["jpg", "jpeg", "png"])) {
                $erreurs[] = "Extension invalide";
            }

            if ($_FILES["photo"]["size"] > 1024 * 1024) {
                $erreurs[] = "Photo trop grande (1 Mo maximum)";
            }
        }
    }

    if (empty($erreurs)) {

        if (isset($_FILES["photo"]) &&
            $_FILES["photo"]["error"] == UPLOAD_ERR_OK) {

            $nouvellePhoto = uniqid() . "." . $extension;

            move_uploaded_file(
                $_FILES["photo"]["tmp_name"],
                "uploads/" . $nouvellePhoto
            );

            if ($photo != "" && file_exists("uploads/" . $photo)) {
                unlink("uploads/" . $photo);
            }

            $photo = $nouvellePhoto;
        }

        if ($id > 0 && isset($liste[$id])) {

            // Modification
            $liste[$id] = [
                "id" => $id,
                "nom" => $nom,
                "prenom" => $prenom,
                "filiere" => $filiere,
                "note" => $note,
                "photo" => $photo
            ];

            $_SESSION["message"] = "Étudiant modifié avec succès";

        } else {

            // Ajout
            $id = empty($liste) ? 1 : max(array_keys($liste)) + 1;

            $liste[$id] = [
                "id" => $id,
                "nom" => $nom,
                "prenom" => $prenom,
                "filiere" => $filiere,
                "note" => $note,
                "photo" => $photo
            ];

            $_SESSION["message"] = "Étudiant ajouté avec succès";
        }

        sauver_etudiants($liste);

        header("Location: liste.php");
        exit;
    }
}

$titre = "Ajouter / Modifier";
include "header.php";
?>

<div class="card">

    <h2><?= $modification ? "Modifier" : "Ajouter" ?> un étudiant</h2>

    <?php foreach ($erreurs as $erreur): ?>
        <p class="erreur"><?= h($erreur) ?></p>
    <?php endforeach; ?>

    <form method="POST" enctype="multipart/form-data">

        <input type="hidden" name="id" value="<?= $id ?>">

        <input type="text" name="nom"
               placeholder="Nom" value="<?= h($nom) ?>">

        <input type="text" name="prenom"
               placeholder="Prénom" value="<?= h($prenom) ?>">

        <select name="filiere">
            <option value="">Choisir une filière</option>

            <?php foreach (FILIERES as $f): ?>
                <option value="<?= $f ?>"
                    <?= $filiere == $f ? "selected" : "" ?>>
                    <?= $f ?>
                </option>
            <?php endforeach; ?>
        </select>

        <input type="number" step="0.01" name="note"
               placeholder="Note" value="<?= h($note) ?>">

        <label>Photo :</label>
        <input type="file" name="photo" accept=".jpg,.jpeg,.png">

        <button type="submit">
            <?= $modification ? "Modifier" : "Ajouter" ?>
        </button>

    </form>
</div>

<?php include "footer.php"; ?>