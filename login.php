<?php
ini_set('display_errors', 'off');
require_once 'PDOQuantik.php';

session_start();

function getPageLogin(): string {
    $form = '<!DOCTYPE html>
    <html class="no-js" lang="fr" dir="ltr">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="css/login.css" />
        <title>Accès à la salle de jeux</title>
    </head>
    <body>
        <div class="container">
            <div class="quantik"></div>
            <h1>Accès au salon Quantik</h1>
            <h2>Identification du joueur</h2>
            <form action="'.$_SERVER['PHP_SELF']. '" method="post" class="login-form" onsubmit="return validateForm()">
                <fieldset>
                    <legend>Nom</legend>
                    <input type="text" name="playerName" id="playerName" />
                    <input type="submit" name="action" value="connecter" class="btn-connect">
                </fieldset>
            </form>
        </div>
        
        <script>
            function validateForm() {
                var playerName = document.getElementById("playerName").value;
                if (playerName.trim() === "") {
                    alert("entrez votre nom svp");
                    return false;
                }
                return true;
            }
        </script>
    </body>
    </html>';
    return $form;
}

if (isset($_REQUEST['playerName'])) {
    // Connexion à la base de données
    require_once 'env/db.php';
    PDOQuantik::initPDO($_ENV['sgbd'],$_ENV['host'],$_ENV['database'],$_ENV['user'],$_ENV['password']);

    // Recherche du joueur par nom
    $player = PDOQuantik::selectPlayerByName($_REQUEST['playerName']);

    // Si le joueur n'existe pas, le créer
    if (is_null($player))
        $player = PDOQuantik::createPlayer($_REQUEST['playerName']);

    // Stocker le joueur dans la session
    $_SESSION['player'] = $player;

    // Rediriger vers la page principale
    header('HTTP/1.1 303 See Other');
    header('Location: index.php');
} else {
    echo getPageLogin();
}
