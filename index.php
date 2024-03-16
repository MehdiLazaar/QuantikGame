<?php
ini_set('display_errors', 'on');
require_once 'class/PieceQuantik.php';
require_once 'class/ArrayPieceQuantik.php';
require_once 'class/PlateauQuantik.php';
require_once 'class/Player.php';
require_once 'class/AbstractGame.php';
require_once 'class/QuantikGame.php';
require_once 'class/AbstractUIGenerator.php';
require_once 'class/QuantikUIGenerator.php';
require_once 'PDOQuantik.php';
require_once 'env/db.php';

session_start();

PDOQuantik::initPDO($_ENV['sgbd'],$_ENV['host'],$_ENV['database'],$_ENV['user'],$_ENV['password']);

$player = $_SESSION['player'];
$nom = $player->getName();

try {
    $gamesPlayer = PDOQuantik::getAllGameQuantikByPlayerName($nom);
} catch (Exception $e) {
    echo "Une erreur s'est produite : " . $e->getMessage();
}

$allgames = PDOQuantik::getAllGameQuantik();
$enAttente = array();
$enCours = array();
$finito = array();

foreach($gamesPlayer as $game) {
    switch($game->gameStatus) {
        case 'initialized':
            $enAttente[]=$game;
            break;
        case 'waitingForPlayer':
            $enCours[]=$game;
            break;
        case 'finished':
            $finito[]=$game;
            break;
    }
}

foreach($allgames as $game) {
    switch($game->gameStatus) {
        case 'initialized':
            if($game->currentPlayer != $player->getId()){
                $enAttente[]=$game;
            }
    }
}

$page = "<!DOCTYPE html><html lang='fr'><head>
<meta charset='utf-8' /><title>Quantik</title>
<link rel='stylesheet' href='css/index.css' /></head>";
$page.="<body><form class='login-form' action='".$_SERVER['PHP_SELF']."' method='post'>";
$page .= "
<h1>Quantik</h1>
<h2>Salon de jeux de ".$nom."</h2>";


$page.=" <h3> Nouvelle partie </h3> ";
$page .= "<div class='partie'>";
$page .= "<button type='submit' name='creerPartie'><img src='css/images/addPage.svg' /></button>";
$page .= "</div>";

$page .= "<h3>Parties en cours</h3>";
$page .= "<div class='partie'><table>";
foreach($enCours as $partie) {
    $page .= "<tr>";
    if($partie->currentPlayer == $player->getId()) {
        $page .= "<td class='game'>";
        $page .= "<button type='submit' name='Jouer' value='".$partie->getId()."'>";
        $page .= "<img src='css/images/enter.svg'/>";
        $page .= "</button>";
        $page .= "</td>";
        $page .= "<td class='game'>$partie</td>";
    } else {
        $page .= "<td class='game'>";
        $page .= "<button type='submit' name='Consulter' value='".$partie->getId()."'>";
        $page .= "<img src='css/images/eye.svg'/>";
        $page .= "</button>";
        $page .= "</td>";
        $page .= "<td class='game'>$partie</td>";
    }
    $page .= "</tr>";
}
$page .= "</table></div>";

$page .= "<h3>Parties en attente d'adversaire</h3>";
$page .= "<div class='partie'><table>";
foreach($enAttente as $partie) {
    $page .= "<tr>";
    if($partie->currentPlayer == $player->getId()) {
        $page .= "<td class='game'>";
        $page .= "<button type='submit' name='attente' disabled>";
        $page .= "<img src='css/images/horloge.svg' />";
        $page .= "</button>";
        $page .= "</td>";
        $page .= "<td class='game'>$partie</td>";
    } else {
        $page .= "<td class='game'>";
        $page .= "<button type='submit' name='rejoindrePartie' value='".$partie->getId()."'>";
        $page .= "<img src='css/images/user.svg' />";
        $page .= "</button>";
        $page .= "</td>";
        $page .= "<td class='game'>$partie</td>";
    }
    $page .= "</tr>";
}
$page .= "</table></div>";

$page .= "<h3>Parties terminées</h3>";
$page .= "<div class='partie'><table>";
foreach($finito as $partie) {
    $page .= "<tr>";
    $page .= "<td class= game>";
    $page .= "<button type='submit' name='Consulter' value='".$partie->getId()."'>";
    $page .= "<img src='css/images/eye.svg' />";
    $page .= "</button>";
    $page .= "</td>";
    $page .= "<td class='game'>$partie</td>";
    $page .= "</tr>";
}
$page .= "</table></div>";

$page .= "<div class='partie'>";
$page .= "<button type='submit' name='deconnecter' class='deconnecter-btn'>Déconnecter</button>";
$page .= "</div>";


$page .= "</form></body></html>";
echo $page;

if(isset($_REQUEST['creerPartie'])) {
    $players = array();
    $players[0] = $player;
    $game = new QuantikGame(0, $players);

    PDOQuantik::createGameQuantik($nom,$game->getJson());
    $id = PDOQuantik::getLastInsertId('quantikgame_gameid_seq');
    $game->setId($id);
    $game->setStatus('initialized');
    PDOQuantik::saveGameQuantik('initialized',$game->getJson(),$id);
    header('Refresh: 0; url=index.php');
}

if(isset($_REQUEST['deconnecter'])) {
    header('Location: login.php');
}

if(isset($_REQUEST['rejoindrePartie'])) {
    PDOQuantik::addPlayerToGameQuantik($nom,$_REQUEST['rejoindrePartie']);
    header('Refresh: 0; url=index.php');
}

if(isset($_REQUEST['Jouer'])) {
    $_SESSION['game'] = PDOQuantik::getGameQuantikById($_REQUEST['Jouer']);
    header('Location: choisirPiece.php');
}

if(isset($_REQUEST['Consulter'])) {
    $_SESSION['game'] = PDOQuantik::getGameQuantikById($_REQUEST['Consulter']);
    header('Location: consulter.php');
}

