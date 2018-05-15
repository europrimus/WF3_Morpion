// on recupère le nom du joueur
jQuery("#submitNom").click( function( event ) {
	console.warn("click sur #submitNom");
// on evite le traitement de la page
	event.preventDefault();
// on récupère le nom et on l'envoi au serveur
	nom = jQuery("#nom").val();
	localStorage.setItem('monNom', nom );
	jQuery.post("serveur.php", { "nom": nom }, draw, 'json');
});

// on recupère la partie en cour
let idInterval = setInterval(function() {
	jQuery.post("partieObj.json", draw, 'json');
}, 5000);

// on dessine le jeu
function draw($data){
  console.warn("morpion.js : draw");
  console.log($data);
  //console.log($data.nomJ2);
// on affiche le nom des joueurs
  jQuery("#nomJ1")[0].innerText=$data.nomJ1;
  jQuery("#nomJ2")[0].innerText=$data.nomJ2;

// si je n'ai pas de nom, on arrete
if(localStorage['monNom'] === undefined ){ return false; };

// on rempli le plateau

// on affiche le plateau
  jQuery("#plateau").removeClass("cacher");

// on cache le formulaire
  jQuery("#creationJoueur").addClass("cacher");

// on affiche le message
  jQuery("#message>p")[0].innerText=$data.message;
}
