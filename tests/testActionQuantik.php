<?php
require_once '../class/PieceQuantik.php';
require_once '../class/ArrayPieceQuantik.php';
require_once '../class/PlateauQuantik.php';
require_once '../class/ActionQuantik.php';

$plateau = new PlateauQuantik();
$mainNoire = ArrayPieceQuantik::initPiecesNoires();
$mainBlanche = ArrayPieceQuantik::initPiecesBlanches();
$actions = new ActionQuantik($plateau);

echo $actions;
echo "état main noire : ".$mainNoire."<br/>";
echo "état main blanche : ".$mainBlanche."<br/>";

echo "test de pose d'un élément Cube Noir en case (1,1) : <br/>"; 
if($actions->isValidePose(1,1,$mainNoire[3])){
    $actions->posePiece(1,1,$mainNoire[3]);
    $mainNoire->removePieceQuantik(3);
}else{
    echo "on ne peut pas poser".$mainNoire[3]." ici ! <br/>";
}



echo $actions;
echo "état main noire : ".$mainNoire."<br/>";
echo "état main blanche : ".$mainBlanche."<br/>";
echo "test de pose d'un élément Cylindre Blanc en case (3,1) : <br/>";
if($actions->isValidePose(3,1,$mainBlanche[5])){
    $actions->posePiece(3,1,$mainBlanche[5]);
    $mainBlanche->removePieceQuantik(5);
}else{
    echo "on ne peut pas poser".$mainBlanche[5]." ici ! <br/>";
}


echo $actions;
echo "état main noire : ".$mainNoire."<br/>";
echo "état main blanche : ".$mainBlanche."<br/>";
echo "test de pose d'un élément Cylindre Noir en case (3,2) : <br/>";
if($actions->isValidePose(3,2,$mainNoire[4])){
    $actions->posePiece(3,2,$mainNoire[4]);
    $mainNoire->removePieceQuantik(4);
}else{
    echo "on ne peut pas poser".$mainNoire[4]." ici ! <br/>";
}

echo $actions;
echo "état main noire : ".$mainNoire."<br/>";
echo "état main blanche : ".$mainBlanche."<br/>";
echo "test de pose d'un élément Cylindre Blanc en case (3,2) : <br/>";
if($actions->isValidePose(3,2,$mainBlanche[4])){
    $actions->posePiece(3,2,$mainBlanche[4]);
    $mainBlanche->removePieceQuantik(4);
}else{
    echo "on ne peut pas poser".$mainBlanche[4]." ici ! <br/>";
}

echo $actions;
echo "état main noire : ".$mainNoire."<br/>";
echo "état main blanche : ".$mainBlanche."<br/>";

echo "test fin de partie : <br/>";
$actions->posePiece(2,1,$mainBlanche[0]);
$mainBlanche->removePieceQuantik(0);
$actions->posePiece(0,1,$mainNoire[5]);
$mainNoire->removePieceQuantik(5);
echo $actions;
echo "état main noire : ".$mainNoire."<br/>";
echo "état main blanche : ".$mainBlanche."<br/>";

if($actions->isColWin(1)){
    echo "gagné col 1";
}
if($actions->isColWin(0)){
    echo "gagné col 0";
}
echo "test fin de partie : <br/>";
$actions->posePiece(0,0,$mainNoire[0]);
$mainNoire->removePieceQuantik(0);
$actions->posePiece(1,0,$mainNoire[3]);
$mainNoire->removePieceQuantik(3);
echo $actions;
echo "état main noire : ".$mainNoire."<br/>";
echo "état main blanche : ".$mainBlanche."<br/>";
if($actions->isRowWin(1)){
    echo "gagné row 1";
}
if($actions->isCornerWin(PlateauQuantik::NW)){
    echo "gagné corner NW";
}