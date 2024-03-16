<?php
// Définition de la classe ActionQuantik
class ActionQuantik{
    // Attribut protégé contenant le plateau de jeu
    protected PlateauQuantik $plateau;

    // Constructeur de la classe prenant en paramètre un objet PlateauQuantik
    public function __construct(PlateauQuantik $plateau){
        $this->plateau = $plateau;
    }

    // Méthode pour obtenir le plateau de jeu
    public function getPlateau():PlateauQuantik{
        return $this->plateau;
    }

    // Méthode pour vérifier si une ligne est gagnante
    public function isRowWin(int $numRow):bool{
        return $this::isComboWin($this->plateau->getRow($numRow));
    }

    // Méthode pour vérifier si une colonne est gagnante
    public function isColWin(int $numCol):bool{
        return $this::isComboWin($this->plateau->getCol($numCol));
    }

    // Méthode pour vérifier si un coin est gagnant
    public function isCornerWin(int $dir):bool{
        return $this::isComboWin($this->plateau->getCorner($dir));
    }

    // Méthode pour vérifier si la pose d'une pièce est valide
    public function isValidePose(int $rowNum, int $colNum, PieceQuantik $piece): bool {
        $corner = PlateauQuantik::getCornerFromCoord($rowNum, $colNum);
        $void = PieceQuantik::initVoid();

        if ($this->plateau->getPiece($rowNum, $colNum) == $void &&
            $this->isPieceValide($this->plateau->getCol($colNum), $piece) &&
            $this->isPieceValide($this->plateau->getRow($rowNum), $piece) &&
            $this->isPieceValide($this->plateau->getCorner($corner), $piece)) {
            return true;
        }

        return false;
    }

    // Méthode pour poser une pièce sur le plateau
    public function posePiece(int $rowNum, int $colNum, PieceQuantik $piece){
        $this->plateau->setPiece($rowNum,$colNum,$piece);
    }

    // Méthode pour obtenir une représentation textuelle de l'objet ActionQuantik
    public function __toString():string{
        return $this->plateau->__toString();
    }

    // Méthode privée pour vérifier si une combinaison de pièces est gagnante
    private static function isComboWin(ArrayPieceQuantik $pieces):bool{
        $somme=0;

        for($i=0;$i<count($pieces);$i++){
            $forme = $pieces[$i]->getForme();
            $somme = $somme+$forme;

        }
        if($somme==10){
            return true;
        }
        return false;
    }

    // Méthode privée pour vérifier si une pièce peut être posée
    private static function isPieceValide(ArrayPieceQuantik $pieces, PieceQuantik $p):bool {
        for($i=0;$i<count($pieces);$i++){
            if($pieces[$i]->getForme()===$p->getForme()&&$pieces[$i]->getCouleur()!==$p->getCouleur()){
                return false;
            }
        }
        return true;
    }

}
