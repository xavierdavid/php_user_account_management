// Vérification dynamique des champs du formulaire de modification du mot de passe

// Récupération des éléments du DOM
let newPasswordElt = document.querySelector('#newPassword'); 
let confirmNewPasswordElt = document.querySelector('#confirmNewPassword'); 
let validationBtn = document.querySelector('#validationBtn'); 
let errorMessage = document.querySelector('#errorMessage'); 

// Définition de gestionnaires d'événement pour gérer les changements de champ de formulaire 
newPasswordElt.addEventListener("keyup", function(){
    passwordVerification();
});
    
confirmNewPasswordElt.addEventListener("keyup", function(){
        passwordVerification();
 });

// Fonctions
function passwordVerification(){
    // Récupération et comparaison des valeurs saisies dans les deux champs de formulaire
        if(newPasswordElt.value === confirmNewPasswordElt.value) {
            // On active le bouton de soumission du formulaire pour permette l'envoi des données
            validationBtn.disabled = false;
            // On masque le message d'erreur
            errorMessage.classList.add("display-none");
        } else {
            // On désactive le bouton de soumission du formulaire pour bloquer l'envoi des données
            validationBtn.disabled = true;
            // On affiche le message d'erreur
            errorMessage.classList.remove("display-none");
        }
} 
