<?php

require_once 'PieceQuantik.php';
require_once 'ArrayPieceQuantik.php';


$apq = ArrayPieceQuantik::initPiecesNoires();
echo $apq;
echo "\n";
$apq = ArrayPieceQuantik::initPiecesBlanches();
echo $apq;
echo "\n";


/* *********************** TRACE d'éxécution de ce programme
(Co:B)(Co:B)(Cu:B)(Cu:B)(Cy:B)(Cy:B)(Sp:B)(Sp:B)
(Co:W)(Co:W)(Cu:W)(Cu:W)(Cy:W)(Cy:W)(Sp:W)(Sp:W)
*********************** */