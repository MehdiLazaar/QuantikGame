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

$game = QuantikGame::initQuantikGame($_SESSION['game']->getJson());
$position = $_GET['position_piece'];

if($game->currentPlayer == $game->couleursPlayers[0]->getId()){
    $coulActive = PieceQuantik::WHITE;
}else{
    $coulActive = PieceQuantik::BLACK;
}
$page = QuantikUIGenerator::getPagePosePiece($game,$coulActive,$position);
echo $page;

$_SESSION['game']=$game;
$_SESSION['positionPiece']=$position;


