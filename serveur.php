<?php
/*
ressources:
https://developer.mozilla.org/en-US/docs/Web/API/EventSource/EventSource
https://developer.mozilla.org/en-US/docs/Web/API/WebSockets_API/Writing_WebSocket_client_applications

depot github:
https://github.com/europrimus/WF3_Morpion

*/

// initialisation de la session
session_start();
include_once("partie.class.php");

$fichierPartie="partieObj.json";

// on supprime la partie
if( isset($_REQUEST["supprime"]) ){
  //echo "le joueur ".$_REQUEST["joueur"]." à joué la case ".$_REQUEST["case"]."<br>".PHP_EOL;
  unlink($fichierPartie);
};

// initialise un objet partie
$partieObj = new partie();
//echo "objet partie (partieObj) :<pre>";var_dump( $partieObj );echo "</pre>";


if( is_file( $fichierPartie ) ){
  $partieObj->setFromJson( file_get_contents( $fichierPartie ) );
  //echo "lecture du fichier $fichierPartie :<pre>";var_dump( $partieObj );echo "</pre>";
};


//on attend un joueur
if( isset($_REQUEST["nom"]) ){
  $partieObj->setJoueur( $_REQUEST["nom"] );
  //echo "Ajoute un joueur (partieObj) :<pre>";var_dump( $partieObj );echo "</pre>";
}

// attend action du joueur
// coordonnés de la case joué
if( isset($_REQUEST["case"]) ){
  //echo "le joueur ".$_REQUEST["joueur"]." à joué la case ".$_REQUEST["case"]."<br>".PHP_EOL;
  $partieObj->jouer($_REQUEST["case"]);
};


// ecrit les infos dans le fichier de la partie
$fichier = fopen ( $fichierPartie , "w" );
fwrite ( $fichier , $partieObj->getJson() );
fclose( $fichier );

// renvoi le json au joueur
echo $partieObj->getJson();
?>
