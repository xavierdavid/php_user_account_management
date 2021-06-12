// Gestion du menu responsive

// Récupération des éléments du DOM 
const menu = document.querySelector('.list-nav'); // Liste du menu
const btnMenu = document.querySelector('.btn-toggle-container'); // Icone hamburger

// Définition d'un gestionnaire d'événement 
btnMenu.addEventListener('click', function() {
    // Ajoute ou supprime la classe 'active-menu' au menu '.list-nav' 
    menu.classList.toggle('active-menu');
})

