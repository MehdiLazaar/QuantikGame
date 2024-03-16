<?php
class PieceQuantik {

    // Constantes définissant les formes et les couleurs des pièces Quantik
    const WHITE = 0;
	const BLACK = 1; 
  const VOID = 0;
	const CUBE  = 1; 
	const CONE  = 2; 
	const CYLINDRE = 3; 
	const SPHERE = 4;

    // Attributs représentant la forme et la couleur de la pièce
    protected int $forme;
    protected int $couleur; 

  private function __construct(int $forme , int $couleur){
  	$this->forme = $forme;
    $this->couleur = $couleur;
  }

    // Méthode pour obtenir la forme de la pièce
    public  function getForme() : int {
  	return $this->forme; 
  }

    // Méthode pour obtenir la couleur de la pièce
    public  function getCouleur() : int {
  	return $this->couleur; 
  }

    // Méthode pour obtenir une représentation textuelle de la pièce
    public function __toString() : string {
    $s="(";
    if($this->forme == self::VOID){
      $s.="&nbsp;&nbsp;&nbsp;&nbsp;";
    }else{
  	switch ($this->forme) {
      case self::CUBE :
        $s .= "Cu";
        break;
      case self::CONE :
        $s .= "Co";
        break;
      case self::CYLINDRE :
        $s .= "Cy";
        break;
      case self::SPHERE :
        $s .= "Sp";
        break;

    }
    $s.=",";
    if($this->couleur==self::WHITE){
      $s .= "W";
    } else{
      $s.="B";
    }
  }
    $s.=")";
    return $s;
  }
    // Méthodes statiques pour initialiser des pièces spécifiques
    public static function initVoid() : PieceQuantik {
  	return new PieceQuantik (self::VOID , self::WHITE);
  }
  public static function initWhiteCube() :PieceQuantik {
  	return new PieceQuantik (self::CUBE , self::WHITE);
  }
  public static function initBlackCube () : PieceQuantik {
  return new PieceQuantik (self::CUBE , self::BLACK);
  }
  public static function initWhiteCone(): PieceQuantik {
  	return new PieceQuantik (self::CONE , self::WHITE);
  }
  public static function initBlackCone (): PieceQuantik {
  	return new PieceQuantik (self::CONE , self::BLACK);
  }
  public static function initWhiteCylindre () : PieceQuantik {
  	return new PieceQuantik (self::CYLINDRE , self::WHITE);
  }
  public static function initBlackCylindre() : PieceQuantik {
  	return new PieceQuantik(self::CYLINDRE , self::BLACK );
  }
  public static function initWhiteSphere () : PieceQuantik {
  	return new PieceQuantik (self::SPHERE , self::WHITE);
  }
  public static function initBlackSphere () : PieceQuantik {
  	return new PieceQuantik (self::SPHERE , self::BLACK);
  }
  public function getJson(): string {
        return '{"forme":'. $this->forme . ',"couleur":'.$this->couleur. '}';
    }

  public static function initPieceFromJson(string $json): PieceQuantik {
        $data = json_decode($json);
        return new PieceQuantik($data->forme, $data->couleur);
    }
}