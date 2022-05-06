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

// Mise en place du système de routage du site - Gestion des paramètres 'page' de l'url
try {
    // Si aucun paramètre n'est envoyé dans dans la variable 'page' de l'url
    if(empty($_GET['page'])) {
        // Alors on demande l'affichage de la page d'accueil 
        $page = "accueil";
    } else {
        // Si des paramètres sont envoyés dans la variable 'page' de l'url, on les sépare, on les nettoie et on les stocke dans un tableau
        $urlParameters = explode("/", filter_var($_GET['page'], FILTER_SANITIZE_URL));
        // On récupère le 1er paramètre de l'url et on le stocke dans la variable "$page"
        $page = $urlParameters[0];
    }

    // On teste la valeur des paramètres de l'url et on appelle les contrôleurs appropriés
    switch ($page) {
        case "accueil": $homeController->home();
            break;
        case "inscription" : $homeController->register();
            break;
        case "validation_inscription" : $homeController->register_validation();
            break;
        case "activation_compte" : $accountController->account_activation($urlParameters[1], $urlParameters[2]);
            break;
        case "connexion" : $homeController->login();
            break;
        case "validation_connexion" : $accountController->login_validation();
            break;
        case "mot_de_passe_oublie" : $accountController->forgotten_password();
            break;
        case "reinitialisation_mot_de_passe" : $accountController->reset_password();
            break;
        case "nouveau_mot_de_passe" : $accountController->reset_password_verification($urlParameters[1], $urlParameters[2]);
            break;
        case "validation_nouveau_mot_de_passe" : $accountController->new_password_validation();
            break;
        case "compte" :
            // Vérification de l'authentification de l'utilisateur
            if(Security::isAuthenticated()) {
                // On teste le 2ème paramètre de l'url
                switch ($urlParameters[1]) {
                    case "profil" : $accountController->profile();
                        break;
                    case "deconnexion" : $accountController->logout();
                        break;
                    case "validation_modification_email" : $accountController->user_email_validation();
                        break;
                    case "modification_mot_de_passe" : $accountController->password_modification();
                        break;
                    case "validation_modification_mot_de_passe" : $accountController->edit_password_validation();
                        break;
                    case "validation-suppression-compte" : $accountController->user_account_deletion();
                        break;
                    case "modification_profil" : $accountController->user_profile_modification();
                        break;
                    case "validation_modification_profil" : $accountController->profile_modification_validation();
                        break;
                    case "espace_membre" :
                        // Vérification du statut de membre de l'utilisateur authentifié
                        if(Security::isMember()) {
                            echo "Accès à l'espace membre !";
                        } else {
                            // Si l'utilisateur ne dispose pas du rôle administrateur, on envoie un message d'alerte
                            Utility::addAlertMessage("Vous ne disposez pas des droits pour accéder à cette section !", Utility::WARNING_MESSAGE);
                            // On redirige l'utilisateur vers la page de login
                            Utility::redirect(URL."accueil");
                        }
                        break;
                    case "administration" : 
                        // Vérification du rôle d'administrateur de l'utilisateur authentifié
                        if(Security::isAdmin()) {
                            echo "Accès au Back-office !";
                        } else {
                            // Si l'utilisateur ne dispose pas du rôle administrateur, on envoie un message d'alerte
                            Utility::addAlertMessage("Vous ne disposez pas des droits pour accéder à cette section !", Utility::WARNING_MESSAGE);
                            // On redirige l'utilisateur vers la page de login
                            Utility::redirect(URL."accueil");
                        }
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



