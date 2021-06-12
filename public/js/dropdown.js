// Gestion de la dropdown du menu utilisateur

// Récupération des éléments du DOM
let dropdownLink = document.querySelector('.dropdown');
let dropdownContent = document.querySelector('.dropdown-content');

// Définition d'un gestionnaire d'événement pour l'ouverture de la dropdown
dropdownLink.addEventListener('click', function(event){
    // Annulation du comportement par défaut du lien
    event.preventDefault();
    // Affectation de la classe 'dropdown-active' à l'élément 'dropdownContent' 
    dropdownContent.classList.toggle('active-dropdown')
})

// Définition d'un gestionnaire d'événement au click sur l'élément window pour fermer la dropdown
window.onclick = function(event) {
    // Si on ne clique pas sur le sélecteur dropdown
    if(!event.target.matches('.dropdown')) {
        // Alors on supprime l'attribut 'active-dropdown' de l'élément 'dropdownContent' 
        dropdownContent.classList.remove('active-dropdown');
    }
}



