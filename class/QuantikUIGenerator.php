<?php
require_once 'ActionQuantik.php';
class QuantikUIGenerator extends AbstractUIGenerator

{
    // Méthode statique pour générer la section des pièces disponibles
    public static function getDivPiecesDisponibles(ArrayPieceQuantik $array, int $pos = -1): string {
    $resultat ="<div class='piecesDispo'>";
    for($i = 0; $i < count($array) ; $i++) {
        $getClass = self::getButtonClass($array[$i]);
        $buttonCode = "<button class='$getClass' type='submit' name='active' disabled>";
        $buttonCode .= "<img src='css/images/$getClass.png'/></button> <br>";
        $resultat .= $buttonCode;
    }
    $resultat .= '</div>';
    return $resultat;
}

// Méthode statique pour générer le formulaire de sélection d'une pièce
public static function getFormSelectionPiece(ArrayPieceQuantik $array): string {
    $resultat = "<div class='selectPiece'>";
    $resultat .= "<form action='poserPiece.php' method='get'>";

    for($i = 0; $i < $array->count(); $i++) {
        $getClass = self::getButtonClass($array[$i]);
        $resultat .= "<button class='$getClass' type='submit' name='position_piece' value='$i'><img src='css/images/$getClass.png'/></button> <br>";
    }
    $resultat .= "</form></div>";

    return $resultat;
}
// Méthode statique pour générer la section du plateau de jeu Quantik
public static function getDivPlateauQuantik(PlateauQuantik $plateau): string {
    $resultat = "<div class='plateau'>";
    $resultat .= "<table>";
    for ($i = 0; $i < PlateauQuantik::NBROWS; $i++) {
        $resultat .= "<tr id='ligne'>";
        for ($j = 0; $j < PlateauQuantik::NBCOLS; $j++) {
            $getClass = self::getButtonClass($plateau->getPiece($i,$j));
            $image = $plateau->getPiece($i,$j);
            if($getClass!="vide"){
                $image = "<img src='css/images/$getClass.png'/>";
            }
            $resultat .= "<td class='nonVide' id='case'>$image</td>";
        }
        $resultat .= "</tr>"; // Fin de la ligne
    }
    $resultat .= "</table></div>"; // Fin du plateau
    return $resultat;
}

// Méthode statique pour générer le formulaire du plateau de jeu Quantik
public static function getFormPlateauQuantik(PlateauQuantik $plateau, PieceQuantik $piece): string {
    $sRet = "<form action='consulter.php' method='get'>";
    $actions = new ActionQuantik($plateau);
    $void = PieceQuantik::initVoid();
    $sRet .= "<table>";
    for ($i = 0; $i < $plateau::NBROWS; $i++) {
        $sRet .= "<tr id='ligne'>";
        
        for ($j = 0; $j < $plateau::NBCOLS; $j++) {
            if($plateau->getPiece($i,$j)==$void){
                if($actions->isValidePose($i,$j,$piece)){
                    $sRet .="<td id='case'class='validePose'><button type'submit' name='poserPlateau' value='$i-$j'>".$plateau->getPiece($i,$j)."</button>";
                }else{
                    $sRet .="<td id='case'class='nonValide'><button type'submit' name='nonValide' disabled>".$plateau->getPiece($i,$j)."</button>";
                }
            }else{
                $getClass = self::getButtonClass($plateau->getPiece($i,$j));
                $sRet .="<td id='case'class='nonVide'><button type'submit' name='nonVide' disabled><img src='css/images/$getClass.png'/></button>";
            }
                
            $sRet .= "</td>";
        }
        $sRet .= "</tr>"; 
    }

    $sRet .= "</table></form>";
    return $sRet;
}

    // Méthode statique pour obtenir la classe CSS pour un bouton en fonction de la pièce
    public static function getButtonClass(PieceQuantik $pq) {
        return ($pq->getForme() === PieceQuantik::VOID) ?
            "vide" :
            (($pq->getForme() === PieceQuantik::CUBE) ?
                "cube_" :
                (($pq->getForme() === PieceQuantik::CONE) ?
                    "cone_" :
                    (($pq->getForme() === PieceQuantik::CYLINDRE) ?
                        "cylindre_" :
                        (($pq->getForme() === PieceQuantik::SPHERE) ?
                            "sphere_" :
                            "")))) .
            ($pq->getCouleur() === PieceQuantik::WHITE ? "white" : "black");
    }

    // Méthode statique pour générer le formulaire d'annulation du choix de la pièce
    public static function getFormBoutonAnnulerChoixPiece(): string {
        $form = "<form action=\"choisirPiece.php\" method=\"post\">";
        $form .= "<input type=\"hidden\" name=\"action\" value=\"annulerChoixPiece\">";
        $form .= "<button class='bouton-annuler' type=\"submit\">Annuler le choix de la pièce</button>";
        $form .= "</form>";
        return $form;
    }
    // Méthode statique pour générer le bouton de retour à la page d'accueil
    public static function getFormBoutonHome():string{
        $form="<form action='index.php' method='post'>";
        $form.="<button type='submit' class='bouton-accueil'>Home</button>";
        $form.="</form>";
        return $form;
    }

    // Méthode statique pour générer la page d'erreur avec un message spécifique
public static function getPageErreur(string $message): string
    {
        header("HTTP/1.1 400 Bad Request");
        $resultat = self::getDebutHTML("400 Bad Request");
        $resultat .= "<h2>$message</h2>";
        $resultat .= self::getLienRecommencer();
        $resultat .= self::getFinHTML();
        return $resultat;
    }

    // Méthode statique pour générer la section du message de victoire
    public static function getDivMessageVictoire(int $couleur) : string {

        $resultat ="";
        if($couleur == PieceQuantik::BLACK){
            $resultat .= "<div class='blkVictory'> Les Noirs ont gagné la partie 🖤 ";
        }elseif ($couleur == PieceQuantik::WHITE){
            $resultat .=  "<div class='whtVictory'> Les Blancs ont gagné la partie 🤍 ";
        }
        $resultat .= self::getFormBoutonHome()."</div>";
        return $resultat;
    }

    // Méthode statique pour obtenir le lien pour recommencer la partie
    public static function getLienRecommencer():string {
        return "<form action='login.php' method='post'>
        <input type=\"hidden\" name=\"recommencer\" value=\"recommencer\">
        <button class='replay' type='submit'>Recommencer ?</button></form>";
    }

    // Méthode statique pour générer la page de sélection de pièce
    public static function getPageSelectionPiece(QuantikGame $quantik, int $couleurActive): string {

    $pageHTML = QuantikUIGenerator::getDebutHTML();
    $pageHTML .= '<table class=jeu>';
        $pageHTML.= "<tr><th>Pieces Blanches </th><th></th><th>Pieces Noires </th></tr>";
    if ($couleurActive == PieceQuantik::BLACK) {
        $pageHTML .= "<tr><td >".self::getDivPiecesDisponibles($quantik->piecesBlanches, true)."</td>";
        $pageHTML .= "<td rowspan=3>".self::getDivPlateauQuantik($quantik->plateau)."</td>";
        $pageHTML .= "<td>". self::getFormSelectionPiece($quantik->piecesNoires)."</td>";

    } elseif ($couleurActive == PieceQuantik::WHITE) {
    $pageHTML .= "<tr><td >".self::getFormSelectionPiece($quantik->piecesBlanches)."</td>";
        $pageHTML .= "<td rowspan=3>".self::getDivPlateauQuantik($quantik->plateau)."</td>";
        $pageHTML .= self::getFormBoutonHome();

        $pageHTML .= "<td>". self::getDivPiecesDisponibles($quantik->piecesNoires, false)."</td></tr>";
    }

    $pageHTML .= '</table>'.self::getFinHTML();
    return $pageHTML;
}
//Cette méthode génère la page HTML permettant de poser une pièce sur le plateau de jeu.
public static function getPagePosePiece(QuantikGame $quantik, int $couleurActive, int $posSelection): string {
    $pageHTML = QuantikUIGenerator::getDebutHTML();
    $pageHTML .= '<table class=jeu>';
    $pageHTML.= "<tr><th>Pieces Blanches </th><th></th><th>Pieces Noires </th></tr>";
    $piece = PieceQuantik::initVoid();
    $pageHTML .= "<tr><td >".self::getDivPiecesDisponibles($quantik->piecesBlanches)."</td>";

    if ($couleurActive == PieceQuantik::WHITE) {
        $piece = $quantik->piecesBlanches->getPieceQuantik($posSelection);
    } else{
        $piece = $quantik->piecesNoires->getPieceQuantik($posSelection);
    }

    $pageHTML .= "<td rowspan=3>".self::getFormPlateauQuantik($quantik->plateau, $piece)."</td>"
    .self::getFormBoutonAnnulerChoixPiece()  .self::getFormBoutonHome();
    $pageHTML .= "<td>". self::getDivPiecesDisponibles($quantik->piecesNoires)."</td></tr>"
    . self::getFinHTML();

    return $pageHTML;
}
//Cette méthode génère la page HTML pour consulter une partie en cours. Elle affiche les pièces disponibles pour les joueurs, le plateau de jeu avec les pièces déjà posées, et les pièces restantes pour chaque joueur.
public static function getPageConsulterPartie(QuantikGame $quantik, int $couleurActive): string {
    $pageHTML = QuantikUIGenerator::getDebutHTML();
    $pageHTML .= '<table class=jeu>';
    $pageHTML.= "<tr><th>Pieces Blanches </th><th></th><th>Pieces Noires </th></tr>";
    $pageHTML .= "<tr><td >".self::getDivPiecesDisponibles($quantik->piecesBlanches)."</td>";
    $pageHTML .= "<td rowspan=3>".self::getDivPlateauQuantik($quantik->plateau)."</td>"
    .self::getFormBoutonHome();
    $pageHTML .= "<td>". self::getDivPiecesDisponibles($quantik->piecesNoires)."</td></tr>"
    . self::getFinHTML();

    return $pageHTML;

}
//Cette fonction génère la page HTML affichant un message de victoire pour l'équipe ayant remporté la partie, accompagné du plateau de jeu final. Un bouton "Home" est également inclus pour permettre à l'utilisateur de retourner à la page d'accueil.
public static function getPageVictoire(QuantikGame $quantik, int $couleurActive): string {
    $pageHTML = QuantikUIGenerator::getDebutHTML();
    $pageHTML .= '<table class=jeu><tr><td>';
    $pageHTML .= self::getDivMessageVictoire($couleurActive)."</td></tr>"
        ."<td>". self::getDivPlateauQuantik($quantik->plateau)."</td></tr>"
        .self::getFormBoutonHome()
        ."</table>". self::getFinHTML();

    return $pageHTML;
}
}
    