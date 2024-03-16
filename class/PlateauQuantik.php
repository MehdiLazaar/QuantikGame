<?php
class PlateauQuantik{
    // Constantes définissant le nombre de lignes et de colonnes sur le plateau
    const NBROWS = 4;
    const NBCOLS = 4;
    // Constantes représentant les coins du plateau
    const NW = 0;
    const NE = 1;
    const SW = 2;
    const SE = 3;

    // Attribut contenant les cases du plateau
    protected array $cases;

    // Constructeur de la classe initialisant les cases du plateau avec des pièces vides
    public function __construct(){
        $this->cases = array();
        for($i=0;$i<4;$i++){
            $this->cases[$i] = new ArrayPieceQuantik();
            for($j=0;$j<4;$j++){
                $this->cases[$i]->addPieceQuantik(PieceQuantik::initVoid());
            }
        }
    }

    // Méthode pour obtenir la pièce à une position donnée sur le plateau
    public function getPiece(int $rowNum, int $colNum) : PieceQuantik{
        return $this->cases[$rowNum]->getPieceQuantik($colNum);
    }

    // Méthode pour définir une pièce à une position donnée sur le plateau
    public function setPiece(int $rowNum, int $colNum, PieceQuantik $piece){
        $this->cases[$rowNum]->setPieceQuantik($colNum,$piece);
    }

    // Méthode pour obtenir une ligne spécifique du plateau
    public function getRow(int $numRow) : ArrayPieceQuantik{
        return $this->cases[$numRow];
    }

    // Méthode pour obtenir une colonne spécifique du plateau
    public function getCol(int $numCol):ArrayPieceQuantik{
        $result = new ArrayPieceQuantik();
        for($i= 0;$i< 4;$i++){
            $result->addPieceQuantik($this->cases[$i]->getPieceQuantik($numCol));
        }
        return $result;
    }

    // Méthode pour obtenir un coin spécifique du plateau
    public function getCorner(int $dir):ArrayPieceQuantik{
        $result = new ArrayPieceQuantik();
        switch($dir){
            case self::NW :
                $result->addPieceQuantik($this->cases[0]->getPieceQuantik(0));
                $result->addPieceQuantik($this->cases[0]->getPieceQuantik(1));
                $result->addPieceQuantik($this->cases[1]->getPieceQuantik(0));
                $result->addPieceQuantik($this->cases[1]->getPieceQuantik(1));
                break;
            case self::NE :
                $result->addPieceQuantik($this->cases[0]->getPieceQuantik(2));
                $result->addPieceQuantik($this->cases[0]->getPieceQuantik(3));
                $result->addPieceQuantik($this->cases[1]->getPieceQuantik(2));
                $result->addPieceQuantik($this->cases[1]->getPieceQuantik(3));
                break;
            case self::SW :
                $result->addPieceQuantik($this->cases[2]->getPieceQuantik(0));
                $result->addPieceQuantik($this->cases[2]->getPieceQuantik(1));
                $result->addPieceQuantik($this->cases[3]->getPieceQuantik(0));
                $result->addPieceQuantik($this->cases[3]->getPieceQuantik(1));
                break;
            case self::SE :
                $result->addPieceQuantik($this->cases[2]->getPieceQuantik(2));
                $result->addPieceQuantik($this->cases[2]->getPieceQuantik(3));
                $result->addPieceQuantik($this->cases[3]->getPieceQuantik(2));
                $result->addPieceQuantik($this->cases[3]->getPieceQuantik(3));
                break;
        }
        return $result;
    }

    // Méthode pour obtenir une représentation textuelle du plateau
    public function __toString():string{
        $s ="";
        for($i=0;$i<4;$i++){
            $s .= $this->cases[$i]."<br/>";
        }
        return $s;
    }

    // Méthode statique pour obtenir le coin à partir des coordonnées d'une case
    public static function getCornerFromCoord(int $rowNum, int $colNum):int{
        if($rowNum==0 || $rowNum==1){
            if($colNum>1) return self::NE;
            else return self::NW;
        }else{
            if($colNum>1) return self::SE;
            else return self::SW;
        }
    }

    // Méthode pour obtenir une représentation JSON du plateau
    public function getJson(): string {
        $json = "[";
        $jTab = [];
        foreach ($this->cases as $apq)
            $jTab[] = $apq->getJson();
        $json .= implode(',',$jTab);
        return $json.']';
    }

    // Méthode statique pour initialiser un plateau à partir d'une représentation JSON
    public static function initFromJson(string $json): PlateauQuantik{
        $result = new PlateauQuantik();
        $tab = json_decode($json);
        for($i=0;$i<4;$i++){
            $result->cases[$i] = ArrayPieceQuantik::initFromJson(json_encode($tab[$i]));
        }
        return $result;
    }

}
?>
