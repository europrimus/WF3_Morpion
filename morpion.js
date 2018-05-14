// on recupère la partie en cour
setInterval(function() {
	jQuery.get("partie.json", draw, 'json');
}, 3000);

// on dessine le jeu
function draw($data){
  console.warn("morpion.js : draw");
  console.log($data);
  //console.log($data.nomJ2);
// on affiche le nom des joueurs
  jQuery("#nomJ1")[0].innerText=$data.nomJ1;
  jQuery("#nomJ2")[0].innerText=$data.nomJ2;

// on rempli le plateau

// on affiche le plateau
  jQuery("#plateau").removeClass("cacher");

// on cache le formulaire
  jQuery("#creationJoueur").addClass("cacher");

// on affiche le message
  jQuery("#message>p")[0].innerText=$data.message;
}
