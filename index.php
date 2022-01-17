<?php 

// Utilisation de l'Autoloader associé au namespace App
use App\Autoloader;
// Utilisation des contrôleurs
use App\Controllers\Home\HomeController; 
use App\Controllers\Account\AccountController;
use App\Services\Security;
use App\Services\Utility;

// Démarrage de la session
session_start();

// Chargement de l'autoloader
require_once 'Autoloader.php';
Autoloader::register();

// Instanciation des contrôleurs
$homeController = new HomeController;
$accountController = new AccountController;

// Définition d'une constante d'URL contenant le chemin absolu du site
define("URL", str_replace("index.php","",(isset($_SERVER['HTTPS']) ? "https" : "http")."://$_SERVER[HTTP_HOST]$_SERVER[PHP_SELF]"));

// Mise en place du système de routage du site
try {
    // Si aucun paramètre 'page' n'existe dans l'url
    if(empty($_GET['page'])) {
        // Alors on demande l'affichage de la page d'accueil 
        $page = "accueil";
    } else {
        // Sinon, on scinde et on filtre l'url pour l'analyser
        $url = explode("/", filter_var($_GET['page'], FILTER_SANITIZE_URL));
        // On récupère le 1er élément de l'url et on le stocke dans la variable "$page"
        $page = $url[0];
    }

    // On teste la valeur des éléments de l'url et on appelle les contrôleurs appropriés
    switch ($page) {
        case "accueil": $homeController->home();
            break;
        case "inscription" : $homeController->register();
            break;
        case "validation_inscription" : $homeController->register_validation();
            break;
        case "activation_compte" : $accountController->account_activation($url[1], $url[2]);
            break;
        case "connexion" : $homeController->login();
            break;
        case "validation_connexion" : $accountController->login_validation();
            break;
        case "mot_de_passe_oublie" : $accountController->forgotten_password();
            break;
        case "reinitialisation_mot_de_passe" : $accountController->reset_password();
            break;
        case "nouveau_mot_de_passe" : $accountController->reset_password_verification($url[1], $url[2]);
            break;
        case "compte" :
            // Vérification de l'authentification de l'utlisateur
            if(Security::isAuthenticated()) {
                // On teste le 2ème élément de l'url
                switch ($url[1]) {
                    case "profil" : $accountController->profile();
                        break;
                    case "deconnexion" : $accountController->logout();
                        break;
                        default:
                        // Dans les autres cas, on lance une exception
                        throw new Exception("La page demandée n'existe pas.");
                }    
            } else {
                // Si l'utilisateur n'est pas authentifié, on envoie un message d'alerte
                Utility::addAlertMessage("Veuillez vous connecter pour accéder à cette section !", Utility::WARNING_MESSAGE);
                // On redirige l'utilisateur vers la page de login
                Utility::redirect(URL."connexion");
            }
        break;  
        default:
            // Dans les autres cas, on lance une exception
            throw new Exception("La page demandée n'existe pas.");
    }
    
} catch (Exception $e) {
    // Récupération des messages d'erreur du bloc try et gestion de l'affichage
    $homeController->errorPage($e->getMessage()); 
}



