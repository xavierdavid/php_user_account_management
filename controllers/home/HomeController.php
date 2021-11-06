<?php 

//Namespace 'Home' des contrôleurs
namespace App\Controllers\Home;

// Utilisation des classes
use App\Services\Utility;
use App\Services\Mail;
use App\Services\Security;
use App\Models\Entities\User;
use App\Controllers\MainController;
use App\Models\Managers\UserManager;

class HomeController extends MainController
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
     * Contrôle le paramétrage et l'affichage de la page d'accueil 
     *
     * @return void
     */
    public function home()
    {
        // Définition d'un tableau associatif regroupant les données d'affichage de la page d'accueil du site
        $data_page = [
            "page_description" => "Description de la page d'accueil",
            "page_title" => "Page d'accueil",
            "view" => "views/home/home.php",
            "template" => "views/common/template.php"
        ];
        // Affichage de la page à l'aide de la méthode generatePage à laquelle on envoie le tableau de données
        $this->generatePage($data_page);
    }

    /**
     * Rédéfinition de méthode pour appeler la méthode parente 'errorPage' du MainController
     *
     * @param [type] $errorMessage
     * @return void
     */
    public function errorPage($errorMessage)
    {
        parent::errorPage($errorMessage);
    }

    /**
     * Contrôle l'affichage de la page du formulaire d'inscription utilisateur
     *
     * @return void
     */
    public function register()
    {
        // Définition d'un tableau associatif regroupant les données d'affichage de la page du formulaire d'inscription
        $data_page = [
            "page_description" => "Page d'inscription",
            "page_title" => "Page d'inscription",
            "view" => "views/home/register.php",
            "template" => "views/common/template.php"
        ];
        // Affichage de la page à l'aide de la méthode generatePage à laquelle on envoie le tableau de données
        $this->generatePage($data_page);
    }

    /**
     * Contrôle le traitement du formulaire d'inscription utilisateur
     *
     * @return void
     */
    public function register_validation()
    {
        // Vérification de la soumission du formulaire
        if(!empty($_POST)) {
            // Vérification que tous les champs requis sont renseignés
            if(isset($_POST['first_name'], $_POST['last_name'], $_POST['address'], $_POST['postal'], $_POST['city'], $_POST['country'], $_POST['phone'], $_POST['email'], $_POST['password'], $_POST['password_confirm']) 
            && !empty($_POST['first_name']) 
            && !empty($_POST['last_name']) 
            && !empty($_POST['address']) 
            && !empty($_POST['postal']) 
            && !empty($_POST['city']) 
            && !empty($_POST['country'])
            && !empty($_POST['phone']) 
            && !empty($_POST['email']) 
            && !empty($_POST['password'])
            && !empty($_POST['password_confirm'])) {

                // Récupération, formatage et sécurisation des données du formulaire
                $firstName = Security::secureHtml($_POST['first_name']);
                $lastName = Security::secureHtml(strtoupper($_POST['last_name']));
                $address = Security::secureHtml($_POST['address']);
                $postal = Security::secureHtml($_POST['postal']);
                $city = Security::secureHtml(strtoupper($_POST['city']));
                $country = Security::secureHtml(strtoupper($_POST['country']));
                $coverImage = Security::secureHtml($_POST['cover_image']);
                $phone = Security::secureHtml($_POST['phone']);
                $email = Security::secureHtml($_POST['email']);
                $password = Security::secureHtml($_POST['password']);
                $password_confirm = Security::secureHtml($_POST['password_confirm']);
               
                // Si la case RGPD est cochée, on affecte la valeur 1 à la variable $isRgpd;
               if(isset($_POST['is_rgpd'])) {
                    $isRgpd = 1;
                } else {
                    // Sinon, on lui affecte la valeur 0
                    $isRgpd = 0;
                }

                // Si l'email saisi existe déjà dans la base de données 
                if($this->userManager->isUserEmailExist($email)) {
                    // On crée un tableau d'erreur $errorEmailUserExist
                    $errorEmailUserExist = [
                        'email_user_exist' => "EMAIL_USER_EXIST"
                    ];
                    // On insère le tableau d'erreur $errorEmailUserExist dans la session
                    $_SESSION['errorEmailUserExist'] = $errorEmailUserExist;  
                } 
        
                // Hachage du mot de passe
                $hashPassword = password_hash($password, PASSWORD_DEFAULT);

                // Génération automatique du slug de l'utilisateur à l'aide de la fonction generateSlug(), à partir d'une chaîne composée de son nom et de son prénom
                $text = $firstName . " " . $lastName;
                $slug = Utility::generateSlug($text);

                // Génération d'un token d'activation de compte
                $activationToken = Utility::generateActivationToken();

                // Statut de l'activation par email (0 par défaut)
                $isValid = 0;

                // Génération du rôle de l'utilisateur par défaut
                $role = "ROLE_USER";

                // Génération de la date de création du compte
                $createdAt = Utility::generateDate();

                // Stockage des données de l'utilisateur dans un tableau associatif $userData
                $userData = [
                    'firstName' => $firstName,
                    'lastName' => $lastName,
                    'address' => $address,
                    'postal' => $postal,
                    'city' => $city,
                    'country' => $country,
                    'coverImage' => $coverImage,
                    'phone' => $phone,
                    'email' => $email,
                    'password' => $hashPassword,
                    'slug' => $slug,
                    'activationToken' => $activationToken,
                    'isValid' => $isValid,
                    'role' => $role,
                    'createdAt' => $createdAt,
                    'isRgpd' => $isRgpd
                ];

                // Stockage temporaire des données de l'utilisateur dans la session pour pré-remplir le formulaire en cas d'erreur
                $_SESSION['registrationUserData'] = $userData;

                // Instanciation et hydratation d'un nouvel objet User à partir du tableau associatif des données utilisateurs $userData
                $user = new User($userData);

                // Si les deux mots de passe ne sont pas identiques
                if($password_confirm !== $password) {
                    // On crée un tableau d'erreur $errorConfirmPassword
                    $errorConfirmPassword = [
                        'invalid_confirm_password' => "INVALID_CONFIRM_PASSWORD"
                    ];
                    // On insère le tableau d'erreur $errorConfirmPassword dans la session
                    $_SESSION['errorConfirmPassword'] = $errorConfirmPassword;
                }

                // Si le nouvel objet User instancié n'est pas valide ou que les deux mots de passe ne sont pas identiques ou que l'email est déjà utilisé ou que la case RGPD n'est pas cochée...
                if(!$user->isUserValid() || $password_confirm !== $password || $this->userManager->isUserEmailExist($email) || $isRgpd == 0) {
                    // On récupère les erreurs de validation de l'entité User
                    $errors = $user->getErrors();
                    // On stocke le tableau des erreurs d'entité dans la session
                    Utility::addFormErrorsIntoSession($errors);
                
                    // On affiche un message d'alerte si le formulaire est incomplet
                    Utility::addAlertMessage("Le formulaire n'a pas pu être soumis. Certains champs sont incomplets ou invalides !", Utility::DANGER_MESSAGE);
                    // On redirige l'utilisateur vers la page d'inscription
                    Utility::redirect(URL."inscription");    
                } else {
                    // Sinon on envoie les données de l'utilisateur en base de données à l'aide du UserManager en vérifiant que la requête a bien abouti
                    if($this->userManager->new($user)) {
                        // On affiche un message d'alerte de succès
                        Utility::addAlertMessage("Votre compte a été créé avec succès ! Un mail vient d'être envoyé à l'adresse " . $user->getEmail() ." pour valider votre inscription.", Utility::SUCCESS_MESSAGE);
                        // On envoie un email d'activation de compte à l'utilisateur à partir de ses informations
                        Mail::userActivationMail($user);
                        
                        // On redirige l'utilisateur vers la page de login
                        Utility::redirect(URL."connexion");
                    } else {
                        // On affiche un message d'alerte à l'utilisateur
                        Utility::addAlertMessage("Erreur lors de la création de votre compte utilisateur !", Utility::DANGER_MESSAGE);
                        // On redirige l'utilisateur vers la page d'inscription
                        Utility::redirect(URL."connexion"); 
                    }
                }
            } else {
                // Affichage d'un message d'alerte si le formulaire est incomplet
                Utility::addAlertMessage("Le formulaire est incomplet. Les champs requis sont obligatoires !", Utility::DANGER_MESSAGE);
                // Redirection de l'utilisateur vers la page d'inscription
                Utility::redirect(URL."inscription");
            }
        }
    }

    /**
     * Contrôle l'affichage de la page du formulaire de connexion utilisateur
     *
     * @return void
     */
    public function login()
    {
        // Définition d'un tableau associatif regroupant les données d'affichage de la page du formulaire de connexion
        $data_page = [
            "page_description" => "Page de connexion",
            "page_title" => "Page de connexion",
            "view" => "views/home/login.php",
            "template" => "views/common/template.php"
        ];
        // Affichage de la page à l'aide de la méthode generatePage à laquelle on envoie le tableau de données
        $this->generatePage($data_page);
    }
}