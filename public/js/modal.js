// Gestion de l'affichage de la fenêtre modale de confirmation de suppression

// Récupération des éléments du DOM
const modalContainer = document.querySelector('.modal-container');
const modalTriggers = document.querySelectorAll(".modal-trigger");


// Définition d'un gestionnaire d'événement qui permet d'afficher la modale au clic sur un élément déclencheur trigger
modalTriggers.forEach(trigger => trigger.addEventListener("click", toggleModal));


// Définitions de fonctions
function toggleModal() {
    modalContainer.classList.toggle("active");
}