// nom de la partie:
let partie = "partieObj.json"

// on charge la partie
jQuery.post( partie, draw, 'json');

// on recupère le nom du joueur
jQuery("#submitNom").click( function( event ) {
	console.warn("click sur #submitNom");
// on evite le traitement de la page
	event.preventDefault();
// on récupère le nom
	nom = jQuery("#nom").val();
	localStorage.setItem('monNom', nom );
// et on l'envoi au serveur
	jQuery.post("serveur.php", { "nom": nom }, draw, 'json');
});

// on click sur une case
jQuery("#plateau .cliquable").click( function( event ) {
	console.warn("click sur #plateau .cliquable");
// on récupère la case
	let ligne = jQuery(this)[0].cellIndex;
	let colone = jQuery(this).parent( "tr" )[0].rowIndex;
	let box = String(colone)+String(ligne);
	console.log(box);
	jQuery.post("serveur.php", { "case": box }, draw, 'json');
});

// on recupère la partie en cour
let idInterval = setInterval(function() {
	jQuery.post( partie, draw, 'json');
}, 5000);

// on dessine le jeu
function draw($data){
  console.warn("morpion.js : draw");
  //console.log($data);
  //console.log($data.nomJ2);
	// est ce que j'appartien à la partie ?
if( ( $data.nomJ1 !== localStorage.monNom ) && ( $data.nomJ2 !== localStorage.monNom ) ){
	localStorage.removeItem("monNom");
}

// on affiche le nom des joueurs
  jQuery("#nomJ1")[0].innerText=$data.nomJ1;
  jQuery("#nomJ2")[0].innerText=$data.nomJ2;

// si je n'ai pas de nom, on arrete
if(localStorage['monNom'] === undefined ){ return false; };

// c'est à moi de jouer ?
//console.log($data.nomJ1);
//console.log(localStorage.monNom);
let jeSuis;
if( $data.nomJ1 == localStorage.monNom ){jeSuis = 1}
else if( $data.nomJ2 == localStorage.monNom ){jeSuis = 2}
if($data.etat != jeSuis ){
	jQuery("#plateau td").removeClass("cliquable");
	jQuery("#plateau").off( "click", "#plateau td");
}

// on rempli le plateau
//console.log($data.grille);
//console.log(typeof $data.grille);
console.log(jQuery("#plateau tr"));
jQuery.each( $data.grille, function(y, ligne){
	console.warn(y);
	//console.log(ligne);
	jQuery.each( ligne, function(x, box){
		console.log(x);
		//console.log(box);
		//console.log( jQuery("#plateau tr").eq(y).children("td").eq(x) );
		jQuery("#plateau tr").eq(y).children("td").eq(x)[0].innerHTML='<img src="img/'+box+'.png">';
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
