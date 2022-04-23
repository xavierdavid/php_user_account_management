<?php  

namespace App\Controllers\Account;

use Exception;
use App\Services\Mail;
use App\Services\Utility;
use App\Services\Security;
use App\Models\Entities\User;
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
                                "id" => $user->getId(),
                                "firstName" => $user->getFirstName(),
                                "lastName" => $user->getLastName(),
                                "coverImage" => $user->getCoverImage(),
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
        // Récupération des données de l'utilisateur authentifié à partir de son identifiant
        $userData = $this->userManager->show($_SESSION['user']['id']);
    
        // Définition d'un tableau associatif regroupant les données d'affichage de la page d'accueil du site
        $data_page = [
        "page_description" => "Page de profil",
        "page_title" => "Page de profil",
        "user_data" => $userData,
        "page_js" => ['edit-profile-email.js'],
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
        // On redirige l'utilisateur vers la page de connexion
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

    /**
     * Contrôle le traitement du formulaire de modification d'email de l'utilisateur authentifié
     *
     * @return void
     */
    public function user_email_validation()
    {
        // Vérification de la soumission du formulaire de modification
        if(!empty($_POST)) {
            // Vérification que tous les champs requis sont renseignés
            if(isset($_POST['email'])) {
                // Récupération, formatage et sécurisation des données du formulaire
                $newEmail = Security::secureHtml($_POST['email']);
                // Vérification que l'email saisi correspond au format 'email'
                if(!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
                    // On affiche un message d'erreur
                    Utility::addAlertMessage("L'email saisi n'est pas valide !", Utility::DANGER_MESSAGE);
                    // Redirection de l'utilisateur vers la page de profil
                    Utility::redirect(URL."compte/profil");
                }
                // On récupère en base de données une instance de la classe user correspondant à l'email de l'utilisateur en cours stocké dans la variable de session
                $user = $this->userManager->getUserByEmail($_SESSION['user']['email']);

                // On affecte la valeur du nouvel email saisi à l'attribut coorespondant de la classe User
                $user->setEmail($newEmail);

                // On enregistre la nouvelle valeur dans la base de données
                $isUserNewEmailIntoDatabase = $this->userManager->setUserNewEmailIntoDatabase($user, $newEmail);
                        
                // Si la requête d'enregistrement du nouvel email a abouti
                if($isUserNewEmailIntoDatabase) {
                    // On actualise les informations de l'utilisateur (dont le nouveau mot de passe) dans la session
                    $_SESSION['user'] = [
                        "id" => $user->getId(),
                        "firstName" => $user->getFirstName(),
                        "lastName" => $user->getLastName(),
                        "coverImage" => $user->getCoverImage(),
                        "email" => $user->getEmail(),
                        "role" => $user->getRole()
                    ];
                    // Affichage d'un message de succès
                    Utility::addAlertMessage("Votre nouvel email " . $user->getEmail() ." a bien été enregistré !", Utility::SUCCESS_MESSAGE);
                    // On redirige l'utilisateur
                    //Utility::redirect(URL."compte/profil");
                } else {
                     // Affichage d'un message d'alerte
                    Utility::addAlertMessage("Aucune modification effectuée !", Utility::DANGER_MESSAGE); 
                }
                // On redirige l'utilisateur
                Utility::redirect(URL."compte/profil");
                    
            } else {
                // On affiche un message d'erreur
                Utility::addAlertMessage("Merci de renseigner un nouvel email !", Utility::DANGER_MESSAGE);
                // Redirection de l'utilisateur vers la page de profil
                Utility::redirect(URL."compte/profil");
            }
        } else {
           // Affichage d'un message d'alerte si le formulaire est incomplet
           Utility::addAlertMessage("Le formulaire est incomplet, veuillez saisir un nouvel email !", Utility::DANGER_MESSAGE);
           // Redirection de l'utilisateur vers le formulaire de saisie du nouveau mot de passe
           Utility::redirect(URL."compte/profil");
        }
    }

    /**
     * Contrôle le paramétrage et l'affichage de la page du formulaire de modification du mot de passe de l'utilisateur authentifié
     *
     * @return void
     */
    public function password_modification()
    {
        // Définition d'un tableau associatif regroupant les données d'affichage de la page du formulaire de modification du mot de passe
        $data_page = [
            "page_description" => "Modification du mot de passe",
            "page_title" => "Formulaire de modification du mot de passe",
            "page_js" => ['confirm-new-password.js'],
            "view" => "views/account/password_modification.php",
            "template" => "views/common/template.php",
            ];
            // Affichage de la page à l'aide de la méthode generatePage à laquelle on envoie le tableau de données
            $this->generatePage($data_page);
    }

    /**
     * Contrôle le traitement du formulaire de modification du mot de passe de l'utilisateur authentifié
     *
     * @return void
     */
    public function edit_password_validation()
    {
        // Vérification de la soumission du formulaire de modification
        if(!empty($_POST)) {
            // Vérification que tous les champs requis sont renseignés
            if(isset($_POST['oldPassword'], $_POST['newPassword'], $_POST['confirmNewPassword']) && !empty($_POST['oldPassword']) && !empty($_POST['newPassword']) && !empty($_POST['confirmNewPassword'])) {
                // Récupération, formatage et sécurisation des données du formulaire
                $oldPassword = Security::secureHtml($_POST['oldPassword']);
                $newPassword = Security::secureHtml($_POST['newPassword']);
                $confirmNewPassword = Security::secureHtml($_POST['confirmNewPassword']);
                // Vérification que le nouveau mot de passe et la confirmation sont identiques
                if($newPassword === $confirmNewPassword) {
                    // On récupère en base de données une instance de l'objet utilisateur authentifié à partir de son identifiant stocké en session
                    $user = $this->userManager->show($_SESSION['user']['id']);
                
                    // Si l'utilisateur existe
                    if($user) {
                        // Si le mot de passe actuel saisi correspond au mot de passe de l'utilisateur enregistré en base de données
                        if(password_verify($oldPassword, $user->getPassword())) {
                            // On hashe le nouveau mot de passe saisie
                            $newHashPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                            // On affecte le nouveau mot de passe à l'utilisateur en cours
                            $user->setPassword($newHashPassword);
                            // On enregistre le nouveau mot de passe hashé en base de données
                            $isNewPasswordIntoDatabase = $this->userManager->setNewPasswordIntoDatabase($user, $newHashPassword);
                            // On vérifie que la requête a abouti
                            if($isNewPasswordIntoDatabase) {
                                // On affiche un message de succès
                                Utility::addAlertMessage("Votre mot de passe a été modifié avec succès", Utility::SUCCESS_MESSAGE);
                                // On redirige l'utilisateur
                                Utility::redirect(URL."compte/profil");
                            } else {
                                // On affiche un message d'erreur
                                Utility::addAlertMessage("Une erreur est survenue", Utility::DANGER_MESSAGE);
                                // On redirige l'utilisateur
                                Utility::redirect(URL."compte/modification_mot_de_passe");
                            }

                        } else {
                            // On affiche un message d'erreur
                            Utility::addAlertMessage("Aucun utilisateur ne correspond au mot de passe actuel", Utility::DANGER_MESSAGE);
                            // On redirige l'utilisateur
                            Utility::redirect(URL."compte/modification_mot_de_passe");
                        }
                    } else {
                        // On affiche un message d'erreur
                        Utility::addAlertMessage("Utilisateur inconnu !", Utility::DANGER_MESSAGE);
                        // On redirige l'utilisateur
                        Utility::redirect(URL."compte/modification_mot_de_passe");
                    }
                } else {
                    // On affiche un message d'erreur
                    Utility::addAlertMessage("Les deux mots de passe ne sont pas identiques", Utility::DANGER_MESSAGE);
                    // On redirige l'utilisateur
                    Utility::redirect(URL."compte/modification_mot_de_passe");
                }
            } else {
                // On affiche un message d'erreur
                Utility::addAlertMessage("Toutes les informations n'ont pas été renseignées", Utility::DANGER_MESSAGE);
                // On redirige l'utilisateur
                Utility::redirect(URL."compte/modification_mot_de_passe");
            }
        }
    }

    /**
     * Permet de contrôler la suppression du compte utilisateur 
     *
     * @return void
     */
    public function user_account_deletion() 
    {
        // On récupère en base de données une instance de l'objet utilisateur authentifié à partir de son identifiant stocké en session
        $user = $this->userManager->show($_SESSION['user']['id']);
        // On récupère l'ancienne image de profil de l'utilisateur éventuellement stockée en base de données 
        $oldCoverImage = $user->getCoverImage();
        // On supprime cette image du dossier 'public/img/users/'
        $this->deleteUserImageFile($oldCoverImage);
        // On supprime l'utilisateur de la base de données
        $isUserDelete = $this->userManager->delete($user);
        // Si la requête abouti
        if($isUserDelete) {
            // On affiche un message à l'utilisateur
            Utility::addAlertMessage("Votre compte a bien été supprimé !", Utility::SUCCESS_MESSAGE);
            // On déconnecte l'utilisateur
            $this->logout();
        } else {
            // On affiche un message à l'utilisateur
            Utility::addAlertMessage("La suppression de votre compte a échoué ! Veuillez contacter l'administrateur.", Utility::DANGER_MESSAGE);
            // On redirige l'utilisateur vers la page de profil
            Utility::redirect(URL."compte/profil");
        }
    }

    /**
     * Permet de contrôler le paramétrage et l'affichage de la page du formulaire de modification des informations de profil de l'utilisateur
     *
     * @return void
     */
    public function user_profile_modification()
    {
        // On récupère une instance de l'utilisateur en cours à partir de la session
        $user = $this->userManager->show($_SESSION['user']['id']);

        // Définition d'un tableau associatif regroupant les données d'affichage de la page du formulaire de modification des informations du profil de l'utilisateur
        $data_page = [
            "page_description" => "Page de modification du profil",
            "page_title" => "Page de modification du profil",
            "user_data" => $user,
            "view" => "views/account/edit_profile.php",
            "template" => "views/common/template.php"
            ];
            // Affichage de la page à l'aide de la méthode generatePage à laquelle on envoie le tableau de données
            $this->generatePage($data_page);
    }

    /**
     * Permet de contrôler le traitement du formulaire de modification du profil de l'utilisateur
     *
     * @return void
     */
    public function profile_modification_validation() 
    {
        // Vérification de la soumission du formulaire
        if(!empty($_POST)) {
            // Vérification que tous les champs requis sont renseignés
            if(isset($_POST['firstName'], $_POST['lastName'], $_POST['address'], $_POST['postal'], $_POST['city'], $_POST['country'], $_POST['phone'])
            && !empty($_POST['firstName']) 
            && !empty($_POST['lastName']) 
            && !empty($_POST['address']) 
            && !empty($_POST['postal']) 
            && !empty($_POST['city']) 
            && !empty($_POST['country'])
            && !empty($_POST['phone'])) {

                // Récupération, formatage et sécurisation des données du formulaire
                $firstName = Security::secureHtml(ucfirst($_POST['firstName']));
                $lastName = Security::secureHtml(strtoupper($_POST['lastName']));
                $address = Security::secureHtml($_POST['address']);
                $postal = Security::secureHtml($_POST['postal']);
                $city = Security::secureHtml(strtoupper($_POST['city']));
                $country = Security::secureHtml(strtoupper($_POST['country']));
                $phone = Security::secureHtml($_POST['phone']);

                // Génération automatique du slug de l'utilisateur à l'aide de la fonction generateSlug(), à partir d'une chaîne composée de son nom et de son prénom
                $text = $firstName . " " . $lastName;
                $slug = Utility::generateSlug($text);

                // Stockage des données modifiées de l'utilisateur dans un tableau associatif $userData
                $userData = [
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'address' => $address,
                    'postal' => $postal,
                    'city' => $city,
                    'country' => $country,
                    'phone' => $phone,
                    'slug' => $slug,
                ];

                // Stockage temporaire des données modifiées de l'utilisateur dans la session pour pré-remplir le formulaire de modification en cas d'erreur
                $_SESSION['editUserData'] = $userData;

                // Récupération d'une instance de l'utilisateur en cours 
                $user = $this->userManager->show($_SESSION['user']['id']);
               
                // Modification des informations de l'objet 'User' en cours
                $user->setFirstName($firstName);
                $user->setLastName($lastName);
                $user->setAddress($address);
                $user->setPostal($postal);
                $user->setCity($city);
                $user->setCountry($country);
                $user->setPhone($phone);
                $user->setSlug($slug);

                // On vérifie l'existence d'une image uploadée en testant sa taille
                if($_FILES['coverImage']['size'] > 0) {
                    // On définit le dossier cible des images de profil uploadées
                    $dir = "public/img/users/";
                    try {
                        // On appelle la fonction d'upload d'images de la classe Utility pour renommer et stocker l'image dans le répertoire défini. Cette fonction créée et retourne un nom pour l'image. Ce nom est stocké dans la variable $coverImage
                        $coverImage = Utility::uploadImage($_FILES['coverImage'], $dir);   
                        // On récupère l'ancienne image de profil de l'utilisateur éventuellement stockée en base de données 
                        $oldCoverImage = $user->getCoverImage();
                        // Si cette image de profil existe réellement
                        if($oldCoverImage) {
                            // On supprime cette image du dossier 'public/img/users/'
                            $this->deleteUserImageFile($oldCoverImage);
                        }
                        // Puis on affecte le nom de l'image uploadée à l'attribut $coverImage de l'objet User en cours 
                        $user->setCoverImage($coverImage);
                    } catch (Exception $e ) {
                        Utility::addAlertMessage($e->getMessage(), Utility::DANGER_MESSAGE);
                        Utility::redirect(URL."compte/modification_profil");
                    }
                } 

                // Si le tableau des erreurs de l'entité User n'est pas vide
                if(!empty($user->getErrors())) {
                    // On stocke le tableau des erreurs d'entité dans la session
                    Utility::addFormErrorsIntoSession($user->getErrors());
                    Utility::addAlertMessage("Le formulaire n'a pas pu être soumis. Certains champs sont incomplets ou invalides !", Utility::DANGER_MESSAGE);
                    Utility::redirect(URL."compte/modification_profil"); 
                } else {
                    // Sinon on enregistre les modifications en base de données et dans la session
                    $isRequestSuccess = $this->userManager->edit($user);
                    $_SESSION['user'] = [
                        "id" => $user->getId(),
                        "firstName" => $user->getFirstName(),
                        "lastName" => $user->getLastName(),
                        "coverImage" => $user->getCoverImage(),
                        "email" => $user->getEmail(),
                        "role" => $user->getRole()
                    ];
                    Utility::addAlertMessage("Votre profil a été modifié avec succès !", Utility::SUCCESS_MESSAGE);
                    Utility::redirect(URL."compte/profil");
                }

            } else {
               // Affichage d'un message d'alerte si le formulaire est incomplet
               Utility::addAlertMessage("Le formulaire est incomplet. Les champs requis sont obligatoires !", Utility::DANGER_MESSAGE);
               // Redirection de l'utilisateur vers la page d'inscription
               Utility::redirect(URL."compte/modification_profil"); 
            }
        }      
    }

    /**
     * Permet de gérer la suppression de l'image de l'utilisateur du dossier public/img/users
     *
     * @param [type] $oldCoverImage
     * @return void
     */
    private function deleteUserImageFile($oldCoverImage)
    {
        unlink("public/img/users/".$oldCoverImage);
    }

}






