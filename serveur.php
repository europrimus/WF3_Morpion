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
$fichierLog="morpion.log";

// log dans le $fichierLog
//file_put_contents( $fichierLog, PHP_EOL."debut : ".date('Y-m-d').PHP_EOL , FILE_APPEND );

function log2file($tolog,$fichierLog){
  file_put_contents( $fichierLog, date('Y/m/d H:i:s')." : ".$tolog.PHP_EOL , FILE_APPEND );
}

// on supprime la partie
if( isset($_REQUEST["supprime"]) ){
  //echo "le joueur ".$_REQUEST["joueur"]." à joué la case ".$_REQUEST["case"]."<br>".PHP_EOL;
  unlink($fichierPartie);
  unset( $_SESSION["monNom"] );
  unset( $_SESSION["monNum"] );
};

// initialise un objet partie
$partieObj = new partie();
//echo "objet partie (partieObj) :<pre>";var_dump( $partieObj );echo "</pre>";

// on lit la partie enregistré
if( is_file( $fichierPartie ) ){
  $partieObj->setFromJson( file_get_contents( $fichierPartie ) );
  //echo "lecture du fichier $fichierPartie :<pre>";var_dump( $partieObj );echo "</pre>";
};

// si la partie n'est pas initialisé on supprime le nom du joueur
if( $partieObj->getEtat() == 11 ){
  unset( $_SESSION["monNom"] );
  unset( $_SESSION["monNum"] );
}

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

//On recupère les infos de lapartie
$json = $partieObj->getJson();

// log dans le $fichierLog
log2file("json : ".$json.PHP_EOL."partieObj->getEtat : ".$partieObj->getEtat().PHP_EOL."_SESSION[monNom] : ".$_SESSION["monNom"] , $fichierLog );

// si on n'est pas spectateur
if( $partieObj->getEtat() != 5 ){
  // ecrit les infos dans le fichier de la partie
  $fichier = fopen ( $fichierPartie , "w" );
  fwrite ( $fichier , $json );
  fclose( $fichier );
};
// renvoi le json au joueur
echo $json;
?>
