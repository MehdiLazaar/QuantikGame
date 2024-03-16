<?php
class QuantikGame extends AbstractGame{
    // Attributs de la classe
    public PlateauQuantik $plateau; // Le plateau de jeu
    public ArrayPieceQuantik $piecesBlanches; // Les pièces blanches disponibles
    public ArrayPieceQuantik $piecesNoires; // Les pièces noires disponibles
    public array $couleursPlayers; // Les joueurs de la partie

    // Constructeur de la classe
    public function __construct(int $id, array $players)
    {
        $this->couleursPlayers = array();
        foreach ($players as $player) {
            $this->couleursPlayers[] = $player;
        }
        $this->gameID = $id;
        if(isset($this->couleursPlayers[0])){
            $this->currentPlayer = $this->couleursPlayers[0]->getId();
        }

        $this->plateau = new PlateauQuantik(); // Initialisation du plateau de jeu
        $this->piecesBlanches = ArrayPieceQuantik::initPiecesBlanches(); // Initialisation des pièces blanches
        $this->piecesNoires = ArrayPieceQuantik::initPiecesNoires(); // Initialisation des pièces noires
        $this->gameStatus = 'constructed'; // Statut initial de la partie
    }

    // Méthodes pour accéder et modifier l'identifiant de la partie
    public function setId(int $id) {
        $this->gameID=$id;
    }
    public function getId(): int {
        return $this->gameID;
    }

    // Méthode pour définir le deuxième joueur
    public function setPlayer2(Player $player){
        $this->couleursPlayers[1]=$player;
    }

    // Méthode pour définir le statut de la partie
    public function setStatus(string $status){
        $this->gameStatus=$status;
    }

    // Méthode pour obtenir une représentation textuelle de la partie
    public function __toString(): string
    {
        // Selon le statut de la partie, renvoie un message approprié
        if($this->gameStatus=='initialized'){
            return 'Partie n°' . $this->gameID. ' lancée par joueur ' . $this->couleursPlayers[0]->getName();
        }
        if($this->gameStatus=='waitingForPlayer'){
            if($this->currentPlayer!=$this->couleursPlayers[0]->getId())
                return 'Partie n°' . $this->gameID. ' en attente du joueur ' . $this->couleursPlayers[1]->getName();
            else
                return 'Partie n°' . $this->gameID. ' en attente du joueur ' . $this->couleursPlayers[0]->getName();
        }
        if($this->gameStatus=='finished'){
            return 'Partie n°' . $this->gameID. ' terminée';
        }
        return 'Partie n°' . $this->gameID. ' a un problème';
    }

    // Méthode pour obtenir une représentation JSON de la partie
    public function getJson(): string
    {
        // Construction de la chaîne JSON contenant les données de la partie
        $json = '{';
        $json .= '"plateau":' . $this->plateau->getJson();
        $json .= ',"piecesBlanches":' . $this->piecesBlanches->getJson();
        $json .= ',"piecesNoires":' . $this->piecesNoires->getJson();
        $json .= ',"currentPlayer":' . $this->currentPlayer;
        $json .= ',"gameID":' . $this->gameID;
        $json .= ',"gameStatus":' . json_encode($this->gameStatus);
        // Si le deuxième joueur n'est pas défini, inclut uniquement le premier joueur dans les données JSON
        if (!isset($this->couleursPlayers[1]))
            $json .= ',"couleursPlayers":[' . $this->couleursPlayers[0]->getJson() . ']';
        // Sinon, inclut les deux joueurs dans les données JSON
        else
            $json .= ',"couleursPlayers":[' . $this->couleursPlayers[0]->getJson() . ',' . $this->couleursPlayers[1]->getJson() . ']';
        return $json . '}';
    }

    // Méthode statique pour initialiser une partie Quantik à partir d'une représentation JSON
    public static function initQuantikGame(string $json): QuantikGame{
        // Création d'une nouvelle instance de QuantikGame avec des valeurs par défaut
        $result = new QuantikGame(0, []);
        // Décodage de la chaîne JSON
        $tab = json_decode($json);
        // Initialisation des attributs de la partie à partir des données JSON
        $result->plateau = PlateauQuantik::initFromJson(json_encode($tab->plateau));
        $result->piecesBlanches = ArrayPieceQuantik::initFromJson(json_encode($tab->piecesBlanches));
        $result->piecesNoires = ArrayPieceQuantik::initFromJson(json_encode($tab->piecesNoires));
        $result->currentPlayer = $tab->currentPlayer;
        $result->gameID = $tab->gameID;
        $result->gameStatus = $tab->gameStatus;
        $result->couleursPlayers = [];
        // Initialisation des joueurs à partir des données JSON
        foreach ($tab->couleursPlayers as $player)
            $result->couleursPlayers[] = Player::initPlayer(json_encode($player));
        return $result; // Retourne l'objet QuantikGame initialisé
    }
}
?>
