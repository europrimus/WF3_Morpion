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

$fichierPartie="partie.json";

if( is_file( $fichierPartie ) ){
  $partie = json_decode( file_get_contents( $fichierPartie ) , true );
  echo "lecture du fichier $fichierPartie :<pre>";var_dump( $partie );echo "</pre>";
};


// si pas des grille
if( !isset($partie["grille"]) ){
// initialise une grille vide
  $partie["grille"]=[["","",""],["","",""],["","",""]];
  echo "grille:<pre>";var_dump( $grille );echo "</pre>";
};

// attend action J1
// coordonnés de la case joué
if( isset($_REQUEST["case"]) ){
  echo "le joueur ".$_REQUEST["joueur"]." à joué la case ".$_REQUEST["case"]."<br>".PHP_EOL;
  $case=str_split ( $_REQUEST["case"] , 1);
  $partie["grille"][$case[0]][$case[1]]=$_REQUEST["joueur"];
};

// renvois info à J2
// toute la grille


// attend action J2
// coordonnés de la case

// renvois info à J1
// toute la grille
$json=json_encode($partie);
$fichier = fopen ( $fichierPartie , "w" );
fwrite ( $fichier , $json );
fclose( $fichier );

echo $json;
?>
