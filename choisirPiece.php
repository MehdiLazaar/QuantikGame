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

// Démarrer la session
session_start();

// Récupérer l'objet de jeu depuis la session et l'initialiser
$game = QuantikGame::initQuantikGame($_SESSION['game']->getJson());

// Déterminer la couleur active en fonction du joueur actuel
if($game->currentPlayer == $game->couleursPlayers[0]->getId()){
    $coulActive = PieceQuantik::WHITE;
}else{
    $coulActive = PieceQuantik::BLACK;
}
// Générer la page HTML de sélection des pièces
$page = QuantikUIGenerator::getPageSelectionPiece($game,$coulActive);

// Afficher la page générée
echo $page;

// Mettre à jour l'objet de jeu dans la session
$_SESSION['game'] = $game;




