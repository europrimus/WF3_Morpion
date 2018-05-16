// nom de la partie:
let partie = "serveur.php"
let idInterval;
// on charge la partie
jQuery.post( partie, draw, 'json');

// on recupère le nom du joueur
jQuery("#submitNom").click( function( event ) {
	console.warn("click sur #submitNom");
// on evite le traitement de la page
	event.preventDefault();
// on récupère le nom
	nom = jQuery("#nom").val();
// et on l'envoi au serveur
	jQuery.post(partie, { "nom": nom }, draw, 'json');
});

// on click sur une case
function clickJoue( event ) {
	//console.warn("click sur #plateau .cliquable");
// on récupère la case
	let ligne = jQuery(this)[0].cellIndex;
	let colone = jQuery(this).parent( "tr" )[0].rowIndex;
	let box = String(colone)+String(ligne);
	//console.log(box);
	jQuery.post(partie, { "case": box }, draw, 'json');
	idInterval = setTimeout(function() {
		jQuery.post( partie, draw, 'json');
	}, 500);
};


// on dessine le jeu
function draw($data){
  console.warn("morpion.js : draw");
  //console.log($data);

	// si je n'ai pas de nom, on arrete
	if($data.monNom === undefined ){ return false; };

// on affiche le nom des joueurs
  jQuery("#nomJ1")[0].innerText=$data.nomJ1;
  jQuery("#nomJ2")[0].innerText=$data.nomJ2;

// affiche mon nom et mon symbole
	jQuery("#monNom")[0].innerText=$data.monNom;
	jQuery("#monSymbole")[0].src="img/"+$data.monNum+".png";

// suivant l'état de la partie
switch ($data.etat) {
	case 1:
		// au joueur 1 de jouer
		if($data.monNum == 1){jouer($data);}
		else{attendre($data);};
		voirPlateau($data);
		break;

	case 2:
		// au joueur 2 de jouer
		if($data.monNum == 2){jouer($data);}
		else{attendre($data);};
		voirPlateau($data);
		break;

	case 10:
		// partie terminé
		cacherPlateau();
		idInterval = setTimeout(function() {
			jQuery.post( partie, {"supprime":""} , draw, 'json');
		}, 5000);
		break;

	case 11:
		// manque nom joueur 1
		cacherPlateau();
		attendre($data);
		break;

	case 12:
		// manque nom joueur 2
		cacherPlateau();
		attendre($data);
		break;

	default:
		// code d'état non reconu
		$data.message="Erreur: code de retour serveur non reconnu.";

}

// on affiche le message
  jQuery("#message>p")[0].innerText=$data.message;

}

function jouer($data){
	// c'est à moi de jouer
	clearInterval(idInterval);
	jQuery("#plateau td").addClass("cliquable").mouseup( clickJoue );
};


function attendre($data){
	// si ce n'est pas à moi de jouer
	jQuery("#plateau td").removeClass("cliquable").off( "mouseup" );
// on recupère la partie en cour
	idInterval = setTimeout(function() {
		jQuery.post( partie, draw, 'json');
	}, 500);
}

function voirPlateau($data){
	// on rempli le plateau
	jQuery.each( $data.grille, function(y, ligne){
		jQuery.each( ligne, function(x, box){
			jQuery("#plateau tr").eq(y).children("td").eq(x)[0].innerHTML='<img src="img/'+box+'.png">';
			jQuery("#plateau tr").eq(y).children("td").eq(x).removeClass("cliquable").off( "mouseup" );
		});
	});

	// on affiche le plateau
	  jQuery("#plateau").removeClass("cacher");

	// on cache le formulaire
	  jQuery("#creationJoueur").addClass("cacher");
}

function cacherPlateau(){
	// on vide le plateau
		jQuery("#plateau td").each( function( ) {
  		this.innerHTML='';
		});
		// on cache le plateau
	  jQuery("#plateau").addClass("cacher");

	// on affiche le formulaire
	  jQuery("#creationJoueur").removeClass("cacher");
}
