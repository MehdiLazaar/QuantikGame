<?php
    class ArrayPieceQuantik implements ArrayAccess, Countable{

        // Attribut contenant les pièces Quantik

        protected $piecesQuantik;

        // Attribut comptant le nombre de pièces

        private int $count;

        // Constructeur de la classe initialisant l'array et le compteur
        public function __construct() {
            $this->piecesQuantik = array();
            $this->count = 0;
        }


        // Méthode pour obtenir une pièce Quantik à une position donnée
        public function getPieceQuantik(int $pos) : PieceQuantik{
            return self::offsetGet($pos);
        }


        // Méthode pour définir une pièce Quantik à une position donnée
        public function setPieceQuantik(int $pos, PieceQuantik $piece):void{
            self::offsetSet($pos,$piece);
        }

        // Méthode pour ajouter une pièce Quantik
        public function addPieceQuantik(PieceQuantik $piece):void{
            self::setPieceQuantik($this->count,$piece);
            $this->count++;
        }


        // Méthode pour supprimer une pièce Quantik à une position donnée
        public function removePieceQuantik(int $pos):void{
            self::offsetUnset($pos);
            $this->count--;
        }

        // Méthode statique pour initialiser un ensemble de pièces noires
        public static function initPiecesNoires() : ArrayPieceQuantik{
            $piecesNoires = new ArrayPieceQuantik();
            $piecesNoires->addPieceQuantik(PieceQuantik::initBlackCone());
            $piecesNoires->addPieceQuantik(PieceQuantik::initBlackCone());
            $piecesNoires->addPieceQuantik(PieceQuantik::initBlackCube());
            $piecesNoires->addPieceQuantik(PieceQuantik::initBlackCube());
            $piecesNoires->addPieceQuantik(PieceQuantik::initBlackCylindre());
            $piecesNoires->addPieceQuantik(PieceQuantik::initBlackCylindre());
            $piecesNoires->addPieceQuantik(PieceQuantik::initBlackSphere());
            $piecesNoires->addPieceQuantik(PieceQuantik::initBlackSphere());
            return $piecesNoires;
        }

        // Méthode statique pour initialiser un ensemble de pièces blanches
        public static function initPiecesBlanches() : ArrayPieceQuantik{
            $piecesBlanches = new ArrayPieceQuantik();
            $piecesBlanches->addPieceQuantik(PieceQuantik::initWhiteCone());
            $piecesBlanches->addPieceQuantik(PieceQuantik::initWhiteCone());
            $piecesBlanches->addPieceQuantik(PieceQuantik::initWhiteCube());
            $piecesBlanches->addPieceQuantik(PieceQuantik::initWhiteCube());
            $piecesBlanches->addPieceQuantik(PieceQuantik::initWhiteCylindre());
            $piecesBlanches->addPieceQuantik(PieceQuantik::initWhiteCylindre());
            $piecesBlanches->addPieceQuantik(PieceQuantik::initWhiteSphere());
            $piecesBlanches->addPieceQuantik(PieceQuantik::initWhiteSphere());
            return $piecesBlanches;
        }


        // Méthode pour obtenir une représentation textuelle de l'objet ArrayPieceQuantik
        public function __toString() : string{
            $s = "";
            for ($i = 0; $i < $this->count; $i++) {
                $s .= $this->piecesQuantik[$i]. " ";
            }
            return $s;
        }
        
        public function offsetExists($offset) : bool{
            if($offset >=0 && $offset < count($this->piecesQuantik) && $this->piecesQuantik !=null){
                return true;
            }
            return false;
        }
        
        public function offsetGet($offset) : ?PieceQuantik {
            return $this->offsetExists($offset) ? $this->piecesQuantik[$offset] : null;
        }
        
        public function offsetSet(mixed $offset, mixed $value): void
        {
            $this->piecesQuantik[$offset] = $value;
        }

        public function offsetUnset($offset):void{
            for ($i = $offset; $i < $this->count; $i++) {
                if (isset($this->piecesQuantik[$i + 1])) {
                    $this->piecesQuantik[$i] = $this->piecesQuantik[$i + 1];
                }
            }
            unset($this->piecesQuantik[$this->count-1]);
        }
        public function count() : int{
            return $this->count;
        }
        
        public function getJson(): string
    {
        $json = "[";
        $jTab = [];
        foreach ($this->piecesQuantik as $p)
            $jTab[] = $p->getJson();
        $json .= implode(',', $jTab);
        return $json . ']';
    }

    public static function initFromJson(string $json): ArrayPieceQuantik
    {
        $result = new ArrayPieceQuantik();
        $tab = json_decode($json);
        foreach ($tab as $p)
            $result->addPieceQuantik(PieceQuantik::initPieceFromJson(json_encode($p)));
        return $result;
    }
}
