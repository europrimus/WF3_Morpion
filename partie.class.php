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

  // les fonctions privés


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
    $this->_grille[ $case[0] ][ $case[1] ]=$joueur;
    if($this->_etat == 1){
      $this->_etat = 2;
      $this->_message = "C'est à $this->_nomJ2 de jouer.";
    }
    elseif($this->_etat == 2){
      $this->_etat = 1;
      $this->_message = "C'est à $this->_nomJ1 de jouer.";
    };
    return True;
  }

  public function getGrille(){
    // retourne la grille de jeu
    return $this->_grille;
  }

  public function getJson(){
    // retourne le json avec touts les infos de la partie
    $json = ["nomJ1"=>$this->_nomJ1, "nomJ2"=>$this->_nomJ2, "etat"=>$this->_etat, "message"=>$this->_message, "grille"=>$this->_grille];
    return json_encode($json);
  }

  public function setFromJson($json){
    // rempli les infos de la partie depuis un json
    // si $json n'est pas un tableau
    if( !is_array($json) ){$json = json_decode($json, true); };
    $this->_nomJ1 = $json["nomJ1"];
    $this->_nomJ2 = $json["nomJ2"];
    $this->_etat = $json["etat"];
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
    }

    elseif($this->_etat == 12){
      // défini le nom du joueur 2
      $this->_nomJ2 = $nom;
      $this->_etat = 1;
      $this->_message = "Bienvenu $this->_nomJ2. C'est à $this->_nomJ1 de jouer.";
    };
  }
}
 ?>
