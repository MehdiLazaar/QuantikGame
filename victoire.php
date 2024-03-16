<?php
require_once 'class/PieceQuantik.php';
require_once 'class/ArrayPieceQuantik.php';
require_once 'class/PlateauQuantik.php';
require_once 'class/Player.php';
require_once 'class/AbstractGame.php';
require_once 'class/QuantikGame.php';
require_once 'class/AbstractUIGenerator.php';
require_once 'class/QuantikUIGenerator.php';

session_start();
$game =$_SESSION['game'];
if($game->currentPlayer == $game->couleursPlayers[0]->getId()){
    $coulActive = PieceQuantik::WHITE;
}else{
    $coulActive = PieceQuantik::BLACK;
}
echo QuantikUIGenerator::getPageVictoire($game,$coulActive);