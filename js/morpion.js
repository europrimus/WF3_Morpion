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
	console.warn("click sur #plateau .cliquable");
// on récupère la case
	let ligne = jQuery(this)[0].cellIndex;
	let colone = jQuery(this).parent( "tr" )[0].rowIndex;
	let box = String(colone)+String(ligne);
	console.log(box);
	jQuery.post(partie, { "case": box }, draw, 'json');
	idInterval = setTimeout(function() {
		jQuery.post( partie, draw, 'json');
	}, 500);
};


// on dessine le jeu
function draw($data){
  console.warn("morpion.js : draw");
  //console.log($data);
  //console.log($data.nomJ2);

// on affiche le nom des joueurs
  jQuery("#nomJ1")[0].innerText=$data.nomJ1;
  jQuery("#nomJ2")[0].innerText=$data.nomJ2;

// si je n'ai pas de nom, on arrete
if($data.monNom === undefined ){ return false; };

// c'est à moi de jouer ?
//console.log($data.nomJ1);
//console.log($data.monNom);
let jeSuis=$data.monNum;
// affiche mon nom et mon symbole
jQuery("#monNom")[0].innerText=$data.monNom;
jQuery("#monSymbole")[0].src="img/"+$data.monNum+".png";

// si ce n'est pas à moi de jouer
// ou si la partie est fini
if($data.etat != jeSuis){
	//console.log("ce n'est pas à moi de jouer");
	jQuery("#plateau td").removeClass("cliquable").off( "mouseup" );
if( $data.etat == 10 ){
	jQuery.post( partie, {"supprime":""} , draw, 'json')
	}else{
		// on recupère la partie en cour
		idInterval = setTimeout(function() {
			jQuery.post( partie, draw, 'json');
		}, 500);
	};
}else{
	//console.log("c'est à moi de jouer");
	// on arrete de récupérer les infos de partie
	clearInterval(idInterval);
	jQuery("#plateau td").addClass("cliquable");
	jQuery("#plateau .cliquable").mouseup( clickJoue );
}

// on rempli le plateau
//console.log($data.grille);
//console.log(typeof $data.grille);
//console.log(jQuery("#plateau tr"));
jQuery.each( $data.grille, function(y, ligne){
	//console.warn(y);
	//console.log(ligne);
	jQuery.each( ligne, function(x, box){
		//console.log(x);
		//console.log(box);
		//console.log( jQuery("#plateau tr").eq(y).children("td").eq(x) );
		jQuery("#plateau tr").eq(y).children("td").eq(x)[0].innerHTML='<img src="img/'+box+'.png">';
		jQuery("#plateau tr").eq(y).children("td").eq(x).removeClass("cliquable").off( "mouseup" );
		//jQuery("#plateau tr").eq(1).children()
		//box;
	});
});

// on affiche le plateau
  jQuery("#plateau").removeClass("cacher");

// on cache le formulaire
  jQuery("#creationJoueur").addClass("cacher");

// on affiche le message
  jQuery("#message>p")[0].innerText=$data.message;
}
