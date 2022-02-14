
// Gestion de l'affichage du formulaire de modification d'email de la page de profil

// Récupération des éléments du DOM
let editEmailButton = document.querySelector('#editEmailButton'); 
let checkEmailButton = document.querySelector('#checkEmailButton');
let emailProfileElt = document.querySelector('#emailProfile');
let emailEditFormElt = document.querySelector('#emailEditForm');

// Définition d'un gestionnaire d'événement pour afficher le formulaire 
editEmailButton.addEventListener("click", function(){
    // On cache l'élément 'emailProfileElt' en ajoutant la classe 'display-none'
    emailProfileElt.classList.add("display-none");
    // On affiche l'élément 'emailEditFormElt' en supprimant la classe 'display-none'
    emailEditFormElt.classList.remove("display-none");
});