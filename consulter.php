<?php

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

// Démarrage de la session
session_start();

PDOQuantik::initPDO($_ENV['sgbd'],$_ENV['host'],$_ENV['database'],$_ENV['user'],$_ENV['password']);
$game = QuantikGame::initQuantikGame($_SESSION['game']->getJson());

if (isset($_GET['poserPlateau'])){
    $tableau = explode("-", $_GET['poserPlateau']);
    $x = (int)$tableau[0];
    $y = (int)$tableau[1]; 
    $position = $_SESSION['positionPiece'];

    if($game->plateau->getPiece($x,$y)==PieceQuantik::initVoid()){
        if($game->currentPlayer == $game->couleursPlayers[0]->getId()){
            $piece = $game->piecesBlanches->getPieceQuantik($position);
            $game->piecesBlanches->removePieceQuantik($position);
            $game->plateau->setPiece($x,$y,$piece);
            $actions = new ActionQuantik($game->plateau);
            PDOQuantik::saveGameQuantik('waitingForPlayer',$game->getJson(),$game->getId());
            if($actions->isColWin($y) || $actions->isRowWin($x) || $actions->isCornerWin($game->plateau->getCornerFromCoord($x,$y))){
                $game->setStatus('finished');
                PDOQuantik::saveGameQuantik('finished',$game->getJson(),$game->getId());
                $_SESSION['game'] = $game;
                header("Location: victoire.php");
                exit();
            }else{
                $game->currentPlayer = $game->couleursPlayers[1]->getId();
                PDOQuantik::saveGameQuantik('waitingForPlayer',$game->getJson(),$game->getId());
            }
        }else{
            $piece = $game->piecesNoires->getPieceQuantik($position);
            $game->piecesNoires->removePieceQuantik($position);
            $game->plateau->setPiece($x,$y,$piece);
            $actions=new ActionQuantik($game->plateau);
            PDOQuantik::saveGameQuantik('waitingForPlayer',$game->getJson(),$game->getId());
            if($actions->isColWin($y) || $actions->isRowWin($x) || $actions->isCornerWin($game->plateau->getCornerFromCoord($x,$y))){
                $game->setStatus('finished');
                PDOQuantik::saveGameQuantik('finished',$game->getJson(),$game->getId());
                $_SESSION['game'] = $game;
                header("Location: victoire.php");
                exit();
            }else{
                $game->currentPlayer = $game->couleursPlayers[0]->getId();
                PDOQuantik::saveGameQuantik('waitingForPlayer',$game->getJson(),$game->getId());
            }
        }
    }
}
if($game->currentPlayer == $game->couleursPlayers[0]->getId()){
    $coulActive = PieceQuantik::WHITE;
}else{
    $coulActive = PieceQuantik::BLACK;
}

if($game->gameStatus == 'finished'){
    header("Location: victoire.php");
    exit();
}

echo QuantikUIGenerator::getPageConsulterPartie($game, $coulActive);