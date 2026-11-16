<?php
require_once "functions.php";

if (connecte()) {
    header("Location: liste.php");
    exit;
}

$erreur = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $login = $_POST["login"] ?? "";
    $mdp = $_POST["mdp"] ?? "";

    if (isset(UTILISATEURS[$login]) && UTILISATEURS[$login] == $mdp) {

        $_SESSION["login"] = $login;

        setcookie("dernier_user", $login, time() + 7 * 24 * 3600, "/");

        header("Location: liste.php");
        exit;

    } else {
        $erreur = "Login ou mot de passe incorrect";
    }
}

$titre = "Connexion";
include "header.php";
?>

<div class="card">
    <h2>Connexion</h2>

    <p class="erreur"><?= h($erreur) ?></p>

    <form method="POST">
        <input type="text" name="login"
               placeholder="Login"
               value="<?= h($_COOKIE["dernier_user"] ?? "") ?>"
               required>

        <input type="password" name="mdp"
               placeholder="Mot de passe" required>

        <button type="submit">Se connecter</button>
    </form>
</div>

<?php include "footer.php"; ?>