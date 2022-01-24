<?php  

namespace App\Controllers\Account;

use App\Services\Utility;
use App\Services\Security;
use App\Controllers\MainController;
use App\Models\Managers\UserManager;
use App\Models\Entities\User;
use App\Services\Mail;


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
        // On vérifie que le slug de l'utilisateur et le token d'activation récupérés via l'url sont identiques à ceux stockés dans la base de données pour activer son compte
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
            if(isset($_POST['email'], $_POST['password']) && !empty($_POST['email']) && !empty($_POST['password'])) {
                // Récupération, formatage et sécurisation des données du formulaire de connexion
                $email = Security::secureHtml($_POST['email']);
                $password = Security::secureHtml($_POST['password']);
                // Vérification que l'email saisi correspond au format 'email'
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    // On affiche un message d'erreur
                    Utility::addAlertMessage("L'email saisi n'est pas valide !", Utility::DANGER_MESSAGE);
                }
                
                // On récupère en base de données une instance de la classe utilisateur correspondant à l'email saisi 
                $user = $this->userManager->getUserByEmail($email);
                
                // Si l'utilisateur existe
                if($user) {
                    // Si le mot de passe saisi correspond au mot de passe de l'utilisateur enregistré en base de données
                    if(password_verify($password, $user->getPassword())) {
                        // Si le compte de l'utilisateur concerné est actif
                        if($user->getIsValid() == 1) {
                            // On affiche un message d'alerte de succès
                            Utility::addAlertMessage("Connexion établie !", Utility::SUCCESS_MESSAGE);
                            // On stocke les informations de connexion de l'utilisateur dans la session
                            $_SESSION['user'] = [
                                "firstName" => $user->getFirstName(),
                                "lastName" => $user->getLastName(),
                                "email" => $user->getEmail(),
                                "role" => $user->getRole()
                            ];
                            // Si l'option "Se souvenir de moi" a été sélectionnée
                            if(isset($_POST['rememberMe'])) {
                                // Création de cookies pour l'email et le mot de passe
                                setcookie("email", $email, time()+365*24*3600,'/','', true,true);
                                setcookie("password", $password, time()+365*24*3600, '/','',true, true);
                            // Si l'option n'a pas été sélectionnée
                            } else {
                                // Si un cookie 'email' existe
                                if(isset($_COOKIE['email'])) {
                                    // Alors on supprime la valeur du cookie 'email'
                                    setcookie($_COOKIE['email'],""); 
                                }
                                // Si un cookie 'password' existe
                                if(isset($_COOKIE['password'])) {
                                    // Alors on supprime la valeur du 'password''
                                    setcookie($_COOKIE['password'],""); 
                                }
                            }

                            // On redirige l'utilisateur vers la page de profil
                            Utility::redirect(URL."compte/profil");
                           
                        } else {
                            // Si le compte n'est pas actif, on génère un message d'erreur
                            Utility::addAlertMessage("Le compte ".$email." n'a pas été activé. Veuillez cliquer sur le lien qui vous a été envoyé par email !", Utility::DANGER_MESSAGE);
                            // On renvoie le mail d'activation à l'utilisateur

                            // On redirige l'utilisateur vers la page de connexion
                            Utility::redirect(URL."connexion");
                        }
                    } else {
                        // Si les mots de passe ne correspondent pas, on affiche un message d'erreur
                        Utility::addAlertMessage("Identifiants invalides !", Utility::DANGER_MESSAGE);
                        // On redirige l'utilisateur vers la page de connexion
                        Utility::redirect(URL."connexion");
                    }
                       
                } else {
                    // Si l'utilisateur n'existe pas, on affiche un message d'erreur
                    Utility::addAlertMessage("Identifiants invalides !", Utility::DANGER_MESSAGE);
                    // On redirige l'utilisateur vers la page de connexion
                    Utility::redirect(URL."connexion");
                }
            } else {
                // Affichage d'un message d'alerte si le formulaire est incomplet
                Utility::addAlertMessage("Echec de la connexion. L'email et/ou le mot de passe ne sont pas renseignés !", Utility::DANGER_MESSAGE);
                // Redirection de l'utilisateur vers la page de connexion
                Utility::redirect(URL."connexion");
            }
        }
    }

    /**
     * Contrôle le paramétrage et l'affichage de la page de profil de l'utilisateur authentifié
     *
     * @return void
     */
    public function profile()
    {
        // Récupération des données de l'utilisateur authentifié
        $userData = $this->userManager->getUserByEmail($_SESSION['user']['email']);
    
        // Définition d'un tableau associatif regroupant les données d'affichage de la page d'accueil du site
        $data_page = [
        "page_description" => "Page de profil",
        "page_title" => "Page de profil",
        "user_data" => $userData,
        "view" => "views/account/profile.php",
        "template" => "views/common/template.php"
        ];
        // Affichage de la page à l'aide de la méthode generatePage à laquelle on envoie le tableau de données
        $this->generatePage($data_page);
    }

    /**
     * Contrôle la déconnexion de l'utilisateur
     *
     * @return void
     */
    public function logout()
    {
        // Destruction de la variable de session
        unset($_SESSION['user']);
        // Message d'alerte
        Utility::addAlertMessage("Déconnexion effectuée !", Utility::SUCCESS_MESSAGE);
        // On redirige l'utilisateur vers la page d'accueil
        Utility::redirect(URL."accueil");
    }

    /**
     * Contrôle le paramétrage et l'affichage de la page de réinitialisation du mot de passe
     *
     * @return void
     */
    public function forgotten_password() 
    {
        // Définition d'un tableau associatif regroupant les données d'affichage de la page de réinitialisation du mot de passe
        $data_page = [
            "page_description" => "Réinitialisation du mot de passe",
            "page_title" => "Page de réinitialisation du mot de passe",
            "view" => "views/account/forgotten_password.php",
            "template" => "views/common/template.php"
            ];
            // Affichage de la page à l'aide de la méthode generatePage à laquelle on envoie le tableau de données
            $this->generatePage($data_page);
    }

    /**
     * Contrôle le traitement du formulaire de demande de réinitialisation du mot de passe
     *
     * @return void
     */
    public function reset_password()
    {
        // Vérification de la soumission du formulaire de demande de réinitialisation
        if(!empty($_POST)) {
            // Vérification que tous les champs requis sont renseignés
            if(isset($_POST['email'])) {
                // Récupération, formatage et sécurisation des données du formulaire de réinitialisation
                $email = Security::secureHtml($_POST['email']);
                // Vérification que l'email saisi correspond au format 'email'
                if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    // On affiche un message d'erreur
                    Utility::addAlertMessage("L'email saisi n'est pas valide !", Utility::DANGER_MESSAGE);
                     // Redirection de l'utilisateur vers la page de réinitialisation
                    Utility::redirect(URL."mot_de_passe_oublie");
                }
                // On récupère en base de données une instance de la classe utilisateur correspondant à l'email saisi 
                $user = $this->userManager->getUserByEmail($email);

                // Si l'utilisateur existe
                if($user) {
                    // Si le compte utilisateur n'est pas encore activé
                    if($user->getIsValid() == 0) {
                        
                        // On génère d'un nouveau token d'activation de compte
                        $newActivationToken = Utility::generateToken();
                        
                        // On affecte la valeur du token de l'utilisateur à l'attribut correspondant à l'aide du setter de la classe User
                        $user->setActivationToken($newActivationToken);
                        
                        // On enregistre la valeur du nouveau token de l'utilisateur concerné dans la base de données
                        $isUpdateToken = $this->userManager->updateActivationToken($user, $newActivationToken);
                        
                        // Si la requête d'actualisation du token a abouti
                        if($isUpdateToken) {
                            // Affichage d'un message d'alerte
                            Utility::addAlertMessage("Votre email correspond bien à un compte utilisateur présent dans notre base de données. Ce dernier n'a toutefois pas encore été activé  ! Un mail vient de vous être envoyé à l'adresse " . $user->getEmail() ." pour activer votre compte.", Utility::DANGER_MESSAGE);
                            
                            // On envoie un nouvel email d'activation de compte à l'utilisateur à partir de ses informations
                            Mail::userActivationMail($user);

                        } else {
                            // Affichage d'un message d'alerte
                            Utility::addAlertMessage("Une erreur s'est produite !", Utility::DANGER_MESSAGE);
                            
                            // Redirection de l'utilisateur vers la page de réinitialisation
                            Utility::redirect(URL."mot_de_passe_oublie");
                        }
                    }

                    // Si le compte utilisateur a déjà été activé
                    if($user->getIsValid() == 1) {
                        
                        // On génère un token de réinitialisation
                        $resetToken = Utility::generateToken();

                        // On affecte la valeur du token à l'attribut de l'objet User
                        $user->setResetToken($resetToken);
                        
                        // On enregistre la valeur en base de données
                        $isResetToken = $this->userManager->setResetTokenIntoDatabase($user, $resetToken);
                        
                        // Si la requête d'enregistrement du token de réinitialisation a abouti
                        if($isResetToken) {
                            // Affichage d'un message d'alerte si l'utilisateur n'existe pas
                            Utility::addAlertMessage("Votre email correspond bien à un utilisateur présent dans notre base de données ! Un mail vient de vous être envoyé à l'adresse " . $user->getEmail() ." pour réinitialiser votre compte.", Utility::SUCCESS_MESSAGE);
    
                            // On envoie un email de réinitialisation du mot de passe à l'utilisateur à partir de ses informations
                            Mail::userResetMail($user);
                        } else {
                            // Affichage d'un message d'alerte
                            Utility::addAlertMessage("Une erreur s'est produite !", Utility::DANGER_MESSAGE);
                            
                            // Redirection de l'utilisateur vers la page de réinitialisation
                            Utility::redirect(URL."mot_de_passe_oublie");
                        }
                    }
                    // Redirection de l'utilisateur vers la page de réinitialisation
                    Utility::redirect(URL."mot_de_passe_oublie");
                } else {
                    // Affichage d'un message d'alerte si l'utilisateur n'existe pas
                    Utility::addAlertMessage("Aucun utilisateur n'existe avec cet email !", Utility::DANGER_MESSAGE);
                    // Redirection de l'utilisateur vers la page de réinitialisation
                    Utility::redirect(URL."mot_de_passe_oublie");
                }

            } else {
                // Affichage d'un message d'alerte si le formulaire est incomplet
                Utility::addAlertMessage("Le formulaire est incomplet, veuillez saisir votre mot de passe !", Utility::DANGER_MESSAGE);
                // Redirection de l'utilisateur vers la page de réinitialisation
                Utility::redirect(URL."mot_de_passe_oublie");
            }
        } 
    }

    /**
     * Contrôle la procédure de vérification du slug et du token de réinitialisation du mot de passe de l'utilisateur
     *
     * @param [type] $userSlug
     * @param [type] $resetToken
     * @return void
     */
    public function reset_password_verification($userSlug, $resetToken)
    {
        // On vérifie que le slug et le token de réinitialisation récupérés via l'url correspondent bien à un utilisateur stocké dans la base de données 
        if($this->userManager->isUserWithSlugAndResetToken($userSlug, $resetToken)){
            // Si tel est le cas, on affiche le formulaire de réinitialisation du mot de passe à l'aide de la méthode new_password_form() dans laquelle on passe le slug et resetToken de l'utilisateur en paramètres
            $this->new_password_form($userSlug, $resetToken);
        } else {
            // Si tel n'est le cas, on affiche un message d'échec
            Utility::addAlertMessage("<i class='far fa-frown'></i> Votre compte n'a pas pu être réinitialisé ! Veuillez cliquer sur le lien de réinitialisation envoyé par mail !", Utility::DANGER_MESSAGE);
            // On redirige l'utilisateur vers la page de connexion
            Utility::redirect(URL."formulaire_nouveau_mot_de_passe");
        }
    }

    /**
     * Contrôle le paramétrage et l'affichage de la page du formulaire de saisie du mot de passe de l'utilisateur correspondant au slug et au token de réinitialisation
     *
     * @param [type] $userSlug
     * @param [type] $resetToken
     * @return void
     */
    public function new_password_form($userSlug, $resetToken)
    {
        // Définition d'un tableau associatif regroupant les données d'affichage de la page du formulaire de réinitialisation du mot de passe
        $data_page = [
            "page_description" => "Nouveau mot de passe",
            "page_title" => "Formulaire de saisie du nouveau mot de passe",
            "view" => "views/account/new_password.php",
            "template" => "views/common/template.php",
            "resetToken" => $resetToken,
            "userSlug" => $userSlug
            ];
            // Affichage de la page à l'aide de la méthode generatePage à laquelle on envoie le tableau de données
            $this->generatePage($data_page);
    }

    /**
     * Contrôle le traitement du formulaire de saisie du nouveau mot de passe
     *
     * @return void
     */
    public function new_password_validation()
    {
        // Vérification de la soumission du formulaire de connexion
        if(!empty($_POST)) {
            // Vérification que tous les champs requis sont renseignés
            if(isset($_POST['new_password'], $_POST['new_password_confirm']) && !empty($_POST['new_password']) && !empty($_POST['new_password_confirm'])) {
                // Récupération, formatage et sécurisation des données du formulaire de saisie du nouveau mot de passe
                $newPassword = Security::secureHtml($_POST['new_password']);
                $newPasswordConfirm = Security::secureHtml($_POST['new_password_confirm']);
                $userSlug = Security::secureHtml($_POST['userSlug']);
                $resetToken = Security::secureHtml(($_POST['resetToken']));

                // Si les deux mots de passe sont identiques
                if($newPassword == $newPasswordConfirm) {
                    // Hachage du mot de passe
                    $hashNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                    // On récupère en base de données une instance de la classe utilisateur correspondant au slug et au token transmis 
                    $user = $this->userManager->getUserBySlugAndResetToken($userSlug, $resetToken);

                    // Si la requête a abouti et qu'un utilisateur existe
                    if($user) {

                        // On affecte la valeur nouveau mot de passe hasché à l'attribut de l'objet User
                        $user->setPassword($hashNewPassword);
                            
                        // On enregistre la valeur du nouveau mot de passe en base de données
                        $isNewPasswordIntoDatabase = $this->userManager->setNewPasswordIntoDatabase($user, $hashNewPassword);
                        
                        // Si la requête d'enregistrement du nouveau mot de passe a abouti
                        if($isNewPasswordIntoDatabase) {
                            // Affichage d'un message d'alerte si l'utilisateur n'existe pas
                            Utility::addAlertMessage("Votre mot de passe a bien été modifié. Veuillez vous connecter à votre compte en utilisant votre nouveau mot de passe ", Utility::SUCCESS_MESSAGE);
    
                            // Redirection de l'utilisateur vers la page de connexion
                            Utility::redirect(URL."connexion");
                        } else {
                            // Affichage d'un message d'alerte
                            Utility::addAlertMessage("Une erreur s'est produite !", Utility::DANGER_MESSAGE);
                            
                            // Redirection de l'utilisateur vers la page de réinitialisation
                            Utility::redirect(URL."mot_de_passe_oublie");
                        }
                    } else {
                        // Affichage d'un message d'alerte
                        Utility::addAlertMessage("Une erreur s'est produite !", Utility::DANGER_MESSAGE);
                            
                        // Redirection de l'utilisateur vers la page de réinitialisation
                        Utility::redirect(URL."mot_de_passe_oublie");
                    }
                } else {
                    // Affichage d'un message d'alerte si les deux mots de passe ne sont pas identiques
                    Utility::addAlertMessage("Les deux mots de passe ne sont pas identiques. Merci de bien vouloir renouveller votre saisie !", Utility::DANGER_MESSAGE); 
                    // Redirection de l'utilisateur vers le formulaire de saisie du nouveau mot de passe
                    Utility::redirect(URL."nouveau_mot_de_passe"."/".$userSlug."/".$resetToken);
                }
                
            } else {
                // Affichage d'un message d'alerte si le formulaire est incomplet
                Utility::addAlertMessage("Le formulaire est incomplet, veuillez saisir votre nouveau mot de passe et le confirmer !", Utility::DANGER_MESSAGE);
                // Redirection de l'utilisateur vers le formulaire de saisie du nouveau mot de passe
                Utility::redirect(URL."nouveau_mot_de_passe"."/".$userSlug."/".$resetToken);
            }
        } 
    }
}