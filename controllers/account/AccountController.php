<?php  

namespace App\Controllers\Account;

use App\Services\Utility;
use App\Services\Security;
use App\Controllers\MainController;
use App\Models\Managers\UserManager;


class AccountController extends MainController
{
    private $userManager;

    /**
     * Initialisation d'une instance de la classe UserManager
     */
    public function __construct() 
    {
        $this->userManager = new UserManager;
    }

   /**
    * Permet d'activer le compte d'un utilisateur
    *
    * @param [type] $userSlug
    * @param [type] $activationToken
    * @return void
    */
    public function account_activation($userSlug, $activationToken)
    {
        // On vérifie si le slug de l'utilisateur et le token d'activation récupérés via l'url sont identiques à ceux stockés dans la base de données
        if($this->userManager->activate_account($userSlug, $activationToken)){
            // Si tel est le cas, on indique à l'utilisateur que son compte est activé
            Utility::addAlertMessage("<i class='far fa-smile'></i> Votre compte a été activé !", Utility::SUCCESS_MESSAGE);
            // On redirige l'utilisateur vers la page de connexion
            Utility::redirect(URL."connexion");
        } else {
            // Si tel est le cas, on affiche un message d'échec
            Utility::addAlertMessage("<i class='far fa-frown'></i> Votre compte n'a pas été activé. Veuillez cliquer sur le lien d'activation envoyé par mail !", Utility::DANGER_MESSAGE);
            // On redirige l'utilisateur vers la page de connexion
            Utility::redirect(URL."connexion");
        }
    }

    /**
     * Contrôle la procédure d'identification et de connexion d'un utilisateur
     *
     * @return void
     */
    public function login_validation()
    {
        // Vérification de la soumission du formulaire de connexion
        if(!empty($_POST)) {
            // Vérification que tous les champs requis sont renseignés
            if(isset($_POST['email'], $_POST['password'])
            && !empty($_POST['email'])
            && !empty($_POST['password'])) {
                // Récupération, formatage et sécurisation des données du formulaire de connexion
                $email = Security::secureHtml($_POST['email']);
                $password = Security::secureHtml($_POST['password']);
                // Vérification de l'authentification de l'utilisateur
                if($this->userManager->isAuthenticationValid($email, $password)) {
                    // On vérifie si le compte de l'utilisateur concerné est actif
                    if($this->userManager->isAccountValid($email)) {
                        // On affiche un message d'alerte de succès
                        Utility::addAlertMessage("Connexion établie !", Utility::SUCCESS_MESSAGE);
                        // On stocke les informations du profil de l'utilisateur dans la session
                        $_SESSION['profil'] = [
                            "email" => $email
                        ];
                        // Redirection vers la page de profil
                        Utility::redirect(URL."compte/profil");
                    } else {
                        // On génère un message d'erreur
                        Utility::addAlertMessage("Le compte ".$email." n'a pas été activé. Veuillez cliquer sur le lien qui vous a été envoyé par email !", Utility::DANGER_MESSAGE);
                        // On renvoie le mail d'activation à l'utilisateur

                        // On redirige l'utilisateur vers la page de connexion
                        Utility::redirect(URL."connexion");
                    }
                } else {
                    // On génère un message d'erreur
                    Utility::addAlertMessage("Email ou mot de passe invalide !", Utility::DANGER_MESSAGE);
                    // On redirige l'utilisateur vers la page de connexion
                    Utility::redirect(URL."connexion");
                }
            } else {
                // Affichage d'un message d'alerte si le formulaire est incomplet
                Utility::addAlertMessage("Echec de la connexion. L'email et/ou le mot de passe ne sont pas renseignés !", Utility::DANGER_MESSAGE);
                // Redirection de l'utilisateur vers la page de connexion
                Utility::redirect(URL."connexion");
            }
        } else {
            // Affichage d'un message d'alerte si le formulaire est incomplet
            Utility::addAlertMessage("Echec de la connexion. L'email et/ou le mot de passe ne sont pas renseignés !", Utility::DANGER_MESSAGE);
            // Redirection de l'utilisateur vers la page de connexion
            Utility::redirect(URL."connexion");
        }
    }

    /**
     * Contrôle le paramétrage et l'affichage de la page de profil d el'utilisateur
     *
     * @return void
     */
    public function profile()
    {
        // Définition d'un tableau associatif regroupant les données d'affichage de la page d'accueil du site
        $data_page = [
        "page_description" => "Page de profil",
        "page_title" => "Page de profil",
        "view" => "views/account/account.php",
        "template" => "views/common/template.php"
        ];
        // Affichage de la page à l'aide de la méthode generatePage à laquelle on envoie le tableau de données
        $this->generatePage($data_page);
    }
}