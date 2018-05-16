<?php
class partie{
  // les variables
  private $_grille;  // tableau de tableau
  private $_nomJ1;   // chaine caractère
  private $_nomJ2;   // chaine caractère
  private $_etat;    // nombre entier
/*  1 : au joueur 1 de jouer
    2 : au joueur 2 de jouer
    10 : partie terminé
    11 : manque nom joueur 1
    12 : manque nom joueur 2
*/
  private $_message; // chaine de carractère
  private const TAILLE=[3,3]; // la taille du plateau [Ligne,Colone]

  // les fonctions privés
  private function _gagne(){
    // on regarde qui a gagner

    //les lignes
    foreach ($this->_grille as $key => $ligne) {
      $this->_nbCase($ligne);
    };

    //les collones
    for ($i=0; $i < $this::TAILLE[1] ; $i++) {
      $collone = array_column ( $this->_grille , $i );
      $this->_nbCase($collone);
    };

    // les diagonales
    $hg=array();
    $bg=array();
    for ($i=0; $i < $this::TAILLE[0] ; $i++) {
      if( isset($this->_grille[$i][$i]) ){
        $hg[]=$this->_grille[$i][$i];
      } ;
      if( isset($this->_grille[$this::TAILLE[0]-$i][$i]) ){
        $bg[]=$this->_grille[$this::TAILLE[0]-$i][$i];
      };
    };
    //echo " hg:<pre>";var_dump( $hg );echo "</pre>";
    //echo " bg:<pre>";var_dump( $bg );echo "</pre>";
    $this->_nbCase($hg);
    $this->_nbCase($bg);

//est ce que toute les cases ont été joué
    if( array_sum( array_map("count", $this->_grille ) ) >= $this::TAILLE[0]*$this::TAILLE[1] ){
      $this->_etat = 10;
      $this->_message = "Toute les cases ont été joué. Entrer votre nom pour commencer une nouvelle partie.";
    };
  }

// verifie si x cases
  private function _nbCase($tableau){
    $val = array_count_values($tableau);
    if( isset($val[1]) ){
      if($val[1] == $this::TAILLE[1] ){
        // le joueur 1 à gagné
        $this->_finPartie($this->_nomJ1);
      };
    };
    if( isset($val[2]) ){
      if($val[2] == $this::TAILLE[1] ) {
        // le joueur 2 à gagné
        $this->_finPartie($this->_nomJ2);
      };
    };
  }

// renvois la fin de la partie
  private function _finPartie($gagnant){
    $this->_etat = 10;
    $this->_message = "C'est $gagnant qui à gagné. Entrer votre nom pour commencer une nouvelle partie.";
  }

  // les fonctions public
// fonction appellé à la création de l'objet
  public function __construct(){
    $this->_etat = 11;
    $this->_message = "En attente de joueurs.";
    return True;
  }

  public function quiJoue(){
    // retourne le numero du joueur qui doit jouer
    if( $this->_etat == 1 ){ return 1 ;}
    elseif( $this->_etat == 2 ){ return 2 ;}
    else{ return False ;}
  }

  public function jouer($case){
    // ajouter dans la grille la case jouer suivant le joueur
    // verifier la longueur
    if( strlen($case) != 2 ){
      $this->_message = "Case joué non valide.";
      return False;
    };

    $joueur = $this->quiJoue();
    if(!$joueur){return False;};
    $case=str_split( $case , 1);
    // vérifier si on a un chiffre entre 0 et 3
    // vérifier que l'on ne joue pas 2 foi la même case
    if( isset($this->_grille[ $case[0] ][ $case[1] ]) ){
      $var="_nomJ";
      $this->_message = "Case déjà joué. C'est toujours à ".$this->{$var . $joueur}." de jouer.";
      return False;
    };

    $this->_grille[ $case[0] ][ $case[1] ]=$joueur;
    if($this->_etat == 1){
      $this->_etat = 2;
      $this->_message = "C'est à $this->_nomJ2 de jouer.";
    }
    elseif($this->_etat == 2){
      $this->_etat = 1;
      $this->_message = "C'est à $this->_nomJ1 de jouer.";
    };
    $this->_gagne();
    return True;
  }


  public function getGrille(){
    // retourne la grille de jeu
    return (array) $this->_grille;
  }

  public function getJson(){
    // retourne le json avec touts les infos de la partie
    $json = [
    "nomJ1"=>$this->_nomJ1,
    "nomJ2"=>$this->_nomJ2,
    "etat"=>$this->_etat,
    "message"=>$this->_message,
    "grille"=>(array) $this->_grille,
    ];
    if( isset($_SESSION["monNom"]) ){ $json["monNom"]=$_SESSION["monNom"]; };
    if( isset($_SESSION["monNum"]) ){ $json["monNum"]=$_SESSION["monNum"]; };

    return json_encode($json);
  }

  public function setFromJson($json){
    // rempli les infos de la partie depuis un json
    // si $json n'est pas un tableau
    if( !is_array($json) ){$json = json_decode($json, true); };
    // si l'état est nul, on quitte
    if( is_null($json["etat"]) ){return False; }else{ $this->_etat = $json["etat"]; };
    $this->_nomJ1 = $json["nomJ1"];
    $this->_nomJ2 = $json["nomJ2"];
    $this->_message = $json["message"];
    $this->_grille = $json["grille"];
    return True;
  }

  public function setJoueur($nom){
    // ajoute un joueur
    if( strlen($nom) < 3 ){
      $this->_message = "Nom de joueur trop court. Minimum 3 caractères.";
      return False;
    };

    if($this->_etat == 11){
      // défini le nom du joueur 1
      $this->_nomJ1 = $nom;
      $this->_etat = 12;
      $this->_message = "Bienvenu $this->_nomJ1. On attend un autre joueur.";
      $_SESSION["monNom"]=$nom;
      $_SESSION["monNum"]=1;
    }

    elseif($this->_etat == 12){
      // défini le nom du joueur 2
      $this->_nomJ2 = $nom;
      $this->_etat = 1;
      $this->_message = "Bienvenu à $this->_nomJ2. C'est à $this->_nomJ1 de jouer.";
      $_SESSION["monNom"]=$nom;
      $_SESSION["monNum"]=2;
    };
  }
}
 ?>
